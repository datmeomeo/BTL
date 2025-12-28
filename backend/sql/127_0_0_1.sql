-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2025 at 11:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `csdl_bansach`
--

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_gio_hang`
--

CREATE TABLE `chi_tiet_gio_hang` (
  `ma_chi_tiet` int(11) NOT NULL,
  `ma_gio_hang` int(11) DEFAULT NULL,
  `ma_sach` int(11) DEFAULT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 1,
  `gia_tai_thoi_diem` decimal(10,2) NOT NULL,
  `ngay_them` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chi_tiet_gio_hang`
--

INSERT INTO `chi_tiet_gio_hang` (`ma_chi_tiet`, `ma_gio_hang`, `ma_sach`, `so_luong`, `gia_tai_thoi_diem`, `ngay_them`) VALUES
(4, 2, 5, 3, 25000.00, '2025-12-06 15:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `ma_danh_gia` int(11) NOT NULL,
  `ma_sach` int(11) DEFAULT NULL,
  `ma_nguoi_dung` int(11) DEFAULT NULL,
  `diem_danh_gia` int(11) DEFAULT NULL CHECK (`diem_danh_gia` between 1 and 5),
  `noi_dung` text DEFAULT NULL,
  `ngay_danh_gia` datetime DEFAULT current_timestamp(),
  `trang_thai` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` int(11) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `danh_muc_cha` int(11) DEFAULT NULL,
  `cap_do` int(11) DEFAULT 1,
  `thu_tu` int(11) DEFAULT 0,
  `hien_thi_menu` tinyint(1) DEFAULT 1,
  `icon` varchar(50) DEFAULT NULL,
  `mau_sac` varchar(20) DEFAULT NULL,
  `la_danh_muc_noi_bat` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `slug`, `mo_ta`, `danh_muc_cha`, `cap_do`, `thu_tu`, `hien_thi_menu`, `icon`, `mau_sac`, `la_danh_muc_noi_bat`) VALUES
(1, 'Sách Trong Nước', 'sach-trong-nuoc', NULL, NULL, 1, 1, 1, NULL, NULL, 0),
(4, 'VĂN HỌC', 'van-hoc', NULL, 1, 2, 1, 1, NULL, NULL, 0),
(5, 'KINH TẾ', 'kinh-te', NULL, 1, 2, 2, 1, NULL, NULL, 0),
(6, 'TÂM LÝ - KỸ NĂNG', 'tam-ly-ky-nang', NULL, 1, 2, 3, 1, NULL, NULL, 0),
(7, 'NUÔI DẠY CON', 'nuoi-day-con', NULL, 1, 2, 4, 1, NULL, NULL, 0),
(8, 'SÁCH THIẾU NHI', 'sach-thieu-nhi', NULL, 1, 2, 5, 1, NULL, NULL, 0),
(9, 'TIỂU SỬ - HỒI KÝ', 'tieu-su-hoi-ky', NULL, 1, 2, 6, 1, NULL, NULL, 0),
(10, 'GIÁO KHOA - THAM KHẢO', 'giao-khoa-tham-khao', NULL, 1, 2, 7, 1, NULL, NULL, 0),
(11, 'HỌC NGOẠI NGỮ', 'hoc-ngoai-ngu', NULL, 1, 2, 8, 1, NULL, NULL, 0),
(12, 'Tiểu Thuyết', 'tieu-thuyet', NULL, 4, 3, 1, 1, NULL, NULL, 0),
(13, 'Truyện Ngắn - Tản Văn', 'truyen-ngan-tan-van', NULL, 4, 3, 2, 1, NULL, NULL, 0),
(14, 'Light Novel', 'light-novel', NULL, 4, 3, 3, 1, NULL, NULL, 0),
(15, 'Ngôn Tình', 'ngon-tinh', NULL, 4, 3, 4, 1, NULL, NULL, 0),
(16, 'Nhân Vật - Bài Học KD', 'nhan-vat-bai-hoc-kd', NULL, 5, 3, 1, 1, NULL, NULL, 0),
(17, 'Quản Trị - Lãnh Đạo - Tản Văn', 'quan-tri-lanh-dao', NULL, 5, 3, 2, 1, NULL, NULL, 0),
(18, 'Marketing - Bán Hàng', 'marketing-ban-hang', NULL, 5, 3, 3, 1, NULL, NULL, 0),
(19, 'Phân Tích Kinh Tế', 'phan-tich-kinh-te', NULL, 5, 3, 4, 1, NULL, NULL, 0),
(20, 'Kỹ năng sống', 'ky-nang-song', NULL, 6, 3, 1, 1, NULL, NULL, 0),
(21, 'Rèn luyện nhân cách', 'ren-luyen-nhan-cach', NULL, 6, 3, 2, 1, NULL, NULL, 0),
(22, 'Tâm lý', 'tam-ly', NULL, 6, 3, 3, 1, NULL, NULL, 0),
(23, 'Sách tuổi mới lớn', 'sach-tuoi-moi-lon', NULL, 6, 3, 4, 1, NULL, NULL, 0),
(24, 'Cẩm nang làm cha mẹ', 'cam-nang-lam-cha-me', NULL, 7, 3, 1, 1, NULL, NULL, 0),
(25, 'Phương pháp giáo dục', 'phuong-phap-giao-duc', NULL, 7, 3, 2, 1, NULL, NULL, 0),
(26, 'Phát triển trí tuệ', 'phat-trien-tri-tue', NULL, 7, 3, 3, 1, NULL, NULL, 0),
(27, 'Phát triển kỹ năng', 'phat-trien-ky-nang', NULL, 7, 3, 4, 1, NULL, NULL, 0),
(28, 'Manga - Comic', 'manga-comic', NULL, 8, 3, 1, 1, NULL, NULL, 0),
(29, 'Kiến thức bách khoa', 'kien-thuc-bach-khoa', NULL, 8, 3, 2, 1, NULL, NULL, 0),
(30, 'Sách tranh kỹ năng', 'sach-tranh-ky-nang', NULL, 8, 3, 3, 1, NULL, NULL, 0),
(31, 'Vừa học vừa chơi', 'vua-hoc-vua-choi', NULL, 8, 3, 4, 1, NULL, NULL, 0),
(32, 'Câu chuyện cuộc đời', 'cau-chuyen-cuoc-doi', NULL, 9, 3, 1, 1, NULL, NULL, 0),
(33, 'Chính trị', 'chinh-tri', NULL, 9, 3, 2, 1, NULL, NULL, 0),
(35, 'Nghệ thuật - Giaỉ trí', 'nghe-thuat-giai-tri', NULL, 9, 3, 4, 1, NULL, NULL, 0),
(36, 'Sách giáo khoa', 'sach-giao-khoa', NULL, 10, 3, 1, 1, NULL, NULL, 0),
(37, 'Sách tham khảo', 'sach-tham-khao', NULL, 10, 3, 2, 1, NULL, NULL, 0),
(38, 'Luyện thi THPT QG', 'luyen-thi-thpt-qg', NULL, 10, 3, 3, 1, NULL, NULL, 0),
(39, 'Mẫu giáo', 'mau-giao', NULL, 10, 3, 4, 1, NULL, NULL, 0),
(40, 'Tiếng Anh', 'tieng anh', NULL, 11, 3, 1, 1, NULL, NULL, 0),
(41, 'Tiếng Nhật', 'tieng-nhat', NULL, 11, 3, 2, 1, NULL, NULL, 0),
(42, 'Tiếng Hoa', 'tieng-hoa', NULL, 11, 3, 3, 1, NULL, NULL, 0),
(43, 'Tiếng Hàn', 'tieng-han', NULL, 11, 3, 4, 1, NULL, NULL, 0),
(44, 'SÁCH MỚI', 'sach-moi', NULL, 1, 2, 9, 1, NULL, NULL, 1),
(45, 'SÁCH BÁN CHẠY', 'sach-ban-chay', NULL, 1, 2, 10, 1, NULL, NULL, 1),
(46, 'MANGA MỚI', 'manga-moi', NULL, 1, 2, 11, 1, NULL, NULL, 1),
(47, 'LIGHT-NOVEL', 'light-novel-2', NULL, 1, 2, 12, 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gio_hang`
--

CREATE TABLE `gio_hang` (
  `ma_gio_hang` int(11) NOT NULL,
  `ma_nguoi_dung` int(11) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `ngay_cap_nhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gio_hang`
--

INSERT INTO `gio_hang` (`ma_gio_hang`, `ma_nguoi_dung`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 1, '2025-12-06 10:12:29', '2025-12-10 15:27:19'),
(2, 3, '2025-12-06 15:10:48', '2025-12-06 15:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `hinh_anh_sach`
--

CREATE TABLE `hinh_anh_sach` (
  `ma_hinh_anh` int(11) NOT NULL,
  `ma_sach` int(11) DEFAULT NULL,
  `duong_dan_hinh` varchar(255) NOT NULL,
  `la_anh_chinh` tinyint(1) DEFAULT 0,
  `thu_tu` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hinh_anh_sach`
--

INSERT INTO `hinh_anh_sach` (`ma_hinh_anh`, `ma_sach`, `duong_dan_hinh`, `la_anh_chinh`, `thu_tu`) VALUES
(140, 1, '/book-sachthieunhi/dora-hiepsikhonggian-2.jpg', 1, 0),
(141, 1, '/book-sachthieunhi/dora-hiepsikhonggian.jpg', 0, 0),
(142, 5, '/book-sachthieunhi/conantap100.jpg', 1, 0),
(145, 2, '/book-vanhoc/matbiec.jpg', 1, 0),
(146, 2, '/book-vanhoc/matbiec2.jpg', 0, 0),
(153, 6, '/book-sachthieunhi/onepiece.jpg', 1, 0),
(154, 6, '/book-sachthieunhi/onepiece2.jpg', 0, 0),
(155, 7, '/book-vanhoc/kafkabenbobien.jpg', 1, 0),
(156, 8, '/book-vanhoc/toithayhoavangtrencoxanh.jpg', 1, 0),
(157, 3, '/book-vanhoc/nhagiakim.jpg', 1, 0),
(158, 3, '/book-vanhoc/nhagiakim2.jpg', 0, 0),
(159, 10, '/book-vanhoc/neubiettramnamlahuuhan.jpg', 1, 0),
(160, 10, '/book-vanhoc/neubiettramnamlahuuhan2.jpg', 0, 0),
(161, 4, '/book-tamlykynang/dacnhantam.jpg', 1, 0),
(162, 4, '/book-tamlykynang/dacnhantam2.jpg', 0, 0),
(163, 13, '/book-vanhoc/muontrungxuso.jpg', 1, 0),
(165, 14, '/book-vanhoc/damtreodaiduongden.jpg', 1, 0),
(170, 16, '/book-vanhoc/duocsutusu.jpg', 1, 0),
(171, 17, '/book-vanhoc/tayanchienky.jpg', 1, 0),
(172, 17, '/book-vanhoc/tayanchienky2.jpg', 0, 0),
(174, 15, '/book-vanhoc/thanhphothieu1buacomnha.jpg', 1, 0),
(175, 19, '/book-vanhoc/anyatiengnga.jpg', 1, 0),
(176, 20, '/book-vanhoc/toimuonbaove.jpg', 1, 0),
(178, 21, '/book-vanhoc/benxe.jpg', 1, 0),
(179, 22, '/book-vanhoc/dauxuavuilanh.jpg', 1, 0),
(180, 18, '/book-vanhoc/thiensunhaben.jpg', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `ma_nguoi_dung` int(11) NOT NULL,
  `ten_dang_nhap` varchar(50) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `ngay_dang_ky` datetime DEFAULT current_timestamp(),
  `trang_thai` enum('active','inactive') DEFAULT 'active',
  `vai_tro` enum('customer','admin') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`ma_nguoi_dung`, `ten_dang_nhap`, `mat_khau`, `email`, `ho_ten`, `so_dien_thoai`, `dia_chi`, `ngay_dang_ky`, `trang_thai`, `vai_tro`) VALUES
(1, 'admin', '$2y$10$mKdTRsWHIOC01omzng9pAeGh6DGGQWFxOCI1uOYvj.6Dt3i/MJY82', 'admin@fahasa.com', 'Quản Trị Viên', '0901234567', 'TP.HCM', '2025-11-23 15:58:48', 'active', 'admin'),
(2, 'dathz1456', '$2y$10$qLIbjanyM3H6OCB/lqejvOh62LhMr8EgahlczUFDIvAiPtxLKgT0a', 'dathz1456@gmail.com', 'dat12345', NULL, NULL, '2025-11-30 16:09:01', 'active', 'customer'),
(3, 'datnt40023201', '$2y$10$KSXs7wiiVAJe75sGz2CGNeJWhAl5hGqbOv6ZVuU79QjCa1nvjae92', 'datnt40023201@gmail.com', 'dat1234567', NULL, NULL, '2025-12-06 11:26:52', 'active', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `nha_xuat_ban`
--

CREATE TABLE `nha_xuat_ban` (
  `ma_nxb` int(11) NOT NULL,
  `ten_nxb` varchar(200) NOT NULL,
  `dia_chi` text DEFAULT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nha_xuat_ban`
--

INSERT INTO `nha_xuat_ban` (`ma_nxb`, `ten_nxb`, `dia_chi`, `so_dien_thoai`, `email`) VALUES
(1, 'Nhà Xuất Bản Kim Đồng', '55 Quang Trung, Hai Bà Trưng, Hà Nội', '0243943456', 'info@nxbkimdong.com.vn'),
(2, 'Nhà Xuất Bản Trẻ', '161B Lý Chính Thắng, Q.3, TP.HCM', '0283931708', 'info@nxbtre.com.vn'),
(3, 'NXB Văn Học', '18 Nguyễn Trường Tộ, Ba Đình, Hà Nội', '0243733226', 'nxbvanhoc@fpt.vn'),
(4, 'NXB Thế Giới', '7 Nguyễn Thị Minh Khai, Q.1, TP.HCM', '0283822341', 'info@thegioipublishers.vn'),
(5, 'NXB Tổng Hợp TP.HCM', '62 Nguyễn Thị Minh Khai, Q.3, TP.HCM', '0283930123', 'nhaxuatban@nxbhcm.com.vn');

-- --------------------------------------------------------

--
-- Table structure for table `sach`
--

CREATE TABLE `sach` (
  `ma_sach` int(11) NOT NULL,
  `ten_sach` varchar(255) NOT NULL,
  `ma_nxb` int(11) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia_ban` decimal(10,2) NOT NULL,
  `gia_goc` decimal(10,2) DEFAULT NULL,
  `so_luong_ton` int(11) DEFAULT 0,
  `so_trang` int(11) DEFAULT NULL,
  `hinh_thuc_bia` varchar(50) DEFAULT NULL,
  `ngon_ngu` varchar(50) DEFAULT 'Tiếng Việt',
  `nam_xuat_ban` int(11) DEFAULT NULL,
  `ma_isbn` varchar(20) DEFAULT NULL,
  `ngay_them` datetime DEFAULT current_timestamp(),
  `luot_xem` int(11) DEFAULT 0,
  `trang_thai` enum('available','out_of_stock','discontinued') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sach`
--

INSERT INTO `sach` (`ma_sach`, `ten_sach`, `ma_nxb`, `mo_ta`, `gia_ban`, `gia_goc`, `so_luong_ton`, `so_trang`, `hinh_thuc_bia`, `ngon_ngu`, `nam_xuat_ban`, `ma_isbn`, `ngay_them`, `luot_xem`, `trang_thai`) VALUES
(1, 'Doraemon - Movie Story: Nobita Và Những Hiệp Sĩ Không Gian', 1, 'Câu chuyện phiêu lưu của Nobita và những người bạn trong không gian', 33000.00, 35000.00, 120, 139, 'Bìa Mềm', 'Tiếng Việt', 2025, '9786042393324', '2025-11-23 15:31:27', 1603, 'available'),
(2, 'Mắt Biếc', 2, 'Tác phẩm văn học nổi tiếng của nhà văn Nguyễn Nhật Ánh', 108000.00, 120000.00, 85, 368, 'Bìa Mềm', 'Tiếng Việt', 2023, '9786041169647', '2025-11-23 15:31:27', 3428, 'available'),
(3, 'Nhà Giả Kim', 3, 'Cuốn sách nổi tiếng của Paulo Coelho về hành trình tìm kiếm ước mơ', 79000.00, 95000.00, 150, 227, 'Bìa Mềm', 'Tiếng Việt', 2024, '9786042105323', '2025-11-23 15:31:27', 5686, 'available'),
(4, 'Đắc Nhân Tâm', 2, 'Cuốn sách kinh điển về nghệ thuật giao tiếp và ứng xử', 86000.00, 108000.00, 200, 320, 'Bìa Cứng', 'Tiếng Việt', 2023, '9786041132937', '2025-11-23 15:31:27', 8929, 'available'),
(5, 'Detective Conan - Tập 100', 1, 'Thám tử lừng danh Conan và những vụ án bí ẩn', 25000.00, 30000.00, 300, 186, 'Bìa Mềm', 'Tiếng Việt', 2024, '9786042155678', '2025-11-23 15:31:27', 2166, 'available'),
(6, 'One Piece - Tập 105', 1, 'Cuộc phiêu lưu của Luffy và băng Mũ Rơm', 28000.00, 32000.00, 250, 192, 'Bìa Mềm', 'Tiếng Việt', 2024, '9786042167890', '2025-11-23 15:31:27', 3893, 'available'),
(7, 'Kafka Bên Bờ Biển', 3, 'Tác phẩm nổi tiếng của Haruki Murakami', 165000.00, 195000.00, 60, 568, 'Bìa Mềm', 'Tiếng Việt', 2023, '9786041198765', '2025-11-23 15:31:27', 982, 'available'),
(8, 'Tôi Thấy Hoa Vàng Trên Cỏ Xanh', 2, 'Tuổi thơ dữ dội và nhân ái của Nguyễn Nhật Ánh', 95000.00, 115000.00, 110, 432, 'Bìa Mềm', 'Tiếng Việt', 2023, '9786041154321', '2025-11-23 15:31:27', 2675, 'available'),
(10, 'Nếu Biết Trăm Năm Là Hữu Hạn - Ấn Bản Kỉ Niệm 10 Năm Xuất Bản (Tái Bản 2024)', 1, 'NẾU BIẾT TRĂM NĂM LÀ HỮU HẠN - LÁ THƯ GỬI NHỮNG NGƯỜI TRẺ ĐANG LẠC LỐI\r\nBạn đã bao giờ tự hỏi: Nếu biết trước cuộc đời là hữu hạn, bạn sẽ sống khác đi chứ? \r\n\r\nChúng ta luôn nghĩ mình có nhiều thời gian, nhưng thực tế, mọi khoảnh khắc đều đang trôi qua mãi mãi.\r\n\r\nVỀ TÁC GIẢ: PHẠM LỮ ÂN\r\nLà bút danh của đôi vợ chồng nhà báo chuyên viết cho giới trẻ, là Đặng Nguyễn Đông Vy và Nguyễn Hoàng Mai, hai nhà văn nổi bật trong dòng sách truyền cảm hứng.\r\n\r\nNhững tác phẩm của họ không chỉ là lời kể, mà là những triết lý sống sâu sắc, giúp độc giả nhìn lại chính mình.\r\n\r\nNếu Biết Trăm Năm Là Hữu Hạn là một trong những cuốn sách được yêu thích nhất, giúp hàng ngàn người trẻ tìm lại ý nghĩa của cuộc sống.\r\n\r\nTÓM TẮT NỘI DUNG SÁCH\r\nNếu Biết Trăm Năm Là Hữu Hạn là tập hợp 40 bài viết nhẹ nhàng nhưng sâu sắc, giàu cảm xúc từ chuyên mục Cảm thức của Bán nguyệt san Sinh Viên Việt Nam. Cuốn sách dẫn dắt người đọc đi sâu vào những cảm nhận về cuộc đời, tình yêu, tình bạn và sự thành bại, đặt ra những câu hỏi mà ai cũng từng nghĩ đến nhưng ít ai dám đối diện:\r\n\r\nChúng ta đang sống hay chỉ đang tồn tại?\r\n\r\nHạnh phúc thực sự nằm ở đâu?\r\n\r\nĐiều gì sẽ khiến chúng ta không hối tiếc khi nhìn lại?\r\n\r\nVới giọng văn dung dị, thân mật, tác giả dễ dàng chạm đến trái tim người đọc, khiến ta như đang lắng nghe một người bạn tâm sự. Những câu chuyện giản dị nhưng chứa nhiều tầng cảm xúc: hoài niệm, sâu sắc, chân thành - gợi mở những suy ngẫm mới mẻ về giá trị của từng khoảnh khắc trong cuộc đời.\r\n\r\nCuốn sách không chỉ là một tác phẩm văn học mà còn là một lời nhắc nhở nhẹ nhàng: Thời gian là hữu hạn, hãy sống sao cho xứng đáng!\r\n\r\nVì sao bạn không nên bỏ lỡ cuốn sách này?\r\nNếu bạn từng trì hoãn hạnh phúc của mình cho một ngày \"đủ đầy\" trong tương lai.\r\n\r\nNếu bạn từng loay hoay giữa những lựa chọn, sợ hãi mình sẽ hối tiếc.\r\n\r\nNếu bạn muốn sống một cuộc đời mà không phải quay đầu nhìn lại với tiếc nuối.\r\n\r\nCuốn sách giúp bạn nhận ra điều gì?\r\nNếu Biết Trăm Năm Là Hữu Hạn là một lời nhắc nhở nhẹ nhàng nhưng đầy ám ảnh, giúp bạn nhận ra:\r\n\r\nHạnh phúc không nằm ở tương lai xa vời mà ngay trong hiện tại.\r\nCuộc sống hữu hạn, đừng chờ đến khi quá muộn mới nhận ra điều gì đáng giá.\r\nNhững gì nhỏ bé hôm nay có thể trở thành những kỷ niệm lớn nhất mai sau. ', 129000.00, 159000.00, 10, 263, 'Bìa mềm', 'Tiếng Việt', 2024, '8932000134749', '2025-12-12 20:36:46', 0, 'available'),
(13, 'Muôn Trùng Xứ Sở', NULL, 'Muôn Trùng Xứ Sở\r\n\r\nCó những miền ký ức không nằm trên bản đồ, nhưng in sâu trong lòng mỗi người.\r\n\r\nLà hình ảnh má lúi húi nạo dừa sau bếp, vừa làm vừa rôm rả đủ thứ chuyện trong nhà ngoài xóm. Là ánh mắt ba im lặng trước hiên nhà, dầu không nói nhưng chứa trong đó một đại dương những chuyện chưa từng kể. Là tiếng “ngoại ơi”, “a pò” (阿婆) cất lên từ thuở chưa biết buồn, bây giờ nghe lại thấy tim nhói một nhịp nơi lòng.\r\n\r\n\"Muôn Trùng Xứ Sở\" là hành trình đi qua những ngóc ngách của những ngày cũ kỹ - không chỉ của riêng tác giả mà là của rất nhiều người con miền Tây, người Hoa, những đứa trẻ lớn lên giữa Chợ Lớn và bãi bờ phù sa của miền Cửu Long bằng tiếng rầy của ba má.\r\n\r\nỞ đó, có những người ba người má không giỏi biểu lộ, có những “a pò a cúng” quấn quýt bên bếp chè mâm bánh, có tiếng kêu í ới trong chợ nhỏ, có giọng nửa Việt nửa Quảng Đông lơ lớ í ới theo từng bữa ăn, từng câu thăm hỏi...\r\n\r\nMọi thứ đều rất đời thường, nhưng lại khiến ta chùng lòng - và quê hương đã ở lại trong chúng ta như một dải ngân hà lặng lẽ.\r\n\r\nCàng lớn, càng nhớ cái chỗ người ta thương mình không cần nói ra một câu nào, ấy vậy mà tình thương đó cứ miên man lan truyền tới trọn đời trọn kiếp...', 95000.00, 129000.00, 30, 280, 'Bìa mềm', 'Tiếng Việt', 2025, '8935325029950', '2025-12-12 20:47:26', 0, 'available'),
(14, 'Đám Trẻ Ở Đại Dương Đen', 4, 'ĐÁM TRẺ Ở ĐẠI DƯƠNG ĐEN - NHỮNG TIẾNG GỌI TUYỆT VỌNG GIỮA LÒNG ĐẠI DƯƠNG LẠNH\r\nKhi những đứa trẻ bị cuộc đời nuốt chửng, chúng sẽ làm gì?\r\n\r\nChìm sâu, hay bật lên như một nhát dao xuyên thủng mặt nước?\r\n\r\nVỀ TÁC GIẢ:Châu Sa Đáy Mắt\r\nTác giả trẻ thuộc thế hệ Gen Z, mang giọng văn đậm chất thơ và chiều sâu nội tâm.\r\n\r\nBút danh “Châu Sa Đáy Mắt” gợi hình ảnh mong manh, nhiều tầng cảm xúc, là hiện thân của những tâm hồn từng chìm sâu trong tổn thương.\r\n\r\nTác phẩm tập trung vào các vấn đề tâm lý, gia đình, sự mất mát và hành trình chữa lành - đặc biệt chạm đến nỗi đau của những người trẻ bị bỏ lại phía sau.\r\n\r\nKhông ngại đối diện với những góc tối, cô dùng văn chương như một hình thức “nói hộ” những điều không thể nói.\r\n\r\nTÓM TẮT NỘI DUNG SÁCH\r\nTrong một đại dương không có sóng, chỉ có những tầng u ám của nỗi buồn đang lặng lẽ dìm chết nội tâm - những đứa trẻ hiện ra, không tên, không màu, không cả quyền được ồn ào.\r\n\r\nChúng là ai? Là những tâm hồn non nớt bị bỏ rơi bởi chính nơi gọi là \"nhà\". Là những đứa trẻ không được quyền lựa chọn sự xuất hiện của mình trong cuộc đời này - và đang tự bơi giữa dòng nước đen của tổn thương, của cô độc, của câu hỏi không lời giải: \"Vì sao mình lại ở đây?\"\r\n\r\nKhông giật gân, không gào thét, cuốn sách là một hành trình của lặng im nhưng kháng cự, của đau nhưng không đầu hàng. Những đoạn độc thoại như lời thì thầm từ vực sâu, những mảnh ký ức rời rạc tưởng như sẽ chìm mãi dưới đáy… bỗng trồi lên thành tiếng nói - của một thế hệ trẻ đang đi tìm ánh sáng nhỏ nhất trong biển đêm.\r\n\r\nTẠI SAO NÊN ĐỌC VÀ SỞ HỮU “ĐÁM TRẺ Ở ĐẠI DƯƠNG ĐEN”?\r\n\"Đám Trẻ Ở Đại Dương Đen\" không đơn thuần là một tác phẩm văn học - nó là tiếng vọng từ những vùng tối tâm lý chưa từng được chạm đến bằng lời.\r\n\r\nCuốn sách ghi lại một cách trung thực những tổn thương bị che giấu kỹ càng trong nội tâm tuổi trẻ - vừa mong manh, vừa kháng cự.\r\n\r\nKhông theo lối kể chuyện truyền thống, tác phẩm dựng nên một hành trình \"vượt biển đen\" bằng chất văn đậm đặc, giàu hình ảnh, lay động mà không bi lụy.\r\n\r\nĐặc biệt phù hợp để nghiên cứu, chiêm nghiệm hoặc làm chất liệu sáng tạo cho các lĩnh vực liên quan đến tâm lý học, giáo dục, nghệ thuật.\r\n\r\nĐIỀU LÀM NÊN SỨC HẤP DẪN CỦA “ĐÁM TRẺ Ở ĐẠI DƯƠNG ĐEN”?\r\nLối viết ngắn gọn nhưng sắc lạnh, kết hợp giữa văn chương, thơ và tự sự, tạo nên một tiết tấu đặc biệt như nhịp sóng ngầm.\r\n\r\nSử dụng hình ảnh ẩn dụ đầy chiều sâu: “đại dương đen”, “đứa trẻ không tên”... mở ra không gian liên tưởng mạnh mẽ.\r\n\r\nKhai thác đề tài tâm lý và sự đổ vỡ trong gia đình một cách thẳng thắn nhưng giàu tính nghệ thuật, không rao giảng, không gượng ép.\r\n\r\nLà minh chứng rõ nét cho xu hướng văn học GenZ hiện đại - văn chương như một phương tiện tự chữa lành và đối thoại nội tâm.', 59000.00, 99000.00, 15, 280, 'Bìa mềm', 'Tiếng Việt', 2025, '8935325011559', '2025-12-12 20:53:07', 1, 'available'),
(15, 'Thành Phố Thiếu Một Bữa Cơm Nhà', 5, '', 66000.00, 83000.00, 10, 208, 'Bìa mềm', 'Tiếng Việt', 2025, '9786326042023', '2025-12-12 20:56:23', 1, 'available'),
(16, '[Light Novel] Dược Sư Tự Sự - Tập 7 - Tặng Kèm Bookmark', 1, '[Light Novel] Dược Sư Tự Sự - Tập 7\r\n\r\nVụ việc của Lí Thụ phi giải quyết xong chưa được bao lâu, Cao Thuận đã lại đem rắc rối đến chỗ Miêu Miêu. Cụ thể, người ta cần Miêu Miêu tham gia kì thi tuyển nữ quan. Miêu Miêu đã dự thi trong tình trạng nửa phần là bị ép buộc. Kết quả, Miêu Miêu trở thành nữ quan mới phụ việc cho thái y. Trong thời gian này, Miêu Miêu chạm trán với một quân sư quái nhân phiền phức, những thái y thượng cấp nghiêm khắc và các nữ quan đồng nghiệp... Các nữ quan này dường như đã thoả thuận với nhau bày trò chơi xỏ Miêu Miêu. Đặc biệt là nữ quan đứng đầu tên Diêu thường xuyên tỏ ra thù địch.\r\n\r\n* DƯỢC SƯ TỰ SỰ là series light-novel thể loại trinh thám vô cùng độc đáo lấy bối cảnh cung đình. Truyện đã được chuyển thể manga và anime ra mắt vào cuối năm 2023. Toàn series đã vượt mốc 40 triệu bản tại thị trường Nhật Bản và luôn thống trị các bảng xếp hạng bán chạy mỗi khi ra tập mới! Anime đã chiếu xong mùa 2 và dự kiến sẽ có mùa 3 trong thời gian tới.', 112500.00, 125000.00, 100, 408, 'Bìa mềm', 'Tiếng Việt', 2025, '9786042251754', '2025-12-12 20:59:38', 1, 'available'),
(17, 'Tanya Chiến Ký - Tập 5 - Abyssus Abyssum Invocate - Tặng Kèm Bookmark + Móc Khóa CD', 1, 'Tanya Chiến Ký - Tập 5 - Abyssus Abyssum Invocate\r\n\r\nVới sự trỗi dậy của tàn dư quân đội Cộng Hòa ở phía nam, cùng với sự tham chiến của hải quân Vương Quốc Liên Hiệp ở phía tây và quân đội Liên Bang ở phía đông, Đế quốc cùng lúc phải đối đầu với nhiều kẻ địch hùng mạnh ở cả 3 phía. Bộ Tổng tham mưu quân đội Đế Quốc đã xây dựng nên chiến lược chiến tranh cơ động, với cốt lõi là đoàn chiến đấu bao gồm các đơn vị bộ binh, pháo binh, tăng thiết giáp cùng với sự hỗ trợ của các ma pháp sư không quân, chỉ huy bởi vị trung tá ma pháp sư tài năng và mẫn cán Tanya von Degurechaff.\r\n\r\nTanya và đoàn chiến đấu Salamander của cô đã liên tục được cho thử nghiệm ở mặt trận phía nam và cả mặt trận phía đông. Có thể nói, đây là hai khu vực với điều kiện khí hậu khắc nghiệt trái ngược nhau. Nơi sa mạc phương nam nóng nực, thiếu thốn nguồn nước. Còn ở phía đông lại là những vùng đồng bằng rộng mênh mông, mùa đông thì bị bao phủ bởi tuyết trắng. Dù sở hữu đoàn chiến đấu tinh nhuệ, nhưng Tanya vẫn gặp vô vàn khó khăn, từ những cuộc tập kích lúc nửa đêm từ du kích Liên Bang, cho tới viễn cảnh về một mùa đông khắc nghiệt đang tới gần.\r\n\r\nĐúng lúc này, điều mà Đế Quốc không mong muốn nhất đã xảy ra. Vương Quốc Liên Hiệp và Liên Bang chính thức bắt tay nhau trong mặt trận chung chống lại Đế Quốc. Tuy vậy, đấy lại cũng chính là thời điểm bộ Tổng tham mưu quân đội Đế Quốc tìm ra được một lối thoát khỏi vực thẳm không đáy mà họ đã mắc kẹt suốt bấy lâu. Bằng cách tạo ra một vực thẳm khác nữa, Đế quốc không những thoát khỏi vực thẳm giam giữ mình, mà còn đẩy kẻ địch vào chính cái vực thẳm ấy.\r\n\r\nTập 5 của Tanya chiến ký – Abyssus Abyssum Invocat.\r\n\r\nMục lục:\r\n\r\nChương O: Bức thư\r\n\r\nChương I: Tiến công thần tốc\r\n\r\nChương II: Tình bạn kỳ lạ\r\n\r\nChương III: Chiến dịch phương bắc\r\n\r\nChương IV: Chiến dịch tấn công tầm xa\r\n\r\nChương V: Hết giờ\r\n\r\nChương VI: “Người giải phóng”\r\n\r\nPhụ lục\r\n\r\nLời bạt', 211500.00, 249000.00, 40, 526, 'Bìa mềm', 'Tiếng Việt', 2025, '8935280919853-qt', '2025-12-12 21:05:21', 0, 'available'),
(18, 'Thiên Sứ Nhà Bên - Tập 3', 1, '“Mọi người đều thân thiết với Amane, chỉ có tôi như bị cho ra rìa vậy đó.”\r\n\r\nMahiru và Amane đã lên lớp 11, họ trở thành bạn cùng lớp với nhau! Trái với Mahiru luôn cố gắng thu hẹp khoảng cách kể cả khi ở trường, Amane vẫn giữ ý với “thiên sứ” và không tiến thêm một bước nào.\r\n\r\nNhờ có Chitose mà Mahiru dần xóa bỏ bức tường ngăn cách với các bạn cùng lớp, trong khi Amane lại nhớ đến vết thương cũ vừa lành trong tim...\r\n\r\nĐây là câu chuyện tình ngọt ngào với cô gái nhà bên tuy lạnh lùng nhưng thật đáng yêu đã được ủng hộ nhiệt tình trên trang Shousetsuka ni Narou.\r\n\r\n* THIÊN SỨ NHÀ BÊN được xem là cú hit của dòng Light Novel rom-com tại thị trường Nhật Bản, với nội dung hài hước - lãng mạn rất được yêu thích. Tác phẩm nằm top 10 Kono Light novel ga Sugoi năm 2021, đã bán ra hơn 400.000 bản chỉ với 4 tập truyện riêng tại Nhật Bản.\r\n\r\nSố tập: 5+ (on-going)\r\n\r\n---\r\n\r\nMột ấn phẩm của WINGS BOOKS - Thương hiệu sách trẻ của NXB Kim Đồng.', 85500.00, 100000.00, 40, 316, 'Bìa mềm', 'Tiếng Việt', 2022, '	 8935244872972', '2025-12-12 21:08:38', 1, 'available'),
(19, 'Arya Bàn Bên Thỉnh Thoảng Lại Trêu Ghẹo Tôi Bằng Tiếng Nga - Tập 6 - Tặng Kèm Bookmark Bế Hình + Bìa Áo Bonus', 1, 'Arya Bàn Bên Thỉnh Thoảng Lại Trêu Ghẹo Tôi Bằng Tiếng Nga - Tập 6\r\n\r\n“И наменятоже обрати внимание”\r\n\r\nUy tín của Hội học sinh đang bị đe dọa trong lễ hội trường của Học viện Seirei. Sau khi kết thúc ngày đầu tiên với chiến thắng đầy kịch tính của Arya, lễ hội Shuurei bắt đầu bước vào ngày hội cuối cùng!\r\n\r\nTình yêu, cosplay, biểu diễn ban nhạc… ngay khi sự phấn khích lên đến đỉnh điểm, một sự cố lớn đã xảy ra…!? Khi nghĩ rằng lễ hội trường sắp bị phá hủy bởi âm mưu của người nào đó, Masachika đã quyết định hành động một mình để chấm dứt tình trạng hỗn loạn.\r\n\r\n“Hãy tin tưởng và đợi tôi. Nhất định tôi sẽ để các cậu được lên sân khấu.”\r\n\r\n“Ừ, tôi tin cậu.”\r\n\r\nLiệu Masachika có thể đối phó với những âm mưu diễn ra trong lễ hội trường và giúp Arya được tỏa sáng trên sân khấu?\r\n\r\nĐây là Tập 6 của bộ truyện thanh xuân lãng mạn và hài hước về cô nàng nữ sinh trung học gốc Nga xinh đẹp thích che giấu sự ngọt ngào của mình sau những lời trêu ghẹo!', 85500.00, 95000.00, 30, 404, 'Bìa mềm', 'Tiếng Việt', 2024, '8935352620151', '2025-12-12 21:40:28', 0, 'available'),
(20, 'Tôi Muốn Bảo Vệ Cậu Dù Phải Mất Đi... Tình Yêu Này', 1, 'Tôi Muốn Bảo Vệ Cậu Dù Phải Mất Đi... Tình Yêu Này\r\n\r\nTác phẩm đạt giải Nhất của Giải thưởng Starts Publishing Bunko lần thứ 5!\r\n\r\nMoeka lớn lên cùng những trải nghiệm không mấy tốt đẹp xuất phát từ việc cô bị mắc chứng nói lắp từ nhỏ, tính cách nhút nhát và luôn lo sợ. Cậu học sinh chuyển trường mới đến là Terumichi lại vô cùng vui vẻ, hòa đồng, đối lập hẳn với Moeka, cũng chính là người đã khiến cuộc sống của cô thay đổi từng chút mỗi ngày. Vì không thể thích ứng được với tính cách của Terumichi nên Moeka luôn muốn tránh xa khỏi cậu, nhưng rồi một ngày, cô bất ngờ biết được rằng thì ra Terumichi cũng có những vết thương lòng giống mình. Câu chuyện tình yêu nhẹ nhàng, trong sáng của hai người cũng đã bắt đầu từ khoảnh khắc đó.\r\n\r\nTrích dẫn trong sách:\r\n\r\n“Dù Moeka có nghĩ gì, không thích ở bên tớ như thế nào, chán ghét tớ ra sao, tớ vẫn cảm thấy hạnh phúc khi chúng ta ở cùng nhau.”', 94000.00, 126000.00, 50, 344, 'Bìa mềm', 'Tiếng Việt', 2023, '8935325017155', '2025-12-12 21:53:07', 0, 'available'),
(21, 'Bến Xe (Tái Bản 2020)', 4, 'Bến Xe (Tái Bản 2020)\r\n\r\nBến Xe\r\n\r\nThứ tôi có thể cho em trong cuộc đời này\r\n\r\nchỉ là danh dự trong sạch\r\n\r\nvà một tương lai tươi đẹp mà thôi.\r\n\r\nThế nhưng, nếu chúng ta có kiếp sau,\r\n\r\nnếu kiếp sau tôi có đôi mắt sáng,\r\n\r\ntôi sẽ ở bến xe này… đợi em.', 54720.00, 76000.00, 30, 284, 'Bìa mềm', 'Tiếng Việt', 2020, '8935212349208', '2025-12-12 21:59:59', 0, 'available'),
(22, 'Dấu Xưa, Vui Lành', 3, 'Non nước cỏ cây, trà trong rượu nhạt, đình đài lầu gác, vàng ngọc châu báu, đều có tình ý, chi tiết, có cơ duyên diệu kỳ khó mà diễn tả nổi. Biết làm sao khi sự vật sự việc có đẹp hơn đi chăng nữa, cũng cần người có lòng thưởng thức, thương tiếc nó. Nếu không chỉ là một sự tồn tại giản đơn, ngàn năm cũng như một ngày mà thôi.\r\n\r\nPhồn hoa trên đời khiến người ta say đắm ưa thích, yêu đến nỗi chẳng muốn rời tay, nhưng lại nhỏ bé tựa như hạt bụi, không thể trường tồn. Nhưng một kiếp một đời mênh mông này, rốt cuộc vẫn có điều mong cầu, hoặc là công danh lẫy lừng, hoặc là hoa mai cắm bình cùng gió mát. Nhân lúc tuổi hoa, bẻ vài cành hoa dại, thưởng mấy chén rượu thơ, điền vài câu tiểu lệnh, giãi bày dăm ba tâm sự, như thế, chẳng phụ nhân gian như gấm thêu.\r\n\r\nĐôi nét tác giả\r\n\r\nBạch Lạc Mai tên thật là Tư Trí Tuệ, sống ở Giang Nam, đơn giản tự chủ, tâm như lan thảo, văn chương thanh đạm.\r\n \r\n\r\nCẩm Phong đã xuất bản:\r\n- Năm tháng tĩnh lặng, kiếp này bình yên (tái bản 2018)\r\n- Gặp lại chốn hồng trần sâu nhất (tái bản 2018)\r\n- Duyên (tái bản 2018)\r\n- Nếu em an lành, đó là ngày nắng (xuất bản 2018)\r\n- Ngoảnh lại đã một đời (xuất bản 2019)\r\n- Bởi vì thấu hiểu cho nên từ bi (xuất bản 2020)\r\n \r\n\r\nCẩm Phong sắp xuất bản:\r\n- Anh đi ngang qua thời khắc khuynh thành của em (tạm dịch)\r\n- Tôi dùng cả thanh xuân để tìm em (tạm dịch)\r\n- Vị thời gian (tạm dịch)', 100000.00, 110000.00, 30, 373, 'Bìa mềm', 'Tiếng Việt', 2020, '8935212349208', '2025-12-12 22:03:56', 0, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `sach_danh_muc`
--

CREATE TABLE `sach_danh_muc` (
  `ma_sach` int(11) NOT NULL,
  `ma_danh_muc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sach_danh_muc`
--

INSERT INTO `sach_danh_muc` (`ma_sach`, `ma_danh_muc`) VALUES
(1, 28),
(1, 44),
(2, 12),
(2, 45),
(3, 12),
(3, 45),
(4, 16),
(4, 45),
(5, 28),
(5, 45),
(6, 28),
(6, 44),
(7, 12),
(8, 12),
(8, 45),
(10, 13),
(13, 13),
(14, 13),
(15, 13),
(16, 14),
(17, 14),
(18, 14),
(19, 14),
(20, 14),
(21, 15),
(22, 15);

-- --------------------------------------------------------

--
-- Table structure for table `sach_tac_gia`
--

CREATE TABLE `sach_tac_gia` (
  `ma_sach` int(11) NOT NULL,
  `ma_tac_gia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sach_tac_gia`
--

INSERT INTO `sach_tac_gia` (`ma_sach`, `ma_tac_gia`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 5),
(5, 6),
(6, 7),
(7, 4),
(8, 2),
(10, 8),
(13, 9),
(14, 10),
(15, 11),
(16, 12),
(17, 13),
(18, 14),
(19, 15),
(20, 16),
(21, 17),
(22, 18);

-- --------------------------------------------------------

--
-- Table structure for table `tac_gia`
--

CREATE TABLE `tac_gia` (
  `ma_tac_gia` int(11) NOT NULL,
  `ten_tac_gia` varchar(100) NOT NULL,
  `tieu_su` text DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `quoc_tich` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tac_gia`
--

INSERT INTO `tac_gia` (`ma_tac_gia`, `ten_tac_gia`, `tieu_su`, `ngay_sinh`, `quoc_tich`) VALUES
(1, 'Fujiko F Fujio', 'Bút danh chung của hai họa sĩ truyện tranh Nhật Bản Fujimoto Hiroshi và Abiko Motoo, nổi tiếng với tác phẩm Doraemon', '1933-12-01', 'Nhật Bản'),
(2, 'Nguyễn Nhật Ánh', 'Nhà văn Việt Nam nổi tiếng với các tác phẩm văn học thiếu nhi và tuổi mới lớn', '1955-05-07', 'Việt Nam'),
(3, 'Paulo Coelho', 'Tiểu thuyết gia, nhà thơ người Brazil, tác giả cuốn Nhà giả kim nổi tiếng', '1947-08-24', 'Brazil'),
(4, 'Haruki Murakami', 'Nhà văn và dịch giả người Nhật Bản', '1949-01-12', 'Nhật Bản'),
(5, 'Dale Carnegie', 'Nhà văn và diễn giả người Mỹ về phát triển bản thân', '1888-11-24', 'Mỹ'),
(6, 'Gosho Aoyama', 'Họa sĩ truyện tranh Nhật Bản, tác giả Detective Conan', '1963-06-21', 'Nhật Bản'),
(7, 'Eiichiro Oda', 'Họa sĩ truyện tranh Nhật Bản, tác giả One Piece', '1975-01-01', 'Nhật Bản'),
(8, 'Phạm Lữ Ân', 'Phạm Lữ Ân là bút danh chung của hai tác giả Đặng Nguyễn Đông Vy và Phạm Công Luận. Họ đã tạo dựng được tên tuổi trong lòng độc giả Việt Nam, đặc biệt là giới trẻ. Đặng Nguyễn Đông Vy được biết đến qua các tác phẩm như \"Hãy tìm tôi giữa cánh đồng\" và \"Làm ơn hãy để con yên\", trong khi Phạm Công Luận ghi dấu ấn với các tác phẩm như \"Những lối về ấu thơ\" và \"Chú bé Thất Sơn\".', '1998-01-30', 'Việt Nam'),
(9, 'Thòong Dành Kể Chuyện', 'Chịu ', '1998-01-30', 'Việt Nam'),
(10, 'Châu Sa Đáy Mắt', 'Chịu ', '1998-01-30', 'Việt Nam'),
(11, 'Hồng Nhung , Mỹ Hưng', 'Chịu', '1998-01-30', 'Việt Nam'),
(12, 'Touko Shino, Hyuganatsu', 'Chịu', '2000-01-30', 'Nhật Bản'),
(13, 'Carlo Zen, Shinobu Shimotsuki', 'Chịu', '2000-01-30', 'Nhật Bản'),
(14, 'Saekisan, Hanekoto', 'Chịu', '2000-01-30', 'Nhật Bản'),
(15, 'Sunsunsun, Momoco', 'Chịu', '2000-01-30', 'Nhật Bản'),
(16, 'Sou Inaida', 'Chịu', '2000-01-30', 'Nhật Bản'),
(17, 'Thương Thái Vi', 'Chịu', '2000-10-28', 'Trung Quốc'),
(18, 'Bạch Lạc Mai', 'Chịu', '2000-10-28', 'Trung Quốc');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD PRIMARY KEY (`ma_chi_tiet`),
  ADD UNIQUE KEY `ma_gio_hang` (`ma_gio_hang`,`ma_sach`),
  ADD KEY `ma_sach` (`ma_sach`);

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`ma_danh_gia`),
  ADD KEY `ma_sach` (`ma_sach`),
  ADD KEY `ma_nguoi_dung` (`ma_nguoi_dung`);

--
-- Indexes for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `danh_muc_cha` (`danh_muc_cha`);

--
-- Indexes for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`ma_gio_hang`),
  ADD UNIQUE KEY `ma_nguoi_dung` (`ma_nguoi_dung`);

--
-- Indexes for table `hinh_anh_sach`
--
ALTER TABLE `hinh_anh_sach`
  ADD PRIMARY KEY (`ma_hinh_anh`),
  ADD KEY `ma_sach` (`ma_sach`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`ma_nguoi_dung`),
  ADD UNIQUE KEY `ten_dang_nhap` (`ten_dang_nhap`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `nha_xuat_ban`
--
ALTER TABLE `nha_xuat_ban`
  ADD PRIMARY KEY (`ma_nxb`);

--
-- Indexes for table `sach`
--
ALTER TABLE `sach`
  ADD PRIMARY KEY (`ma_sach`),
  ADD KEY `ma_nxb` (`ma_nxb`);

--
-- Indexes for table `sach_danh_muc`
--
ALTER TABLE `sach_danh_muc`
  ADD PRIMARY KEY (`ma_sach`,`ma_danh_muc`),
  ADD KEY `ma_danh_muc` (`ma_danh_muc`);

--
-- Indexes for table `sach_tac_gia`
--
ALTER TABLE `sach_tac_gia`
  ADD PRIMARY KEY (`ma_sach`,`ma_tac_gia`),
  ADD KEY `ma_tac_gia` (`ma_tac_gia`);

--
-- Indexes for table `tac_gia`
--
ALTER TABLE `tac_gia`
  ADD PRIMARY KEY (`ma_tac_gia`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  MODIFY `ma_chi_tiet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `ma_danh_gia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `ma_gio_hang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hinh_anh_sach`
--
ALTER TABLE `hinh_anh_sach`
  MODIFY `ma_hinh_anh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `ma_nguoi_dung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nha_xuat_ban`
--
ALTER TABLE `nha_xuat_ban`
  MODIFY `ma_nxb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sach`
--
ALTER TABLE `sach`
  MODIFY `ma_sach` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tac_gia`
--
ALTER TABLE `tac_gia`
  MODIFY `ma_tac_gia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD CONSTRAINT `chi_tiet_gio_hang_ibfk_1` FOREIGN KEY (`ma_gio_hang`) REFERENCES `gio_hang` (`ma_gio_hang`) ON DELETE CASCADE,
  ADD CONSTRAINT `chi_tiet_gio_hang_ibfk_2` FOREIGN KEY (`ma_sach`) REFERENCES `sach` (`ma_sach`) ON DELETE CASCADE;

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`ma_sach`) REFERENCES `sach` (`ma_sach`) ON DELETE CASCADE,
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE;

--
-- Constraints for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD CONSTRAINT `danh_muc_ibfk_1` FOREIGN KEY (`danh_muc_cha`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE CASCADE;

--
-- Constraints for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `gio_hang_ibfk_1` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE;

--
-- Constraints for table `hinh_anh_sach`
--
ALTER TABLE `hinh_anh_sach`
  ADD CONSTRAINT `hinh_anh_sach_ibfk_1` FOREIGN KEY (`ma_sach`) REFERENCES `sach` (`ma_sach`) ON DELETE CASCADE;

--
-- Constraints for table `sach`
--
ALTER TABLE `sach`
  ADD CONSTRAINT `sach_ibfk_1` FOREIGN KEY (`ma_nxb`) REFERENCES `nha_xuat_ban` (`ma_nxb`);

--
-- Constraints for table `sach_danh_muc`
--
ALTER TABLE `sach_danh_muc`
  ADD CONSTRAINT `sach_danh_muc_ibfk_1` FOREIGN KEY (`ma_sach`) REFERENCES `sach` (`ma_sach`) ON DELETE CASCADE,
  ADD CONSTRAINT `sach_danh_muc_ibfk_2` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE CASCADE;

--
-- Constraints for table `sach_tac_gia`
--
ALTER TABLE `sach_tac_gia`
  ADD CONSTRAINT `sach_tac_gia_ibfk_1` FOREIGN KEY (`ma_sach`) REFERENCES `sach` (`ma_sach`) ON DELETE CASCADE,
  ADD CONSTRAINT `sach_tac_gia_ibfk_2` FOREIGN KEY (`ma_tac_gia`) REFERENCES `tac_gia` (`ma_tac_gia`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
