<?php
namespace Services;

use Models\BookAggregate\IBookRepository;

class BookService
{
    private IBookRepository $BookRepository;
    public function __construct(IBookRepository $BookRepository)
    {
        $this->BookRepository = $BookRepository;
    }

    public function incrementViews(int $id)
    {
        $book = $this->BookRepository->findById($id);
        $book->incrementViews();
        $this->BookRepository->update($book);
    }

    public function createNewBook(        
        string $name,
        int $publisherId,
        int $numberOfPages,
        string $coverType,
        string $language,
        int $publicationYear,
        string $isbnCode,
        float $sellingPrice,
        float $originalPrice,
        int $stockQuantity): void 
    {
        //kiểm tra trong csdl book đã tồn tại hay chưa
        //existBook = this-> repo -> isByName($name)
        //if (existBook == true) toast("Loi") return thoat khoi ham 
        // book = Book::createBook(......)
        // repo -> add(book)
        // 
    }

}
