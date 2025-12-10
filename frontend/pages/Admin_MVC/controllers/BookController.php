<?php
require_once "models/Book.php";

class BookController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new Book($db);
    }

    public function index()
{
    // --- PHÂN TRANG ---
    $page_current = max(1, (int)($_GET['p'] ?? 1));
    $per_page     = 10;
    $offset       = ($page_current - 1) * $per_page;

    // --- LẤY THAM SỐ TÌM KIẾM ---
    $search_ten     = trim($_GET['search_ten'] ?? '');
    $search_tacgia  = trim($_GET['search_tacgia'] ?? '');  // ma_tac_gia (dạng số)
    $search_nxb     = trim($_GET['search_nxb'] ?? '');     // ma_nxb (dạng số)

    // --- LẤY DỮ LIỆU ---
    $total            = $this->model->getTotal($search_ten, $search_tacgia, $search_nxb);
    $total_pages_view = ceil($total / $per_page);

    $books = $this->model->getBooks(
        $per_page,
        $offset,
        $search_ten,
        $search_tacgia,
        $search_nxb
    );

    // --- DỮ LIỆU CHO FORM THÊM/SỬA ---
    $publishers = $this->model->getPublishers();
    $authors    = $this->model->getAuthors();
    $categories = $this->model->getCategories();

    // --- BỔ SUNG THÔNG TIN ĐỂ CLICK DÒNG HIỂN ĐÚNG TÁC GIẢ & DANH MỤC ---
    foreach ($books as &$book) {
        $book['ma_tac_gia_chinh'] = $this->model->getMainAuthorId($book['ma_sach']);
        $book['danh_muc_list']     = $this->model->getCategoriesByBook($book['ma_sach']);
    }
    unset($book);

    // --- THÔNG BÁO ---
    $success = $_GET['success'] ?? '';
    $error   = $_GET['error'] ?? '';

    // --- URL PHÂN TRANG GIỮ LẠI TÌM KIẾM ---
    $base_url = '?page=book';
    $search_params = '';
    if ($search_ten)     $search_params .= '&search_ten=' . urlencode($search_ten);
    if ($search_tacgia)  $search_params .= '&search_tacgia=' . urlencode($search_tacgia);
    if ($search_nxb)     $search_params .= '&search_nxb=' . urlencode($search_nxb);

    // --- GỌI VIEW ---
    ob_start();
    include "views/book/index.php";
    $content = ob_get_clean();

    include "views/layout/main.php";
}

    public function save()
    {
        $action = $_POST['action'] ?? '';

        $data = [
            ':ten_sach'       => trim($_POST['ten_sach'] ?? ''),
            ':ma_nxb'         => $_POST['ma_nxb'] ?: null,
            ':mo_ta'          => $_POST['mo_ta'] ?? '',
            ':gia_ban'        => (float)($_POST['gia_ban'] ?? 0),
            ':gia_goc'        => $_POST['gia_goc'] ?: null,
            ':so_luong_ton'   => (int)($_POST['so_luong_ton'] ?? 0),
            ':so_trang'       => $_POST['so_trang'] ?: null,
            ':hinh_thuc_bia'  => $_POST['hinh_thuc_bia'] ?? '',
            ':ngon_ngu'       => $_POST['ngon_ngu'] ?? 'Tiếng Việt',
            ':nam_xuat_ban'   => $_POST['nam_xuat_ban'] ?: null,
            ':ma_isbn'        => $_POST['ma_isbn'] ?? '',
        ];

        if ($data[':ten_sach'] === '' || $data[':gia_ban'] <= 0) {
            header("Location: ?page=book&error=Tên sách và giá bán là bắt buộc!");
            exit;
        }

        $this->model->conn->beginTransaction();

        try {
            if ($action === 'add') {
                $ma_sach = $this->model->add($data);
                $msg = "Thêm sách thành công!";
            } elseif ($action === 'update') {
                $data[':ma_sach'] = (int)$_POST['ma_sach'];
                $this->model->update($data);
                $ma_sach = $data[':ma_sach'];
                $msg = "Cập nhật thành công!";
            }

            // Xử lý tác giả (xóa cũ, thêm mới)
            $this->model->conn->exec("DELETE FROM sach_tac_gia WHERE ma_sach = $ma_sach");
            if (!empty($_POST['ma_tac_gia'])) {
                $this->model->conn->prepare("INSERT INTO sach_tac_gia(ma_sach, ma_tac_gia) VALUES(?,?)")
                    ->execute([$ma_sach, $_POST['ma_tac_gia']]);
            }

            // Xử lý danh mục
            $this->model->conn->exec("DELETE FROM sach_danh_muc WHERE ma_sach = $ma_sach");
            if (!empty($_POST['danh_muc']) && is_array($_POST['danh_muc'])) {
                $stmt = $this->model->conn->prepare("INSERT INTO sach_danh_muc(ma_sach, ma_danh_muc) VALUES(?,?)");
                foreach ($_POST['danh_muc'] as $dm) {
                    $stmt->execute([$ma_sach, $dm]);
                }
            }

            $this->model->conn->commit();
        } catch (Exception $e) {
            $this->model->conn->rollBack();
            header("Location: ?page=book&error=" . urlencode("Lỗi: " . $e->getMessage()));
            exit;
        }

        $redirect = "?page=book";
        if (!empty($_GET['search_ten']))     $redirect .= "&search_ten=" . urlencode($_GET['search_ten']);
        if (!empty($_GET['search_tacgia']))  $redirect .= "&search_tacgia=" . urlencode($_GET['search_tacgia']);

        header("Location: $redirect&success=" . urlencode($msg));
        exit;
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        $success = $this->model->delete($id);

        $redirect = "?page=book";
        if (!empty($_GET['search_ten']))     $redirect .= '&search_ten=' . urlencode($_GET['search_ten']);
        if (!empty($_GET['search_tacgia'])) $redirect .= '&search_tacgia=' . urlencode($_GET['search_tacgia']);

        header("Location: $redirect&" . ($success ? 'success=Xóa thành công!' : 'error=Xóa thất bại!'));
        exit;
    }
}
