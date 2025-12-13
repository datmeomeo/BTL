<?php
namespace Queries;

use PDO;
use Exception;

class BookDetailPageQuery
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @throws Exception
     */
    public function handle(int $bookId): BookDetailPageDto
    {
        // 1. Get Book Info
        $sql = "SELECT s.*, 
                       nxb.ten_nxb,
                       GROUP_CONCAT(DISTINCT tg.ten_tac_gia SEPARATOR ', ') as tac_gia,
                       AVG(dg.diem_danh_gia) as diem_trung_binh,
                       COUNT(DISTINCT dg.ma_danh_gia) as so_luong_danh_gia,
                       ROUND(((s.gia_goc - s.gia_ban) / s.gia_goc * 100), 0) as phan_tram_giam
                FROM SACH s
                LEFT JOIN NHA_XUAT_BAN nxb ON s.ma_nxb = nxb.ma_nxb
                LEFT JOIN SACH_TAC_GIA stg ON s.ma_sach = stg.ma_sach
                LEFT JOIN TAC_GIA tg ON stg.ma_tac_gia = tg.ma_tac_gia
                LEFT JOIN DANH_GIA dg ON s.ma_sach = dg.ma_sach AND dg.trang_thai = 'approved'
                WHERE s.ma_sach = ?
                GROUP BY s.ma_sach";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId]);
        $sach = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sach) {
            throw new Exception("Book not found with ID: " . $bookId);
        }

        // 2. Get Images
        $sqlImages = "SELECT duong_dan_hinh FROM HINH_ANH_SACH 
                      WHERE ma_sach = ? 
                      ORDER BY la_anh_chinh DESC, thu_tu ASC";
        $stmtImg = $this->db->prepare($sqlImages);
        $stmtImg->execute([$bookId]);
        $rawImages = $stmtImg->fetchAll(PDO::FETCH_COLUMN);

        $imagePrefix = 'assets/img-book/'; // Phải chắc chắn dòng này đúng đường dẫn

        $processedImages = [];
        if (!empty($rawImages)) {
            foreach ($rawImages as $imgName) {
                if (strpos($imgName, 'http') === 0) {
                    $processedImages[] = $imgName;
                } else {
                    // Phải có dòng nối chuỗi này
                    $processedImages[] = $imagePrefix . $imgName;
                }
            }
        }
        $mainImage = !empty($processedImages) ? $processedImages[0] : 'assets/img/no-image.jpg';
        $thumbnails = $processedImages;

        // 3. Get Related Data
        $categoryInfo = $this->getCategoryInfo($bookId);
        $reviews = $this->getReviews($bookId);

        return new BookDetailPageDto(
            bookId: (int) $sach['ma_sach'],
            bookName: $sach['ten_sach'],
            description: $sach['mo_ta'] ?? '',
            isbn: $sach['ma_isbn'] ?? (string) $sach['ma_sach'],
            sellingPrice: (float) $sach['gia_ban'],
            originalPrice: (float) $sach['gia_goc'],
            discountPercent: (int) ($sach['phan_tram_giam'] ?? 0),
            categoryName: $categoryInfo['categoryName'],
            parentCategoryName: $categoryInfo['parentCategoryName'],
            authorName: $sach['tac_gia'] ?? 'Đang cập nhật',
            publisherName: $sach['ten_nxb'] ?? 'NXB',
            supplierName: $sach['ten_nxb'] ?? 'Nhà cung cấp',
            translatorName: $sach['nguoi_dich'] ?? null,
            language: $sach['ngon_ngu'] ?? 'Tiếng Việt',
            publishYear: !empty($sach['nam_xuat_ban']) ? (int) $sach['nam_xuat_ban'] : null,
            coverForm: $sach['hinh_thuc_bia'] ?? 'Bìa mềm',
            weight: !empty($sach['trong_luong']) ? (int) $sach['trong_luong'] : null,
            dimensions: $sach['kich_thuoc'] ?? null,
            pageCount: !empty($sach['so_trang']) ? (int) $sach['so_trang'] : null,
            stockQuantity: (int) $sach['so_luong_ton'],
            averageRating: (float) ($sach['diem_trung_binh'] ?? 0),
            reviewCount: (int) $sach['so_luong_danh_gia'],
            mainImage: $mainImage,
            thumbnails: $thumbnails,
            reviews: $reviews
        );
    }

    private function getCategoryInfo(int $bookId): array
    {
        $sql = "SELECT dm.ten_danh_muc, dm.danh_muc_cha
                FROM DANH_MUC dm
                INNER JOIN SACH_DANH_MUC sdm ON dm.ma_danh_muc = sdm.ma_danh_muc
                WHERE sdm.ma_sach = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId]);
        $dm = $stmt->fetch(PDO::FETCH_ASSOC);

        $result = [
            'categoryName' => $dm['ten_danh_muc'] ?? null,
            'parentCategoryName' => null
        ];

        if ($dm && $dm['danh_muc_cha']) {
            $sqlCha = "SELECT ten_danh_muc FROM DANH_MUC WHERE ma_danh_muc = ?";
            $stmtCha = $this->db->prepare($sqlCha);
            $stmtCha->execute([$dm['danh_muc_cha']]);
            $cha = $stmtCha->fetch(PDO::FETCH_ASSOC);
            if ($cha) {
                $result['parentCategoryName'] = $cha['ten_danh_muc'];
            }
        }

        return $result;
    }

    private function getReviews(int $bookId): array
    {
        $sql = "SELECT dg.diem_danh_gia, dg.noi_dung, dg.ngay_danh_gia, nd.ho_ten
                FROM DANH_GIA dg
                INNER JOIN NGUOI_DUNG nd ON dg.ma_nguoi_dung = nd.ma_nguoi_dung
                WHERE dg.ma_sach = ? AND dg.trang_thai = 'approved'
                ORDER BY dg.ngay_danh_gia DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $result[] = [
                'reviewerName' => $row['ho_ten'],
                'rating' => (int) $row['diem_danh_gia'],
                'content' => $row['noi_dung'],
                'reviewDate' => $row['ngay_danh_gia']
            ];
        }

        return $result;
    }
}