<?php
include_once 'models/BookImage.php'; // Đảm bảo đúng tên file model (số ít/nhiều)

class BookImageController {
    private $model;

    public function __construct($conn) {
        $this->model = new BookImageModel($conn);
    }

    // ACTION: index (Mặc định) - Hiển thị danh sách và Xử lý Upload
public function index() {
        // 1. Lấy dữ liệu sách
        $books = $this->model->getAllBooks();
        $selectedBookId = isset($_GET['ma_sach']) ? $_GET['ma_sach'] : (isset($books[0]['ma_sach']) ? $books[0]['ma_sach'] : null);

        // --- MỚI: QUÉT THƯ MỤC ẢNH ---
        $baseDir = "../../assets/img-book/"; // Đường dẫn gốc chứa các folder ảnh
        $folders = [];
        // Kiểm tra đường dẫn có tồn tại không
        if (is_dir($baseDir)) {
            $scanned = scandir($baseDir);
            foreach ($scanned as $item) {
                // Chỉ lấy thư mục, bỏ qua dấu . và ..
                if ($item != '.' && $item != '..' && is_dir($baseDir . $item)) {
                    $folders[] = $item;
                }
            }
        }
        // -----------------------------

        // 2. Xử lý Upload (Nếu có POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_upload'])) {
            $ma_sach = $_POST['ma_sach'];
            
            // Lấy tên folder từ Dropdown người dùng chọn
            $folder_name = $_POST['folder_name']; 
            
            $la_anh_chinh = isset($_POST['la_anh_chinh']) ? 1 : 0;
            
            if (isset($_FILES['file_anh']) && $_FILES['file_anh']['error'] == 0) {
                // Đường dẫn lưu file vật lý: Base + Tên folder chọn + Tên file
                $targetDir = $baseDir . $folder_name . "/";
                
                // (Tùy chọn) Nếu lỡ folder chưa có thì tạo mới, nhưng ở đây ta đang chọn folder có sẵn
                if (!file_exists($targetDir)) { mkdir($targetDir, 0777, true); }

                $fileName = basename($_FILES["file_anh"]["name"]);
                $targetFile = $targetDir . $fileName;
                
                // Đường dẫn lưu vào DB: /ten-folder/ten-file.jpg
                $dbPath = "/" . $folder_name . "/" . $fileName;

                if (move_uploaded_file($_FILES["file_anh"]["tmp_name"], $targetFile)) {
                    $this->model->addImage($ma_sach, $dbPath, $la_anh_chinh);
                    header("Location: ?page=bookImage&ma_sach=$ma_sach");
                    exit;
                } else {
                    echo "<script>alert('Lỗi upload file!');</script>";
                }
            }
        }

        // 3. Lấy danh sách ảnh để hiển thị
        $currentImages = $selectedBookId ? $this->model->getImagesByBookId($selectedBookId) : [];
        
        // 4. Gọi View
        ob_start();
        include 'views/imgbook/index.php';
        $content = ob_get_clean();
        
        include 'views/layout/main.php'; 
    }

    // ACTION: delete - Xử lý xóa
    public function delete() {
        if (isset($_GET['id'])) {
            $pathToDelete = $this->model->deleteImage($_GET['id']);
            if ($pathToDelete) {
                // Xóa file vật lý
                $fullPath = "../frontend/assets/img-book" . $pathToDelete; 
                if (file_exists($fullPath)) unlink($fullPath);
            }
        }
        // Quay về trang index
        $ma_sach = isset($_GET['ma_sach']) ? $_GET['ma_sach'] : '';
        header("Location: ?page=bookImage&ma_sach=$ma_sach");
        exit;
    }

    // ACTION: set_main - Đặt ảnh chính
    public function set_main() {
        if (isset($_GET['id']) && isset($_GET['ma_sach'])) {
            $this->model->setMainImage($_GET['id'], $_GET['ma_sach']);
        }
        // Quay về trang index
        $ma_sach = isset($_GET['ma_sach']) ? $_GET['ma_sach'] : '';
        header("Location: ?page=bookImage&ma_sach=$ma_sach");
        exit;
    }
}
?>