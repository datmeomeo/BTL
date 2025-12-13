<?php
class Category
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách category, phân trang và tìm kiếm
    public function getCategories($limit, $offset, $ten = '')
    {
        $sql = "SELECT * FROM danh_muc WHERE 1=1";
        $params = [];

        if ($ten !== '') {
            $sql .= " AND ten_danh_muc LIKE :ten";
            $params[':ten'] = "%$ten%";
        }

        $sql .= " ORDER BY ma_danh_muc LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng số category (có tìm kiếm)
    public function getTotalCategories($ten = '')
    {
        $sql = "SELECT COUNT(*) FROM danh_muc WHERE 1=1";
        $params = [];

        if ($ten !== '') {
            $sql .= " AND ten_danh_muc LIKE :ten";
            $params[':ten'] = "%$ten%";
        }

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function addCategory($data)
    {
        $sql = "INSERT INTO danh_muc (ten_danh_muc, slug, mo_ta, danh_muc_cha, cap_do, thu_tu, hien_thi_menu, icon, mau_sac, la_danh_muc_noi_bat)
                VALUES (:ten_danh_muc, :slug, :mo_ta, :danh_muc_cha, :cap_do, :thu_tu, :hien_thi_menu, :icon, :mau_sac, :la_danh_muc_noi_bat)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateCategory($data)
    {
        $sql = "UPDATE danh_muc SET
                    ten_danh_muc=:ten_danh_muc,
                    slug=:slug,
                    mo_ta=:mo_ta,
                    danh_muc_cha=:danh_muc_cha,
                    cap_do=:cap_do,
                    thu_tu=:thu_tu,
                    hien_thi_menu=:hien_thi_menu,
                    icon=:icon,
                    mau_sac=:mau_sac,
                    la_danh_muc_noi_bat=:la_danh_muc_noi_bat
                WHERE ma_danh_muc=:ma_danh_muc";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteCategory($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM danh_muc WHERE ma_danh_muc = ?");
        return $stmt->execute([$id]);
    }
}
