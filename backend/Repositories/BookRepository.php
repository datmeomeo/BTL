<?php

namespace Repositories;

use Models\BookAggregate\IBookRepository;
use Models\BookAggregate\Book;
use Models\BookAggregate\BookImage;
use Models\BookAggregate\Status;
use PDO;
use PDOException;
use RuntimeException; // Sử dụng RuntimeException cho lỗi không mong muốn

class BookRepository implements IBookRepository
{
    private PDO $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function mapToBook(array $row, array $imagesData): Book
    {
        // Giả định BookImage đã được chuẩn hóa lại tên thuộc tính: url, isMainImage, order
        // Chuyển đổi mảng dữ liệu hình ảnh thành danh sách đối tượng BookImage
        $imageList = [];
        foreach ($imagesData as $img) {
            $imageList[] = new BookImage(
            $img['duong_dan_hinh'],                // Đường dẫn hình ảnh
            (bool)$img['la_anh_chinh'],// Có phải ảnh chính không
            $img['thu_tu']               // Thứ tự hiển thị
            );
        }
        
        // Tái tạo Book Aggregate Root
        return Book::reconstitute(
            $row['ma_sach'],
            $row['ten_sach'],
            $row['ma_nxb'],
            $row['so_trang'],
            $row['hinh_thuc_bia'],
            $row['ngon_ngu'],
            $row['nam_xuat_ban'],
            $row['ma_isbn'],
            new \DateTime($row['ngay_them']),
            $row['mo_ta'],
            (float)$row['gia_ban'],
            (float)$row['gia_goc'],
            (int)$row['so_luong_ton'],
            (int)$row['luot_xem'],
            Status::from($row['trang_thai']),
            $imageList
        );
    }
    
    // --- IMPLEMENTATION CỦA IBOOKREPOSITORY ---

    public function findById(string $id): ?Book
    {
        // Truy vấn sách
        $stmt = $this->db->prepare("SELECT * FROM sach WHERE ma_sach = :id");
        $stmt->execute(['id' => $id]);
        $bookRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$bookRow) {
            return null;
        }

        // Lấy dữ liệu hình ảnh (Entity/VO bên trong Aggregate)
        $stmtImages = $this->db->prepare("SELECT duong_dan_hinh, la_anh_chinh, thu_tu FROM hinh_anh_sach WHERE ma_sach = :id ORDER BY thu_tu");
        $stmtImages->execute(['id' => $id]);
        $imagesData = $stmtImages->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->mapToBook($bookRow, $imagesData);
    }

    /**
     * Thêm mới một Aggregate Root Book vào cơ sở dữ liệu.
     */
    public function add(Book $book): void
    {
        if ($book->getId() !== null) {
            throw new \LogicException("Không thể thêm sách mới khi ID đã tồn tại.");
        }

        $this->db->beginTransaction();

        try {
            // 1. INSERT Sách
            $sql = "INSERT INTO sach (ten_sach, ma_nxb, so_trang, hinh_thuc_bia, ngon_ngu, nam_xuat_ban, ma_isbn, ngay_them, mo_ta, gia_ban, gia_goc, so_luong_ton, luot_xem, trang_thai)
                    VALUES (:name, :publisherId, :pages, :coverType, :lang, :pubYear, :isbn, :addedDate, :desc, :sellPrice, :origPrice, :stock, :views, :status)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $book->getName(),
                ':publisherId' => $book->getPublisherId(),
                ':pages' => $book->getNumberOfPages(),
                ':coverType' => $book->getCoverType(),
                ':lang' => $book->getLanguage(),
                ':pubYear' => $book->getPublicationYear(),
                ':isbn' => $book->getIsbnCode(),
                ':addedDate' => $book->getAddedDate()->format('Y-m-d H:i:s'),
                ':desc' => $book->getDescription(),
                ':sellPrice' => $book->getSellingPrice(),
                ':origPrice' => $book->getOriginalPrice(),
                ':stock' => $book->getStockQuantity(),
                ':views' => $book->getViewCount(),
                ':status' => $book->getStatus()->value,
            ]);

            // Lấy ID mới và gán lại vào Aggregate Root
            $bookId = $this->db->lastInsertId();
            $book->SetId($bookId); 
            
            // 2. INSERT BookImage
            $this->insertImages($bookId, $book->GetImages());

            $this->db->commit();
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $book->SetId(null);
            throw new RuntimeException("Lỗi DB khi thêm sách mới.", 0, $e); 
        } catch (\Exception $e) {
            $this->db->rollBack();
            $book->SetId(null);
            throw $e; 
        }
    }

    public function update(Book $book): void
    {
        if ($book->getId() === null) {
            throw new \LogicException("Không thể cập nhật sách khi ID chưa được gán.");
        }
        
        $bookId = $book->getId();
        $this->db->beginTransaction();

        try {
            // 1. UPDATE Sách
            $sql = "UPDATE sach SET ten_sach = :name, ma_nxb = :publisherId, so_trang = :pages, hinh_thuc_bia = :coverType, ngon_ngu = :lang, nam_xuat_ban = :pubYear, ma_isbn = :isbn,
                    mo_ta = :desc, gia_ban = :sellPrice, gia_goc = :origPrice, so_luong_ton = :stock, luot_xem = :views, trang_thai = :status
                    WHERE ma_sach = :id";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $bookId,
                ':name' => $book->getName(),
                ':publisherId' => $book->getPublisherId(),
                ':pages' => $book->getNumberOfPages(),
                ':coverType' => $book->getCoverType(),
                ':lang' => $book->getLanguage(),
                ':pubYear' => $book->getPublicationYear(),
                ':isbn' => $book->getIsbnCode(),
                ':desc' => $book->getDescription(),
                ':sellPrice' => $book->getSellingPrice(),
                ':origPrice' => $book->getOriginalPrice(),
                ':stock' => $book->getStockQuantity(),
                ':views' => $book->getViewCount(),
                ':status' => $book->getStatus()->value,
            ]);
            
            // 2. Lưu trữ BookImage (Xóa và Tái tạo)
            
            // Xóa tất cả hình ảnh cũ
            $stmtDeleteImages = $this->db->prepare("DELETE FROM hinh_anh_sach WHERE ma_sach = :id");
            $stmtDeleteImages->execute(['id' => $bookId]);
            
            // Thêm lại các hình ảnh mới
            $this->insertImages($bookId, $book->GetImages());

            $this->db->commit();
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new RuntimeException("Lỗi DB khi cập nhật sách.", 0, $e); 
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e; 
        }
    }

    public function delete(int $id): void
    {
        $this->db->beginTransaction();

        try {
            // Xóa hình ảnh liên quan
            $stmtDeleteImages = $this->db->prepare("DELETE FROM hinh_anh_sach WHERE ma_sach = :id");
            $stmtDeleteImages->execute(['id' => $id]);

            // Xóa sách
            $stmtDeleteBook = $this->db->prepare("DELETE FROM sach WHERE ma_sach = :id");
            $stmtDeleteBook->execute(['id' => $id]);

            $this->db->commit();
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new RuntimeException("Lỗi DB khi xóa sách.", 0, $e); 
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e; 
        }
    }

    public function exists(string $id): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM sach WHERE ma_sach = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return (bool) $stmt->fetchColumn();
    }
    
    // --- PHƯƠNG THỨC HỖ TRỢ ---
    
    /**
     * Hàm hỗ trợ INSERT nhiều BookImage.
     * @param string $bookId ID sách.
     * @param BookImage[] $images Danh sách hình ảnh.
     */
    private function insertImages(string $bookId, array $images): void
    {
        if (empty($images)) {
            return;
        }
        
        $sqlInsertImage = "INSERT INTO hinh_anh_sach (ma_sach, duong_dan_hinh, la_anh_chinh, thu_tu) VALUES (:book_id, :url, :is_main, :order)";
        $stmtInsertImage = $this->db->prepare($sqlInsertImage);
        
        /** @var BookImage $image */
        foreach ($images as $image) {
            $stmtInsertImage->execute([
                ':book_id' => $bookId,
                ':url' => $image->getUrl(),
                ':is_main' => $image->isMainImage(),
                ':order' => $image->getOrder(),
            ]);
        }
    }
}