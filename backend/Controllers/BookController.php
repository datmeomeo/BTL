<?php
namespace Controllers;

use Core\BaseController;
use Services\BookService;
use Queries\BookDetailPageQuery;
use Queries\SuggestBookQuery;
use Queries\SearchProductPageQuery;

use Exception;
    
class BookController extends BaseController
{
    private BookService $bookService;
    private BookDetailPageQuery $bookDetailPageQuery;
    private SuggestBookQuery $suggestBookQuery;
    private SearchProductPageQuery $searchProductQuery;

    public function __construct(    
        BookService $bookService, 
        BookDetailPageQuery $bookDetailPageQuery,
        SuggestBookQuery $suggestBookQuery,
        SearchProductPageQuery $searchProductQuery
    )
    {
        $this->bookService = $bookService;
        $this->bookDetailPageQuery = $bookDetailPageQuery;
        $this->suggestBookQuery = $suggestBookQuery;
        $this->searchProductQuery = $searchProductQuery;
    }

    public function handleRequest(string $action)
    {
        try {
            switch ($action) {
                case "increase_view":
                    $this->increaseViewCount();
                    break;
                case 'page_detail':
                    $this->getPageDetail();
                    break;
                case 'suggest_book':
                    $this->getSuggestBooks();
                    break;
<<<<<<< HEAD
                case 'search':
                    $this->getSearch();
                    break;
=======
                case 'list':           // Lấy danh sách sách (có filter)
                    $this->getListBooks();
                    break;
                case 'categories':     // Lấy danh sách danh mục
                    $this->getCategories();
                    break;
                case 'authors':        // Lấy danh sách tác giả
                    $this->getAuthors();
                    break;
                case 'book_types':     // Lấy loại sách (bìa cứng/mềm...)
                     $this->getBookTypes();
                     break;
>>>>>>> 6a024c67c3d9ac6366e3fcb74327a42f32e38cf5
                default:
                    throw new Exception("Action not found");
            }
        } catch (Exception $e) {
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    private function increaseViewCount()
    {
        $bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $this->bookService->incrementViews($bookId);
        $this->jsonResponse(['status' => 'success', 'message' => 'View count increased'], 200);
    }

    private function getPageDetail()
    {
        $bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $data = $this->bookDetailPageQuery->handle($bookId);
        
        if (!$data) {
            $this->jsonResponse(['status'=> 'error','message'=> 'Data not found'], 404);
            return;
        }
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Lấy dữ liệu trang chi tiết sách thành công',
            'data' => $data
        ]);
    }

    private function getSuggestBooks()
    {
        $data = $this->suggestBookQuery->handle();
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Lấy dữ liệu sách gợi ý thành công',
            'data' => $data
        ]);
    }

<<<<<<< HEAD
=======
// Trong hàm getListBooks()
    private function getListBooks()
    {
        $params = $_GET;
        $result = $this->searchProductQuery->search($params);

        // Map mảng DTO sang mảng thường
        $booksArray = array_map(fn($dto) => $dto->toArray(), $result['books']);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Lấy danh sách thành công',
            'data' => [
                'books' => $booksArray,
                'pagination' => [
                    'total' => $result['total'],
                    'page' => $result['page'],
                    'limit' => $result['limit'],
                    'total_pages' => ceil($result['total'] / $result['limit'])
                ]
            ]
        ]);
    }

    private function getCategories()
    {
        $data = $this->searchProductQuery->getAllCategories();
        $this->jsonResponse([
            'status' => 'success',
            'data' => $data
        ]);
    }

    private function getAuthors()
    {
        $data = $this->searchProductQuery->getAllAuthors();
        $this->jsonResponse([
            'status' => 'success',
            'data' => $data
        ]);
    }

    private function getBookTypes() 
    {
        // Tạm thời lấy các loại bìa từ CSDL hoặc trả về cứng (vì chưa có bảng riêng cho Type)
        // Dựa trên cột `hinh_thuc_bia` trong bảng `sach`
        // Nếu bạn muốn truy vấn DB thật: Viết thêm hàm trong Query class
        
        $data = [
            ['id' => 'Bìa Mềm', 'name' => 'Bìa Mềm'],
            ['id' => 'Bìa Cứng', 'name' => 'Bìa Cứng'],
            ['id' => 'Boxset', 'name' => 'Boxset']
        ];

        $this->jsonResponse([
            'status' => 'success',
            'data' => $data
        ]);
    }

>>>>>>> 6a024c67c3d9ac6366e3fcb74327a42f32e38cf5
}
