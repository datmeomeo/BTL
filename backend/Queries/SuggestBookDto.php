<?php
namespace Queries;
use DateTime;

class SuggestBookDto
{
    public function __construct(
        // Sử dụng readonly để đảm bảo các giá trị không thể thay đổi sau khi khởi tạo
        public readonly int $bookId,
        public readonly string $bookName,
        public readonly string $publisherName,
        public readonly float $sellingPrice,
        public readonly float $originalPrice,
        public readonly int $discountPercent,
        public readonly DateTime $addedDate, 
        public readonly string $imagePath
    ) {
    }
    
    public function toArray(): array
    {
        return [
            'bookId' => $this->bookId,
            'bookName' => $this->bookName,
            'publisherName' => $this->publisherName,
            'sellingPrice' => $this->sellingPrice,
            'originalPrice' => $this->originalPrice,
            'discountPercent' => $this->discountPercent,
            'addedDate' => $this->addedDate->format('Y-m-d H:i:s'),
            'imagePath' => $this->imagePath,
        ];
    }
}