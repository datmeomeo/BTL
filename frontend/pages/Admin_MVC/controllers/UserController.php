<?php
require_once "models/User.php";

class UserController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new User($db);
    }

    public function index()
    {
        $page_current = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $per_page = 10;
        $offset = ($page_current - 1) * $per_page;

        $search_ten = $_GET['search_ten'] ?? '';
        $search_vaitro = $_GET['search_vaitro'] ?? '';
        $search_trangthai = $_GET['search_trangthai'] ?? '';

        $total = $this->model->getTotalUsers($search_ten, $search_vaitro, $search_trangthai);
        $total_pages_view = ceil($total / $per_page);

        $users = $this->model->getUsers($per_page, $offset, $search_ten, $search_vaitro, $search_trangthai);

        $success = $_GET['success'] ?? '';
        $error   = $_GET['error'] ?? '';

        ob_start();
        include "views/user/index.php";
        $content = ob_get_clean();

        $page = 'user';
        include "views/layout/main.php";
    }

    public function save()
    {
        $action = $_POST['action'] ?? '';
        if (!in_array($action, ['add', 'update'])) {
            header("Location: ?page=user&error=Hành động không hợp lệ");
            exit;
        }

        $data = [
            'ten_dang_nhap' => trim($_POST['ten_dang_nhap'] ?? ''),
            'email'         => trim($_POST['email'] ?? ''),
            'ho_ten'        => trim($_POST['ho_ten'] ?? ''),
            'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
            'vai_tro'       => $_POST['vai_tro'] ?? 'customer',
            'trang_thai'    => $_POST['trang_thai'] ?? 'active',
        ];

        if ($action === 'update') {
            $data['ma_nguoi_dung'] = (int)($_POST['ma_nguoi_dung'] ?? 0);
            if ($data['ma_nguoi_dung'] <= 0) {
                header("Location: ?page=user&error=ID không hợp lệ");
                exit;
            }
        }

        // Validate bắt buộc
        if (empty($data['ten_dang_nhap']) || empty($data['email'])) {
            header("Location: ?page=user&error=Tên đăng nhập và email là bắt buộc!");
            exit;
        }

        try {
            if ($action === 'add') {
                $this->model->addUser($data);
                $msg = "Thêm thành công! Mật khẩu mặc định: 123456";
            } else {
                $this->model->updateUser($data);
                $msg = "Cập nhật thành công!";
            }
            header("Location: ?page=user&success=" . urlencode($msg));
        } catch (Exception $e) {
            header("Location: ?page=user&error=" . urlencode($e->getMessage()));
        }
        exit;
    }
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 1) {
            header("Location: ?page=user&error=Không thể xóa admin gốc!");
            exit;
        }

        try {
            $this->model->deleteUser($id);
            header("Location: ?page=user&success=Xóa thành công!");
        } catch (Exception $e) {
            header("Location: ?page=user&error=" . urlencode($e->getMessage()));
        }
        exit;
    }

    public function logout() {
        session_unset();
        session_destroy();
        echo "<script>
            window.location.href = '../../index.php?page=login';
        </script>";
        exit();
    }
}
