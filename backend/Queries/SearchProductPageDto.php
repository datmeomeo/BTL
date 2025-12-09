<?php
namespace Queries;


class SearchProductPageDto{

    public function __construct(
        // 1. Thông tin cơ bản của cuốn sách 
        public readonly int $bookId,
        public readonly string $bookName,
        public readonly string $description,
        public readonly ?string $isbn, 
        // 2. Giá bán & Khuyến mãi (Pricing)
        public readonly float $sellingPrice,
        public readonly float $originalPrice,
        public readonly int $discountPercent,
        // 3. Phân loại & Taxonomy (Category)
        public readonly ?string $categoryName,
        public readonly ?string $parentCategoryName,
        // 4. Tên tác giả và nhà xuất bán 
        public readonly string $authorName,        // Tác giả
        public readonly string $publisherName,     // Nhà xuất bản 
        // 5. ảnh chính
        public readonly string $mainImage,
    ){
    }
    public function toArray(): array
    {
        return [
            'bookId' => $this->bookId,
            'bookName' => $this->bookName,
            'description' => $this->description,
            'isbn' => $this->isbn,

            'sellingPrice' => $this->sellingPrice,
            'originalPrice' => $this->originalPrice,
            'discountPercent' => $this->discountPercent,

            'authorName' => $this->authorName,
            'publisherName' => $this->publisherName,

            'categoryName' => $this->categoryName,
            'parentCategoryName' => $this->parentCategoryName,

            'mainImage' => $this->mainImage,
        ];
    }
    
}
