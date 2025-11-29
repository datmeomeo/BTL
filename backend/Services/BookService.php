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

    public function IncrementViews(int $id)
    {
        $book = $this->BookRepository->findById($id);
        $book->incrementViews();
        $this->BookRepository->update($book);
    }

    public function GetBookDetails(int $id): ?array
    {

    }
}
