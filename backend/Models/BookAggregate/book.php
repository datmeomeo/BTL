<?php
namespace Models\BookAggregate;

use DateTime;
use DomainException;
use InvalidArgumentException;

class Book
{
    // ID có thể là null khi đối tượng được tạo lần đầu (trước khi lưu vào DB tự tăng)
    private ?string $id = null; 
    
    // Các thuộc tính được đóng gói (private)
    private string $name;
    private int $publisherId; // ID của nhà xuất bản (khóa ngoại), phần này có thể mở rộng thành một Value Object.
    private int $numberOfPages;
    private string $coverType;
    private string $language;
    private int $publicationYear;
    private string $isbnCode;
    private DateTime $addedDate; 
    private string $description = "";
    private float $sellingPrice = 0;
    private float $originalPrice = 0;
    private int $stockQuantity = 0;
    private int $viewCount = 0;
    
    private Status $status;

    /** @var BookImage[] */
    private array $imageList = []; 

    // --- Phương thức Khởi tạo (Constructor) ---

    // Constructor nhận các tham số bắt buộc và có thể nhận ID nếu tái tạo từ Repository
    private function __construct(
        string $name,
        int $publisherId,
        int $numberOfPages,
        string $coverType,
        string $language,
        int $publicationYear,
        string $isbnCode
    ) {
        
        $this->name = $name;
        $this->publisherId = $publisherId;
        $this->numberOfPages = $numberOfPages;
        $this->coverType = $coverType;
        $this->language = $language;
        $this->publicationYear = $publicationYear;
        $this->isbnCode = $isbnCode;
        
        $this->addedDate = new DateTime();
        $this->status = Status::Available; 
    }

    /** 
     * Phương thức tái tạo đối tượng từ dữ liệu đã có (ví dụ từ DB)
     */
    public static function reconstitute( 
        ?string $id,
        string $name,
        int $publisherId,
        int $numberOfPages,
        string $coverType,
        string $language,
        int $publicationYear,
        string $isbnCode,
        DateTime $addedDate,
        string $description,
        float $sellingPrice,
        float $originalPrice,
        int $stockQuantity,
        int $viewCount,
        Status $status,
        array $imageList
    ): Book {
        $book = new Book(
            $name,
            $publisherId,
            $numberOfPages,
            $coverType,
            $language,
            $publicationYear,
            $isbnCode
        );
        $book->id = $id;
        $book->addedDate = $addedDate;
        $book->description = $description;
        $book->sellingPrice = $sellingPrice;
        $book->originalPrice = $originalPrice;
        $book->stockQuantity = $stockQuantity;
        $book->viewCount = $viewCount;
        $book->status = $status;
        $book->imageList = $imageList;
        return $book;
    }

    /**
     * Phương thức tạo mới một cuốn sách với các thuộc tính bắt buộc và tùy chọn.
     */
    public static function CreateBook(
        string $name,
        int $publisherId,
        int $numberOfPages,
        string $coverType,
        string $language,
        int $publicationYear,
        string $isbnCode,
        float $sellingPrice,
        float $originalPrice,
        int $stockQuantity
    ): Book {
        $book = new Book(
            $name,
            $publisherId,
            $numberOfPages,
            $coverType,
            $language,
            $publicationYear,
            $isbnCode
        );
        $book->UpdatePrice($sellingPrice, $originalPrice);
        $book->UpdateStock($stockQuantity);
        return $book;
    }
    
    /**
     * Thêm một hình ảnh vào danh sách.
     * @param BookImage $image Đối tượng hình ảnh.
     */
    public function AddImage(BookImage $image): void
    {
        $this->imageList[] = $image;
    }

    /**
     * Cập nhật giá bán và giá gốc của sách.
     * @param float $newSellingPrice Giá bán mới.
     * @param float $newOriginalPrice Giá gốc mới.
     */
    public function UpdatePrice(float $newSellingPrice, float $newOriginalPrice): void
    {
        if ($newSellingPrice <= 0 || $newOriginalPrice <= 0) {
            throw new InvalidArgumentException("Giá bán và giá gốc phải lớn hơn 0.");
        }
        $this->sellingPrice = $newSellingPrice;
        $this->originalPrice = $newOriginalPrice;
    }
    
    
    /**
     * Cập nhật số lượng tồn kho và trạng thái sách.
     * @param int $quantityChange Số lượng thay đổi (dương cho nhập, âm cho xuất).
     */
    public function UpdateStock(int $quantityChange): void
    {
        $newStock = $this->stockQuantity + $quantityChange;
        if ($newStock < 0) {
            throw new DomainException("Số lượng tồn kho không thể âm.");
        }
        $this->stockQuantity = $newStock;
        // Cập nhật trạng thái tự động
        if ($this->stockQuantity === 0) {
            $this->status = Status::OutOfStock;
        } elseif ($this->status === Status::OutOfStock && $this->stockQuantity > 0) {
            $this->status = Status::Available;
        }
    }
    /**
     * Tăng lượt xem của sách.
     */
    public function IncrementViews(): void
    {
        $this->viewCount++;
    }

    /**
     * Trả về danh sách hình ảnh (bản sao mảng).
     * @return BookImage[]
     */
    public function GetImages(): array
    {
        // Trả về bản sao của mảng để bảo vệ sự đóng gói của Aggregate Root
        return $this->imageList;
    }
    
}
?>