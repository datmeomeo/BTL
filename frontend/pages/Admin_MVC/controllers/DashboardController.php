<?php
require_once __DIR__ . '/../them/connect.php';


class DashboardController {

    public function index() {
        global $conn;

        $data = [
            'totalBooks'      => $conn->query("SELECT COUNT(*) FROM sach")->fetchColumn(),
            'totalUsers'      => $conn->query("SELECT COUNT(*) FROM nguoi_dung")->fetchColumn(),
            'totalCategories' => $conn->query("SELECT COUNT(*) FROM danh_muc")->fetchColumn(),
            'totalPublishers' => $conn->query("SELECT COUNT(*) FROM nha_xuat_ban")->fetchColumn(),
            'totalAuthors'    => $conn->query("SELECT COUNT(*) FROM tac_gia")->fetchColumn()
        ];

        // gọi view
        $this->render('dashboard', $data);
    }

    private function render($view, $data = []) {
        extract($data);
        ob_start();
        require "views/{$view}.php";
        $content = ob_get_clean();
        require "views/layout/main.php";
    }
}
