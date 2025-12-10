<?php
require_once "models/Publisher.php";

class PublisherController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new Publisher($db);
    }

    public function index()
    {
        $page_current = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $per_page = 10;
        $offset = ($page_current - 1) * $per_page;

        $search_ten = $_GET['search_ten'] ?? '';
        $search_email = $_GET['search_email'] ?? '';

        $total = $this->model->getTotalPublishers($search_ten, $search_email);
        $total_pages_view = ceil($total / $per_page);

        $publishers = $this->model->getPublishers($per_page, $offset, $search_ten, $search_email);

        $success = $_GET['success'] ?? '';
        $error = $_GET['error'] ?? '';

        ob_start();
        include "views/publisher/index.php";
        $content = ob_get_clean();

        $page = 'publisher';
        include "views/layout/main.php";
    }

    public function save()
    {
        $action = $_POST['action'] ?? '';
        $data = [
            ':ten_nxb' => $_POST['ten_nxb'],
            ':dia_chi' => $_POST['dia_chi'],
            ':so_dien_thoai' => $_POST['so_dien_thoai'],
            ':email' => $_POST['email'],
        ];

        if ($action === 'add') {
            $ok = $this->model->addPublisher($data);
            $msg = $ok ? "Thêm NXB thành công!" : "Lỗi khi thêm!";
        } elseif ($action === 'update') {
            $data[':ma_nxb'] = $_POST['ma_nxb'];
            $ok = $this->model->updatePublisher($data);
            $msg = $ok ? "Cập nhật thành công!" : "Cập nhật thất bại!";
        }

        header("Location: index.php?page=publisher&success=$msg");
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $this->model->deletePublisher($id);
            header("Location: index.php?page=publisher&success=Xóa thành công!");
        } else {
            header("Location: index.php?page=publisher&error=ID không hợp lệ!");
        }
        exit;
    }
}
