<?php

namespace Models\BookAggregate; 
/**
 * Interface cho Book Repository (IBookRepository)
 * Định nghĩa hợp đồng cho việc lưu trữ và truy xuất Book Aggregate.
 * Phải nằm trong Domain Layer.
 */
interface IBookRepository
{
    public function findById(string $id): ?Book;

    public function add(Book $book): void;

    public function update(Book $book): void;

    public function delete(int $id): void;
    public function exists(string $id): bool;
}