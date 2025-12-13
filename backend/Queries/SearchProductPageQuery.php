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

    public function search(array $params = []): array
    {
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $limit = isset($params['limit']) ? (int)$params['limit'] : 20;
        $offset = ($page - 1) * $limit;

        // 1. CHUẨN BỊ CTE (Common Table Expression) CHO ĐỆ QUY
        // Nếu có chọn danh mục, ta tạo bảng tạm đệ quy 'CategoryTree'
        $cteSql = "";
        $bindings = [];

        if (!empty($params['category_id']) && is_numeric($params['category_id'])) {
            $cteSql = "
                WITH RECURSIVE CategoryTree AS (
                    -- Neo: Lấy danh mục gốc được chọn
                    SELECT ma_danh_muc 
                    FROM danh_muc 
                    WHERE ma_danh_muc = :category_id
                    
                    UNION ALL
                    
                    -- Đệ quy: Lấy các danh mục con của những thằng đã tìm thấy
                    SELECT dm.ma_danh_muc 
                    FROM danh_muc dm
                    JOIN CategoryTree ct ON dm.danh_muc_cha = ct.ma_danh_muc
                )
            ";
            $bindings[':category_id'] = $params['category_id'];
        }

        // 2. CÂU SELECT CHÍNH
        $selectSql = "
            SELECT 
                s.ma_sach, s.ten_sach, s.mo_ta, s.gia_ban, s.gia_goc, nxb.ten_nxb,
                (SELECT duong_dan_hinh FROM hinh_anh_sach has WHERE has.ma_sach = s.ma_sach ORDER BY la_anh_chinh DESC LIMIT 1) as hinh_anh,
                (SELECT dm.ten_danh_muc FROM danh_muc dm JOIN sach_danh_muc sdm ON dm.ma_danh_muc = sdm.ma_danh_muc WHERE sdm.ma_sach = s.ma_sach LIMIT 1) as ten_danh_muc,
                (SELECT GROUP_CONCAT(tg.ten_tac_gia SEPARATOR ', ') FROM tac_gia tg JOIN sach_tac_gia stg ON tg.ma_tac_gia = stg.ma_tac_gia WHERE stg.ma_sach = s.ma_sach) as ten_tac_gia
        ";

        // 3. ĐIỀU KIỆN LỌC (WHERE)
        $whereSql = " FROM sach s LEFT JOIN nha_xuat_ban nxb ON s.ma_nxb = nxb.ma_nxb WHERE s.trang_thai = 'available' ";

        // Kết hợp CTE vào WHERE: Chỉ lấy sách thuộc các danh mục trong cây CategoryTree
        if ($cteSql !== "") {
            $whereSql .= " AND s.ma_sach IN (
                SELECT ma_sach FROM sach_danh_muc WHERE ma_danh_muc IN (SELECT ma_danh_muc FROM CategoryTree)
            )";
        }

        // Lọc Keyword
        if (!empty($params['keyword'])) {
            $keyword = '%' . $params['keyword'] . '%';
            $whereSql .= " AND s.ten_sach LIKE :keyword";
            $bindings[':keyword'] = $keyword;
        }

        // Lọc Tác giả
        if (!empty($params['author'])) {
            $whereSql .= " AND s.ma_sach IN (
                SELECT stg.ma_sach FROM sach_tac_gia stg JOIN tac_gia tg ON stg.ma_tac_gia = tg.ma_tac_gia WHERE tg.ten_tac_gia = :author_filter
            )";
            $bindings[':author_filter'] = $params['author'];
        }

        // Lọc Giá
        if (isset($params['price_min']) && $params['price_min'] !== '') {
            $whereSql .= " AND s.gia_ban >= :price_min";
            $bindings[':price_min'] = (int)$params['price_min'];
        }
        if (isset($params['price_max']) && $params['price_max'] !== '') {
            $whereSql .= " AND s.gia_ban <= :price_max";
            $bindings[':price_max'] = (int)$params['price_max'];
        }

        // 4. ĐẾM TỔNG SỐ (Cần nối cả CTE vào câu đếm để đếm đúng số sách trong cây danh mục)
        $countQuery = $cteSql . " SELECT COUNT(*) as total " . $whereSql;
        $stmtCount = $this->db->prepare($countQuery);
        foreach ($bindings as $key => $value) $stmtCount->bindValue($key, $value);
        $stmtCount->execute();
        $totalBooks = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

        // 5. SẮP XẾP
        $sort = $params['sort'] ?? 'newest';
        $orderBy = match ($sort) {
            'price_asc' => " ORDER BY s.gia_ban ASC",
            'price_desc' => " ORDER BY s.gia_ban DESC",
            'name_asc' => " ORDER BY s.ten_sach ASC",
            default => " ORDER BY s.ngay_them DESC",
        };

        // 6. THỰC THI CHÍNH
        // Ghép chuỗi: CTE + SELECT + WHERE + ORDER + LIMIT
        $finalSql = $cteSql . $selectSql . $whereSql . $orderBy . " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($finalSql);
        foreach ($bindings as $key => $value) $stmt->bindValue($key, $value);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $imagePrefix = 'assets/img-book/';
        // Map sang DTO
        $dtos = [];
        foreach ($rows as $row) {
            $discount = ($row['gia_goc'] > 0 && $row['gia_goc'] > $row['gia_ban']) 
                ? round((($row['gia_goc'] - $row['gia_ban']) / $row['gia_goc']) * 100) : 0;
            
            // --- XỬ LÝ HÌNH ẢNH (Mới thêm) ---
            $rawImage = $row['hinh_anh'];
            // Ảnh mặc định nếu trong DB là null
            $finalImage = './assets/img/fahasa-logo.jpg'; 

            if ($rawImage) {
                // Nếu link là http (ảnh mạng) thì giữ nguyên
                if (strpos($rawImage, 'http') === 0) {
                    $finalImage = $rawImage;
                } else {
                    // Nối prefix vào trước tên file lấy từ DB
                    $finalImage = $imagePrefix . $rawImage;
                }
            }
            
            $dtos[] = new SearchProductPageDto(
                (int)$row['ma_sach'], $row['ten_sach'], $row['mo_ta'] ?? '', null,
                (float)$row['gia_ban'], (float)$row['gia_goc'], (int)$discount,
                $row['ten_danh_muc'], null,
                $row['ten_tac_gia'] ?? '', $row['ten_nxb'] ?? '',
                $finalImage
            );
        }

        return ['books' => $dtos, 'total' => (int)$totalBooks, 'page' => $page, 'limit' => $limit];
    }

    // Các hàm phụ trợ giữ nguyên
    public function getAllCategories(): array {
        return $this->db->query("SELECT ma_danh_muc, ten_danh_muc, danh_muc_cha FROM danh_muc WHERE hien_thi_menu = 1 ORDER BY thu_tu ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllAuthors(): array {
        return $this->db->query("SELECT ma_tac_gia, ten_tac_gia FROM tac_gia ORDER BY ten_tac_gia ASC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
    }
}