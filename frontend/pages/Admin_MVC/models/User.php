<?php
class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // LẤY DANH SÁCH NGƯỜI DÙNG
    public function getUsers($limit, $offset, $ten = '', $vaitro = '', $trangthai = '')
    {
        try {
            $sql = "SELECT * FROM nguoi_dung WHERE 1=1";
            $params = [];
            //tìm kiếm
            if ($ten !== '') {
                $sql .= " AND ten_dang_nhap LIKE :ten";
                $params[':ten'] = "%$ten%";
            }
            if ($vaitro !== '') {
                $sql .= " AND vai_tro = :vaitro";
                $params[':vaitro'] = $vaitro;
            }
            if ($trangthai !== '') {
                $sql .= " AND trang_thai = :trangthai";
                $params[':trangthai'] = $trangthai;
            }
            //sắp xếp lại 
            $sql .= " ORDER BY ma_nguoi_dung LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);

            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("User::getUsers error: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalUsers($ten = '', $vaitro = '', $trangthai = '')
    {
        try {
            $sql = "SELECT COUNT(*) FROM nguoi_dung WHERE 1=1";
            $params = [];

            if ($ten !== '') {
                $sql .= " AND ten_dang_nhap LIKE :ten";
                $params[':ten'] = "%$ten%";
            }
            if ($vaitro !== '') {
                $sql .= " AND vai_tro = :vaitro";
                $params[':vaitro'] = $vaitro;
            }
            if ($trangthai !== '') {
                $sql .= " AND trang_thai = :trangthai";
                $params[':trangthai'] = $trangthai;
            }

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("User::getTotalUsers error: " . $e->getMessage());
            return 0;
        }
    }

    // KIỂM TRA TRÙNG TÊN ĐĂNG NHẬP HOẶC EMAIL
    private function isDuplicate($ten_dang_nhap, $email, $exclude_id = 0)
    {
        $sql = "SELECT ma_nguoi_dung FROM nguoi_dung 
                WHERE (ten_dang_nhap = ? OR email = ?) AND ma_nguoi_dung != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$ten_dang_nhap, $email, $exclude_id]);
        return $stmt->rowCount() > 0;
    }

    // THÊM NGƯỜI DÙNG – MẬT KHẨU MẶC ĐỊNH = 123456
    public function addUser($data)
    {
        try {
            if ($this->isDuplicate($data['ten_dang_nhap'], $data['email'])) {
                throw new Exception("Tên đăng nhập hoặc email đã tồn tại!");
            }

            $hashed = password_hash('123456', PASSWORD_DEFAULT);

            $sql = "INSERT INTO nguoi_dung 
                    (ten_dang_nhap, email, mat_khau, ho_ten, so_dien_thoai, vai_tro, trang_thai, ngay_dang_ky)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['ten_dang_nhap'],
                $data['email'],
                $hashed,
                $data['ho_ten'] ?? '',
                $data['so_dien_thoai'] ?? '',
                $data['vai_tro'] ?? 'customer',
                $data['trang_thai'] ?? 'active'
            ]);

        } catch (Exception $e) {
            throw new Exception("Thêm thất bại: " . $e->getMessage());
        }
    }

    // CẬP NHẬT – KHÔNG ĐỔI MẬT KHẨU
    public function updateUser($data)
    {
        try {
            if ($this->isDuplicate($data['ten_dang_nhap'], $data['email'], $data['ma_nguoi_dung'])) {
                throw new Exception("Tên đăng nhập hoặc email đã được sử dụng!");
            }

            $sql = "UPDATE nguoi_dung SET 
                    ten_dang_nhap = ?, 
                    email = ?, 
                    ho_ten = ?, 
                    so_dien_thoai = ?, 
                    vai_tro = ?, 
                    trang_thai = ?
                    WHERE ma_nguoi_dung = ?";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['ten_dang_nhap'],
                $data['email'],
                $data['ho_ten'] ?? '',
                $data['so_dien_thoai'] ?? '',
                $data['vai_tro'] ?? 'customer',
                $data['trang_thai'] ?? 'active',
                $data['ma_nguoi_dung']
            ]);

        } catch (Exception $e) {
            throw new Exception("Cập nhật thất bại: " . $e->getMessage());
        }
    }

    // XÓA NGƯỜI DÙNG
    public function deleteUser($id)
    {
        try {
            if ($id <= 1) {
                throw new Exception("Không thể xóa tài khoản admin gốc!");
            }
            $stmt = $this->conn->prepare("DELETE FROM nguoi_dung WHERE ma_nguoi_dung = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            throw new Exception("Xóa thất bại: " . $e->getMessage());
        }
    }
}