<?php
class Publisher
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách nhà xuất bản, phân trang và tìm kiếm
    public function getPublishers($limit, $offset, $ten = '', $email = '')
    {
        $sql = "SELECT * FROM nha_xuat_ban WHERE 1=1";
        $params = [];

        if ($ten !== '') {
            $sql .= " AND ten_nxb LIKE :ten";
            $params[':ten'] = "%$ten%";
        }
        if ($email !== '') {
            $sql .= " AND email LIKE :email";
            $params[':email'] = "%$email%";
        }

        $sql .= " ORDER BY ma_nxb LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng số nhà xuất bản (có tìm kiếm)
    public function getTotalPublishers($ten = '', $email = '')
    {
        $sql = "SELECT COUNT(*) FROM nha_xuat_ban WHERE 1=1";
        $params = [];

        if ($ten !== '') {
            $sql .= " AND ten_nxb LIKE :ten";
            $params[':ten'] = "%$ten%";
        }
        if ($email !== '') {
            $sql .= " AND email LIKE :email";
            $params[':email'] = "%$email%";
        }

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function addPublisher($data)
    {
        $sql = "INSERT INTO nha_xuat_ban (ten_nxb, dia_chi, so_dien_thoai, email)
                VALUES (:ten_nxb, :dia_chi, :so_dien_thoai, :email)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function updatePublisher($data)
    {
        $sql = "UPDATE nha_xuat_ban SET
                    ten_nxb = :ten_nxb,
                    dia_chi = :dia_chi,
                    so_dien_thoai = :so_dien_thoai,
                    email = :email
                WHERE ma_nxb = :ma_nxb";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function deletePublisher($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM nha_xuat_ban WHERE ma_nxb = ?");
        return $stmt->execute([$id]);
    }
}
