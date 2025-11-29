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
    // private BookDetailPageQuery $bookDetailPageQuery;
    private SuggestBookQuery $suggestBookQuery;

    public function __construct(
        BookService $bookService, 
        SuggestBookQuery $suggestBookQuery
    )
    {
        $this->bookService = $bookService;
        $this->suggestBookQuery = $suggestBookQuery;
    }

    public function handleRequest(string $action)
    {
        try {
            switch ($action) {
                case 'suggest_book':
                    $this->getSuggestBooks();
                    break;
                default:
                    throw new Exception("Action not found");
            }
        } catch (Exception $e) {
            $this->jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    // private function getPageDetail()
    // {
    //     $data = $this->bookDetailPageQuery->handle();
        
    //     if (!$data) {
    //         $this->jsonResponse(['status'=> 'error','message'=> 'Data not found'], 404);
    //         return;
    //     }
    //     $this->jsonResponse([
    //         'status' => 'success',
    //         'message' => 'Lấy dữ liệu trang chi tiết sách thành công',
    //         'data' => $data
    //     ], 200);
    // }

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
