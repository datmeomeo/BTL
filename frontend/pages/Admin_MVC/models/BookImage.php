<?php
class BookImageModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy danh sách sách kèm tên thư mục (slug danh mục) để hiển thị dropdown và biết nơi lưu ảnh
    // Giả sử bảng danh_muc có cột 'ten_khong_dau' hoặc 'slug' trùng tên thư mục (vd: book-kinhte)
    public function getAllBooks() {
        // 1. Sửa 'ten_khong_dau' thành 'slug'
        // 2. Thêm JOIN với bảng trung gian 'sach_danh_muc' (vì bảng 'sach' không chứa 'ma_danh_muc')
        // 3. Dùng GROUP BY để sách không bị lặp lại nếu thuộc nhiều danh mục
        
        $sql = "SELECT s.ma_sach, s.ten_sach, MIN(dm.slug) as folder_name 
                FROM sach s 
                JOIN sach_danh_muc sdm ON s.ma_sach = sdm.ma_sach
                JOIN danh_muc dm ON sdm.ma_danh_muc = dm.ma_danh_muc
                GROUP BY s.ma_sach, s.ten_sach"; 
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách ảnh của một cuốn sách cụ thể
    public function getImagesByBookId($ma_sach) {
        $sql = "SELECT * FROM hinh_anh_sach WHERE ma_sach = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$ma_sach]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm ảnh mới
    public function addImage($ma_sach, $duong_dan, $la_anh_chinh) {
        // Nếu chọn là ảnh chính, cần set các ảnh khác của sách này về 0 trước (optional logic)
        if ($la_anh_chinh == 1) {
            $sqlReset = "UPDATE hinh_anh_sach SET la_anh_chinh = 0 WHERE ma_sach = ?";
            $stmtReset = $this->conn->prepare($sqlReset);
            $stmtReset->execute([$ma_sach]);
        }

        $sql = "INSERT INTO hinh_anh_sach (ma_sach, duong_dan_hinh, la_anh_chinh, thu_tu) VALUES (?, ?, ?, 0)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$ma_sach, $duong_dan, $la_anh_chinh]);
    }

    // Xóa ảnh
    public function deleteImage($ma_hinh_anh) {
        // Lấy đường dẫn trước để xóa file vật lý
        $sqlGet = "SELECT duong_dan_hinh FROM hinh_anh_sach WHERE ma_hinh_anh = ?";
        $stmtGet = $this->conn->prepare($sqlGet);
        $stmtGet->execute([$ma_hinh_anh]);
        $img = $stmtGet->fetch(PDO::FETCH_ASSOC);

        if ($img) {
            // Xóa trong DB
            $sqlDel = "DELETE FROM hinh_anh_sach WHERE ma_hinh_anh = ?";
            $stmtDel = $this->conn->prepare($sqlDel);
            $stmtDel->execute([$ma_hinh_anh]);
            return $img['duong_dan_hinh']; // Trả về đường dẫn để Controller xóa file
        }
        return false;
    }
    
    // Đặt làm ảnh chính (Sửa)
    public function setMainImage($ma_hinh_anh, $ma_sach) {
        $this->conn->beginTransaction();
        try {
            // Reset tất cả về 0
            $sql1 = "UPDATE hinh_anh_sach SET la_anh_chinh = 0 WHERE ma_sach = ?";
            $this->conn->prepare($sql1)->execute([$ma_sach]);
            
            // Set ảnh được chọn về 1
            $sql2 = "UPDATE hinh_anh_sach SET la_anh_chinh = 1 WHERE ma_hinh_anh = ?";
            $this->conn->prepare($sql2)->execute([$ma_hinh_anh]);
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>