<?php

namespace Queries;

use Exception;
use PDO;
use Queries\SearchProductPageDto;

class SearchProductPageQuery
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Lấy danh sách sách theo bộ lọc
     * @param array $params (page, limit, sort, keyword, category_id, price_min, price_max...)
     * @return SearchProductPageDto[]
     * @throws Exception
     */
    public function search(array $params = []): array
    {
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $limit = isset($params['limit']) ? (int)$params['limit'] : 24;
        $offset = ($page - 1) * $limit;

        // 1. Xây dựng câu truy vấn cơ bản
        $sql = "
            SELECT 
                s.ma_sach, 
                s.ten_sach, 
                s.mo_ta, 
                s.ma_isbn, 
                s.gia_ban, 
                s.gia_goc,
                nxb.ten_nxb,
                -- Lấy ảnh chính (hoặc ảnh đầu tiên nếu không có ảnh chính)
                (SELECT duong_dan_hinh FROM hinh_anh_sach has WHERE has.ma_sach = s.ma_sach ORDER BY la_anh_chinh DESC LIMIT 1) as hinh_anh,
                -- Lấy tên danh mục đầu tiên
                (SELECT dm.ten_danh_muc FROM danh_muc dm 
                 JOIN sach_danh_muc sdm ON dm.ma_danh_muc = sdm.ma_danh_muc 
                 WHERE sdm.ma_sach = s.ma_sach LIMIT 1) as ten_danh_muc,
                 -- Lấy tên danh mục cha (nếu có)
                (SELECT dm2.ten_danh_muc FROM danh_muc dm 
                 JOIN sach_danh_muc sdm ON dm.ma_danh_muc = sdm.ma_danh_muc 
                 LEFT JOIN danh_muc dm2 ON dm.danh_muc_cha = dm2.ma_danh_muc
                 WHERE sdm.ma_sach = s.ma_sach LIMIT 1) as ten_danh_muc_cha,
                -- Lấy danh sách tác giả (gộp chuỗi)
                (SELECT GROUP_CONCAT(tg.ten_tac_gia SEPARATOR ', ') 
                 FROM tac_gia tg 
                 JOIN sach_tac_gia stg ON tg.ma_tac_gia = stg.ma_tac_gia 
                 WHERE stg.ma_sach = s.ma_sach) as ten_tac_gia
            FROM sach s
            LEFT JOIN nha_xuat_ban nxb ON s.ma_nxb = nxb.ma_nxb
            WHERE s.trang_thai = 'available'
        ";

        // 2. Xây dựng điều kiện lọc động (Dynamic Where)
        $bindings = [];

        // Lọc theo từ khóa (tìm trong tên sách hoặc tên tác giả)
        if (!empty($params['keyword'])) {
            $keyword = '%' . $params['keyword'] . '%';
            $sql .= " AND (s.ten_sach LIKE :keyword OR s.ma_sach IN (
                        SELECT stg.ma_sach FROM sach_tac_gia stg 
                        JOIN tac_gia tg ON stg.ma_tac_gia = tg.ma_tac_gia 
                        WHERE tg.ten_tac_gia LIKE :keyword_author
                      ))";
            $bindings[':keyword'] = $keyword;
            $bindings[':keyword_author'] = $keyword;
        }

        // Lọc theo danh mục
        if (!empty($params['category_id'])) {
            $sql .= " AND s.ma_sach IN (SELECT ma_sach FROM sach_danh_muc WHERE ma_danh_muc = :category_id)";
            $bindings[':category_id'] = $params['category_id'];
        }

        // Lọc theo khoảng giá
        if (!empty($params['price_min'])) {
            $sql .= " AND s.gia_ban >= :price_min";
            $bindings[':price_min'] = $params['price_min'];
        }
        if (!empty($params['price_max'])) {
            $sql .= " AND s.gia_ban <= :price_max";
            $bindings[':price_max'] = $params['price_max'];
        }

        // 3. Xây dựng Sắp xếp (Sorting)
        $sort = $params['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY s.gia_ban ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY s.gia_ban DESC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY s.ten_sach ASC";
                break;
            case 'view_desc':
                $sql .= " ORDER BY s.luot_xem DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY s.ngay_them DESC";
                break;
        }

        // 4. Phân trang
        $sql .= " LIMIT :limit OFFSET :offset";

        // 5. Thực thi truy vấn
        $stmt = $this->db->prepare($sql);
        
        // Bind các giá trị filter
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        // Bind limit và offset (phải là kiểu INT)
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Map kết quả sang DTO
        $result = [];
        foreach ($rows as $row) {
            // Tính phần trăm giảm giá
            $discountPercent = 0;
            if ($row['gia_goc'] > 0 && $row['gia_goc'] > $row['gia_ban']) {
                $discountPercent = round((($row['gia_goc'] - $row['gia_ban']) / $row['gia_goc']) * 100);
            }

            $result[] = new SearchProductPageDto(
                bookId: (int)$row['ma_sach'],
                bookName: $row['ten_sach'],
                description: $row['mo_ta'] ?? '',
                isbn: $row['ma_isbn'],
                sellingPrice: (float)$row['gia_ban'],
                originalPrice: (float)$row['gia_goc'],
                discountPercent: (int)$discountPercent,
                categoryName: $row['ten_danh_muc'],
                parentCategoryName: $row['ten_danh_muc_cha'],
                authorName: $row['ten_tac_gia'] ?? 'Đang cập nhật',
                publisherName: $row['ten_nxb'] ?? 'Đang cập nhật',
                mainImage: $row['hinh_anh'] ?? './assets/img/default-book.jpg'
            );
        }

        return $result;
    }

    /**
     * Lấy danh sách tất cả danh mục (cho filter sidebar)
     */
    public function getAllCategories(): array
    {
        $sql = "SELECT ma_danh_muc, ten_danh_muc FROM danh_muc WHERE hien_thi_menu = 1 ORDER BY thu_tu ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách tác giả (cho filter sidebar)
     */
    public function getAllAuthors(): array
    {
        $sql = "SELECT ma_tac_gia, ten_tac_gia FROM tac_gia ORDER BY ten_tac_gia ASC LIMIT 20"; // Limit để demo, thực tế nên có logic load more
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}