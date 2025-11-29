<?php
namespace Queries;

/**
 * BookDetailPageDto
 * DTO chứa toàn bộ thông tin cho trang chi tiết sách
 */
class BookDetailPageDto
{
    public function __construct(
        // 1. Thông tin cơ bản (Basic Info)
        public readonly int $bookId,
        public readonly string $bookName,
        public readonly string $description,
        public readonly ?string $isbn, // Mã hàng (ISBN)

        // 2. Giá bán & Khuyến mãi (Pricing)
        public readonly float $sellingPrice,
        public readonly float $originalPrice,
        public readonly int $discountPercent,

        // 3. Phân loại & Taxonomy (Category)
        public readonly ?string $categoryName,
        public readonly ?string $parentCategoryName,

        // 4. Thuộc tính sách (Book Attributes)
        public readonly string $authorName,       // Tác giả
        public readonly string $publisherName,    // Nhà xuất bản
        public readonly string $supplierName,     // Nhà cung cấp (View đang dùng chung với NXB)
        public readonly ?string $translatorName,  // Người dịch
        public readonly string $language,         // Ngôn ngữ
        public readonly ?int $publishYear,        // Năm xuất bản
        public readonly string $coverForm,        // Hình thức bìa (Cứng/Mềm)

        // 5. Thông số vật lý (Physical Specs)
        public readonly ?int $weight,             // Trọng lượng (gr)
        public readonly ?string $dimensions,      // Kích thước (cm)
        public readonly ?int $pageCount,          // Số trang

        // 6. Kho & Thống kê (Inventory & Stats)
        public readonly int $stockQuantity,       // Số lượng tồn
        public readonly float $averageRating,     // Điểm đánh giá TB
        public readonly int $reviewCount,         // Số lượng đánh giá

        // 7. Hình ảnh (Images)
        public readonly string $mainImage,        // Ảnh chính
        public readonly array $thumbnails,        // Danh sách ảnh phụ (array of strings)

        // 8. Danh sách đánh giá (Reviews)
        public readonly array $reviews            // Mảng chứa các object/array đánh giá chi tiết
    ) {
    }

    public function toArray(): array
    {
        return [
            // Basic
            'bookId' => $this->bookId,
            'bookName' => $this->bookName,
            'description' => $this->description,
            'isbn' => $this->isbn,
            
            // Pricing
            'sellingPrice' => $this->sellingPrice,
            'originalPrice' => $this->originalPrice,
            'discountPercent' => $this->discountPercent,
            
            // Category
            'categoryName' => $this->categoryName,
            'parentCategoryName' => $this->parentCategoryName,
            
            // Attributes
            'authorName' => $this->authorName,
            'publisherName' => $this->publisherName,
            'supplierName' => $this->supplierName,
            'translatorName' => $this->translatorName,
            'language' => $this->language,
            'publishYear' => $this->publishYear,
            'coverForm' => $this->coverForm,
            
            // Specs
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'pageCount' => $this->pageCount,
            
            // Inventory & Stats
            'stockQuantity' => $this->stockQuantity,
            'averageRating' => $this->averageRating,
            'reviewCount' => $this->reviewCount,
            
            // Images
            'mainImage' => $this->mainImage,
            'thumbnails' => $this->thumbnails,
            
            // Reviews
            'reviews' => $this->reviews,
        ];
    }
}