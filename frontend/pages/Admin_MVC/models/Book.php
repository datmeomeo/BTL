<?php
class Book
{
    public $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách sách + tên NXB + tên tất cả tác giả
    public function getBooks($limit, $offset, $search_ten = '', $search_tacgia = '', $search_nxb = '')
{
    $sql = "
        SELECT DISTINCT s.*, n.ten_nxb,
               GROUP_CONCAT(DISTINCT tg.ten_tac_gia SEPARATOR ', ') AS ten_tac_gia
        FROM sach s
        LEFT JOIN nha_xuat_ban n ON s.ma_nxb = n.ma_nxb
        LEFT JOIN sach_tac_gia stg ON s.ma_sach = stg.ma_sach
        LEFT JOIN tac_gia tg ON stg.ma_tac_gia = tg.ma_tac_gia
        WHERE 1=1
    ";

    $params = [];

    if ($search_ten !== '') {
        $sql .= " AND s.ten_sach LIKE :ten_sach";
        $params[':ten_sach'] = "%$search_ten%";
    }
    if ($search_tacgia !== '') {
        $sql .= " AND stg.ma_tac_gia = :tacgia";
        $params[':tacgia'] = $search_tacgia;
    }
    if ($search_nxb !== '') {
        $sql .= " AND s.ma_nxb = :nxb";
        $params[':nxb'] = $search_nxb;
    }

    $sql .= " GROUP BY s.ma_sach ORDER BY s.ma_sach DESC LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($sql);

    // Bind all params
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }

    // Bind LIMIT and OFFSET as integers
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getTotal($search_ten = '', $search_tacgia = '', $search_nxb = '')
    {
        $sql = "SELECT COUNT(DISTINCT s.ma_sach) FROM sach s
            LEFT JOIN sach_tac_gia stg ON s.ma_sach = stg.ma_sach
            WHERE 1=1";

        $params = [];

        if ($search_ten !== '') {
            $sql .= " AND s.ten_sach LIKE ?";
            $params[] = "%$search_ten%";
        }
        if ($search_tacgia !== '') {
            $sql .= " AND stg.ma_tac_gia = ?";
            $params[] = $search_tacgia;
        }
        if ($search_nxb !== '') {
            $sql .= " AND s.ma_nxb = ?";
            $params[] = $search_nxb;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getCategoriesByBook($ma_sach)
    {
        $stmt = $this->conn->prepare("SELECT ma_danh_muc FROM sach_danh_muc WHERE ma_sach = ?");
        $stmt->execute([$ma_sach]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    // === LẤY TÁC GIẢ CHÍNH (ID) CỦA 1 CUỐN SÁCH ===
    public function getMainAuthorId($ma_sach)
    {
        $stmt = $this->conn->prepare("
            SELECT ma_tac_gia 
            FROM sach_tac_gia 
            WHERE ma_sach = ? 
            ORDER BY ma_tac_gia 
            LIMIT 1
        ");
        $stmt->execute([$ma_sach]);
        return $stmt->fetchColumn() ?: '';
    }
    public function add($data)
    {
        $sql = "INSERT INTO sach (
                    ten_sach, ma_nxb, mo_ta, gia_ban, gia_goc, so_luong_ton,
                    so_trang, hinh_thuc_bia, ngon_ngu, nam_xuat_ban, ma_isbn
                ) VALUES (
                    :ten_sach, :ma_nxb, :mo_ta, :gia_ban, :gia_goc, :so_luong_ton,
                    :so_trang, :hinh_thuc_bia, :ngon_ngu, :nam_xuat_ban, :ma_isbn
                )";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }

    public function update($data)
    {
        $sql = "UPDATE sach SET
                ten_sach=:ten_sach,
                ma_nxb=:ma_nxb,
                mo_ta=:mo_ta,
                gia_ban=:gia_ban,
                gia_goc=:gia_goc,
                so_luong_ton=:so_luong_ton,
                so_trang=:so_trang,
                hinh_thuc_bia=:hinh_thuc_bia,
                ngon_ngu=:ngon_ngu,
                nam_xuat_ban=:nam_xuat_ban,
                ma_isbn=:ma_isbn
                WHERE ma_sach=:ma_sach";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $this->conn->beginTransaction();
        try {
            $this->conn->exec("DELETE FROM sach_tac_gia WHERE ma_sach = " . (int)$id);
            $this->conn->exec("DELETE FROM sach_danh_muc WHERE ma_sach = " . (int)$id);
            $this->conn->exec("DELETE FROM sach WHERE ma_sach = " . (int)$id);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Dữ liệu cho form
    public function getPublishers()
    {
        return $this->conn->query("SELECT ma_nxb, ten_nxb FROM nha_xuat_ban ORDER BY ten_nxb")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAuthors()
    {
        return $this->conn->query("SELECT ma_tac_gia, ten_tac_gia FROM tac_gia ORDER BY ten_tac_gia")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCategories()
    {
        return $this->conn->query("SELECT ma_danh_muc, ten_danh_muc FROM danh_muc ORDER BY ten_danh_muc")->fetchAll(PDO::FETCH_ASSOC);
    }
}
