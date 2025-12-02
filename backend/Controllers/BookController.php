<?php
namespace Controllers;

use Core\BaseController;
use Services\BookService;
use Queries\BookDetailPageQuery;
use Queries\SuggestBookQuery;

use Exception;
    
class BookController extends BaseController
{
    private BookService $bookService;
    private BookDetailPageQuery $bookDetailPageQuery;
    private SuggestBookQuery $suggestBookQuery;

    public function __construct(
        BookService $bookService, 
        BookDetailPageQuery $bookDetailPageQuery,
        SuggestBookQuery $suggestBookQuery
    )
    {
        $this->bookService = $bookService;
        $this->bookDetailPageQuery = $bookDetailPageQuery;
        $this->suggestBookQuery = $suggestBookQuery;
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
                case 'search':
                    $this->getSearch();
                    break;
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

}
