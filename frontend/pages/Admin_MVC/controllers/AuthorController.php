<?php
require_once "models/Author.php";

class AuthorController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new Author($db);
    }

    public function index()
    {
        $page_current = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $per_page = 10;
        $offset = ($page_current - 1) * $per_page;

        $search_ten = $_GET['search_ten'] ?? '';
        $search_quoc_tich = $_GET['search_quoc_tich'] ?? '';

        $total = $this->model->getTotalAuthors($search_ten, $search_quoc_tich);
        $total_pages_view = ceil($total / $per_page);

        $authors = $this->model->getAuthors($per_page, $offset, $search_ten, $search_quoc_tich);
        $search_ten = $_GET['search_ten'] ?? '';
        $search_quoc_tich = $_GET['search_quoc_tich'] ?? '';

        $total = $this->model->getTotalAuthors($search_ten, $search_quoc_tich);
        $authors = $this->model->getAuthors($per_page, $offset, $search_ten, $search_quoc_tich);


        $success = $_GET['success'] ?? '';
        $error = $_GET['error'] ?? '';

        ob_start();
        include "views/author/index.php";
        $content = ob_get_clean();

        $page = 'author';
        include "views/layout/main.php";
    }

    public function save()
    {
        $action = $_POST['action'] ?? '';
        $data = [
            ':ten_tac_gia' => $_POST['ten_tac_gia'],
            ':ngay_sinh' => $_POST['ngay_sinh'],
            ':quoc_tich' => $_POST['quoc_tich'],
            ':tieu_su' => $_POST['tieu_su'],
        ];

        if ($action == 'add') {
            $ok = $this->model->addAuthor($data);
            $msg = $ok ? "Thêm tác giả thành công!" : "Lỗi khi thêm!";
        } elseif ($action == 'update') {
            $data[':ma_tac_gia'] = $_POST['ma_tac_gia'];
            $ok = $this->model->updateAuthor($data);
            $msg = $ok ? "Cập nhật thành công!" : "Cập nhật thất bại!";
        }

        header("Location: index.php?page=author&success=$msg");
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? 0;

        if ($id > 0) {
            $this->model->deleteAuthor($id);
            header("Location: index.php?page=author&success=Xóa thành công!");
        } else {
            header("Location: index.php?page=author&error=ID không hợp lệ!");
        }
        exit;
    }
}
