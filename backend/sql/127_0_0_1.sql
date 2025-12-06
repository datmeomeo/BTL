-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 07:31 AM
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
CREATE DATABASE IF NOT EXISTS `csdl_bansach` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `csdl_bansach`;

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
  `danh_muc_cha` int(11) DEFAULT 0,
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
(2, 'Foreign Books', 'foreign-books', NULL, NULL, 1, 2, 1, NULL, NULL, 0),
(3, 'SGK 2025', 'sgk-2025', NULL, NULL, 1, 3, 1, NULL, NULL, 0),
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
(34, 'Kinh tế', 'kinh-te-con', NULL, 3, 3, 3, 1, NULL, NULL, 0),
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
(1, 1, './assets/img-book/book-doraemon/dora-hiepsikhonggian-2.jpg', 1, 0),
(2, 1, './assets/img-book/book-doraemon/dora-hiepsikhonggian.jpg', 0, 1);

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
(1, 'admin', '123456', 'admin@fahasa.com', 'Quản Trị Viên', '0901234567', 'TP.HCM', '2025-11-23 15:58:48', 'active', 'admin');

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
  `ma_tac_gia` int(11) DEFAULT NULL,
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
(1, 'Doraemon - Movie Story: Nobita Và Những Hiệp Sĩ Không Gian', 1, 'Câu chuyện phiêu lưu của Nobita và những người bạn trong không gian', 33000.00, 35000.00, 120, 139, 'Bìa Mềm', 'Tiếng Việt', 2025, '9786042393324', '2025-11-23 15:31:27', 1296, 'available'),
(2, 'Mắt Biếc', 2, 'Tác phẩm văn học nổi tiếng của nhà văn Nguyễn Nhật Ánh', 108000.00, 120000.00, 85, 368, 'Bìa Mềm', 'Tiếng Việt', 2023, '9786041169647', '2025-11-23 15:31:27', 3420, 'available'),
(3, 'Nhà Giả Kim', 3, 'Cuốn sách nổi tiếng của Paulo Coelho về hành trình tìm kiếm ước mơ', 79000.00, 95000.00, 150, 227, 'Bìa Mềm', 'Tiếng Việt', 2024, '9786042105323', '2025-11-23 15:31:27', 5680, 'available'),
(4, 'Đắc Nhân Tâm', 2, 'Cuốn sách kinh điển về nghệ thuật giao tiếp và ứng xử', 86000.00, 108000.00, 200, 320, 'Bìa Cứng', 'Tiếng Việt', 2023, '9786041132937', '2025-11-23 15:31:27', 8920, 'available'),
(5, 'Detective Conan - Tập 100', 1, 'Thám tử lừng danh Conan và những vụ án bí ẩn', 25000.00, 30000.00, 300, 186, 'Bìa Mềm', 'Tiếng Việt', 2024, '9786042155678', '2025-11-23 15:31:27', 2150, 'available'),
(6, 'One Piece - Tập 105', 1, 'Cuộc phiêu lưu của Luffy và băng Mũ Rơm', 28000.00, 32000.00, 250, 192, 'Bìa Mềm', 'Tiếng Việt', 2024, '9786042167890', '2025-11-23 15:31:27', 3890, 'available'),
(7, 'Kafka Bên Bờ Biển', 3, 'Tác phẩm nổi tiếng của Haruki Murakami', 165000.00, 195000.00, 60, 568, 'Bìa Mềm', 'Tiếng Việt', 2023, '9786041198765', '2025-11-23 15:31:27', 980, 'available'),
(8, 'Tôi Thấy Hoa Vàng Trên Cỏ Xanh', 2, 'Tuổi thơ dữ dội và nhân ái của Nguyễn Nhật Ánh', 95000.00, 115000.00, 110, 432, 'Bìa Mềm', 'Tiếng Việt', 2023, '9786041154321', '2025-11-23 15:31:27', 2671, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `sach_danh_muc`
--

CREATE TABLE `sach_danh_muc` (
  `ma_sach` int(11) NOT NULL,
  `ma_danh_cha` int(11) NOT NULL
  `ma_danh_con` int(11) NOT NULL
  `ma_danh_chau` int(11) NOT NULL
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
(8, 45);

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
(8, 2);

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
(7, 'Eiichiro Oda', 'Họa sĩ truyện tranh Nhật Bản, tác giả One Piece', '1975-01-01', 'Nhật Bản');

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
  MODIFY `ma_chi_tiet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `ma_danh_gia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `ma_gio_hang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hinh_anh_sach`
--
ALTER TABLE `hinh_anh_sach`
  MODIFY `ma_hinh_anh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `ma_nguoi_dung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nha_xuat_ban`
--
ALTER TABLE `nha_xuat_ban`
  MODIFY `ma_nxb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sach`
--
ALTER TABLE `sach`
  MODIFY `ma_sach` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tac_gia`
--
ALTER TABLE `tac_gia`
  MODIFY `ma_tac_gia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
