<?php
class Author
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách tác giả, có phân trang và tìm kiếm
    public function getAuthors($limit, $offset, $ten = '', $quoc_tich = '')
    {
        $sql = "SELECT * FROM tac_gia WHERE 1=1";
        $params = [];

        if ($ten !== '') {
            $sql .= " AND ten_tac_gia LIKE :ten";
            $params[':ten'] = "%$ten%";
        }
        if ($quoc_tich !== '') {
            $sql .= " AND quoc_tich LIKE :quoc_tich";
            $params[':quoc_tich'] = "%$quoc_tich%";
        }

        $sql .= " ORDER BY ma_tac_gia DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng số tác giả (có tìm kiếm)
    public function getTotalAuthors($ten = '', $quoc_tich = '')
    {
        $sql = "SELECT COUNT(*) FROM tac_gia WHERE 1=1";
        $params = [];

        if ($ten !== '') {
            $sql .= " AND ten_tac_gia LIKE :ten";
            $params[':ten'] = "%$ten%";
        }
        if ($quoc_tich !== '') {
            $sql .= " AND quoc_tich LIKE :quoc_tich";
            $params[':quoc_tich'] = "%$quoc_tich%";
        }

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function addAuthor($data)
    {
        $sql = "INSERT INTO tac_gia (ten_tac_gia, ngay_sinh, quoc_tich, tieu_su)
                VALUES (:ten_tac_gia, :ngay_sinh, :quoc_tich, :tieu_su)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateAuthor($data)
    {
        $sql = "UPDATE tac_gia SET
                ten_tac_gia=:ten_tac_gia, 
                ngay_sinh=:ngay_sinh, 
                quoc_tich=:quoc_tich, 
                tieu_su=:tieu_su
                WHERE ma_tac_gia=:ma_tac_gia";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteAuthor($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM tac_gia WHERE ma_tac_gia = ?");
        return $stmt->execute([$id]);
    }
}
