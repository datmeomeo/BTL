<?php
require_once "models/Category.php";

class CategoryController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new Category($db);
    }

    public function index()
    {
        $page_current = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $per_page = 10;
        $offset = ($page_current - 1) * $per_page;

        $search_ten = $_GET['search_ten'] ?? '';

        $total = $this->model->getTotalCategories($search_ten);
        $total_pages_view = ceil($total / $per_page);

        $categories = $this->model->getCategories($per_page, $offset, $search_ten);

        $success = $_GET['success'] ?? '';
        $error = $_GET['error'] ?? '';

        ob_start();
        include "views/category/index.php";
        $content = ob_get_clean();

        $page = 'category';
        include "views/layout/main.php";
    }

    public function save()
    {
        $action = $_POST['action'] ?? '';
        $data = [
            ':ten_danh_muc' => $_POST['ten_danh_muc'],
            ':slug' => $_POST['slug'],
            ':mo_ta' => $_POST['mo_ta'],
            ':danh_muc_cha' => $_POST['danh_muc_cha'] !== '' ? (int)$_POST['danh_muc_cha'] : null,
            ':cap_do' => (int)($_POST['cap_do'] ?? 1),
            ':thu_tu' => (int)($_POST['thu_tu'] ?? 0),
            ':hien_thi_menu' => (int)($_POST['hien_thi_menu'] ?? 1),
            ':icon' => $_POST['icon'],
            ':mau_sac' => $_POST['mau_sac'],
            ':la_danh_muc_noi_bat' => (int)($_POST['la_danh_muc_noi_bat'] ?? 0),
        ];

        if ($action === 'add') {
            $ok = $this->model->addCategory($data);
            $msg = $ok ? "Thêm danh mục thành công!" : "Lỗi khi thêm!";
        } elseif ($action === 'update') {
            $data[':ma_danh_muc'] = $_POST['ma_danh_muc'];
            $ok = $this->model->updateCategory($data);
            $msg = $ok ? "Cập nhật thành công!" : "Cập nhật thất bại!";
        }

        header("Location: index.php?page=category&success=$msg");
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $this->model->deleteCategory($id);
            header("Location: index.php?page=category&success=Xóa thành công!");
        } else {
            header("Location: index.php?page=category&error=ID không hợp lệ!");
        }
        exit;
    }
}
