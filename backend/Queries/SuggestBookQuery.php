<?php
namespace Queries;
use PDO;

class SuggestBookQuery
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Lấy danh sách sách gợi ý
     * @return SuggestBookDto[]
     */
    public function handle(): array
    {
        $sql = "SELECT s.*, 
               h.duong_dan_hinh,
               nxb.ten_nxb,
               ROUND(((s.gia_goc - s.gia_ban) / s.gia_goc * 100), 0) as phan_tram_giam
        FROM SACH s
        LEFT JOIN HINH_ANH_SACH h ON s.ma_sach = h.ma_sach AND h.la_anh_chinh = TRUE
        LEFT JOIN NHA_XUAT_BAN nxb ON s.ma_nxb = nxb.ma_nxb
        WHERE s.trang_thai = 'available'
        ORDER BY RAND()
        LIMIT 30";

        $stmt = $this->db->query($sql);
        $danhSachSach = $stmt->fetchAll();

        $result = [];
        foreach ($danhSachSach as $sach) {
            $dto = new SuggestBookDto(
                bookId: (int)$sach['ma_sach'],
                bookName: $sach['ten_sach'],
                publisherName: $sach['ten_nxb'] ?? 'NXB',
                sellingPrice: (float)$sach['gia_ban'],
                originalPrice: (float)$sach['gia_goc'],
                discountPercent: (int)$sach['phan_tram_giam'],
                addedDate: new \DateTime($sach['ngay_them']),
                imagePath: $sach['duong_dan_hinh'] ?? ''
            );
            $result[] = $dto->toArray();
        } 
        return $result;
    }
}
