<?php
namespace Models\BookAggregate;

use InvalidArgumentException;
use Core\ValueObject;
// Đây là Value Object đại diện cho hình ảnh sách
class BookImage extends ValueObject {
    // Thuộc tính chỉ được gán trong constructor (readonly)
    private string $url;
    private bool $isMainImage;
    private int $order;

    public function __construct(string $url, bool $isMainImage, int $order){
        if (empty($url)) {
            throw new InvalidArgumentException("Đường dẫn hình ảnh không được để trống.");
        }
        if ($order < 1) {
            throw new InvalidArgumentException("Thứ tự hiển thị phải lớn hơn 0.");
        }
        
        $this->url = $url;
        $this->isMainImage = $isMainImage;
        $this->order = $order;
    }
    
    
    public function getUrl(): string
    {
        return $this->url;
    }
    
    public function isMainImage(): bool
    {
        return $this->isMainImage;
    }
    
    public function getOrder(): int
    {
        return $this->order;
    }

    protected function getEqualityComponents(): array
    {
        return [
            $this->url,
            $this->isMainImage,
            $this->order
        ];
    }
}
?>