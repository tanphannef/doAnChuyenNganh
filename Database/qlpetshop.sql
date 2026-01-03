-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 03, 2026 at 06:01 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlpetshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

DROP TABLE IF EXISTS `chitietdonhang`;
CREATE TABLE IF NOT EXISTS `chitietdonhang` (
  `idDonHang` int NOT NULL,
  `idSanPham` int NOT NULL,
  `SoLuong` int DEFAULT NULL,
  `Gia` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`idDonHang`,`idSanPham`),
  KEY `idSanPham` (`idSanPham`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`idDonHang`, `idSanPham`, `SoLuong`, `Gia`) VALUES
(0, 0, 2, 180000.00),
(2, 0, 2, 180000.00),
(3, 0, 1, 180000.00),
(4, 0, 1, 180000.00),
(5, 0, 1, 180000.00);

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc`
--

DROP TABLE IF EXISTS `danhmuc`;
CREATE TABLE IF NOT EXISTS `danhmuc` (
  `idDanhMuc` int NOT NULL,
  `TenDanhMuc` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`idDanhMuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhmuc`
--

INSERT INTO `danhmuc` (`idDanhMuc`, `TenDanhMuc`) VALUES
(1, 'Thức Ăn'),
(2, 'Vật Dụng'),
(3, 'Thú Cưng'),
(4, 'Phụ Kiện');

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

DROP TABLE IF EXISTS `donhang`;
CREATE TABLE IF NOT EXISTS `donhang` (
  `idDonHang` int NOT NULL AUTO_INCREMENT,
  `NgayTao` datetime NOT NULL,
  `TongTien` decimal(15,2) DEFAULT NULL,
  `DiaChiNhanHang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `TrangThai` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `idKhachHang` int NOT NULL,
  PRIMARY KEY (`idDonHang`),
  KEY `idKhachHang` (`idKhachHang`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`idDonHang`, `NgayTao`, `TongTien`, `DiaChiNhanHang`, `TrangThai`, `idKhachHang`) VALUES
(1, '2025-12-12 18:40:38', 360000.00, 'adfasdfasdf', 'Chờ xác nhận', 13),
(2, '2025-12-12 18:47:51', 360000.00, 'aaaaa', 'Chờ xác nhận', 13),
(3, '2025-12-14 19:10:39', 180000.00, 'qẻqadfasf', 'Chờ xác nhận', 14),
(4, '2025-12-14 19:13:14', 180000.00, 'ádfasdf', 'Chờ xác nhận', 14),
(5, '2025-12-15 17:01:59', 180000.00, 'adasd', 'Chờ xác nhận', 14);

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

DROP TABLE IF EXISTS `khachhang`;
CREATE TABLE IF NOT EXISTS `khachhang` (
  `idKhachHang` int NOT NULL,
  `TenKhachHang` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `DiaChi` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `EmailKH` varchar(50) DEFAULT NULL,
  `Sdt` char(10) DEFAULT NULL,
  `idRole` int NOT NULL,
  PRIMARY KEY (`idKhachHang`),
  KEY `idRole` (`idRole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`idKhachHang`, `TenKhachHang`, `DiaChi`, `EmailKH`, `Sdt`, `idRole`) VALUES
(13, 'teocute', 'fasdfasdf', 'admin@gmail.com', '123456', 2),
(14, 'teocute', 'qqeqwer', 'admin@gmail.com', '123456', 2);

-- --------------------------------------------------------

--
-- Table structure for table `loaisanpham`
--

DROP TABLE IF EXISTS `loaisanpham`;
CREATE TABLE IF NOT EXISTS `loaisanpham` (
  `idLoaiSP` int NOT NULL,
  `TenLoai` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `MoTa` text,
  `idDanhMuc` int NOT NULL,
  PRIMARY KEY (`idLoaiSP`),
  KEY `idDanhMuc` (`idDanhMuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `loaisanpham`
--

INSERT INTO `loaisanpham` (`idLoaiSP`, `TenLoai`, `MoTa`, `idDanhMuc`) VALUES
(1, 'Thức Ăn Cho Cún Con', 'thức ăn dành cho cún con dưới 6 tháng tuổi.', 1),
(2, 'Thức Ăn Cho Mèo Con', 'thức ăn dành cho mèo con dưới 6 tháng tuổi', 1),
(3, 'Thức Ăn Cho Cún', 'Thức ăn dành cho cún trên 6 tháng tuổi', 1),
(4, 'Thức Ăn Cho Mèo', 'thức ăn dành cho mèo trên 6 tháng tuổi', 1),
(5, 'Snack Cho Cún', 'đồ ăn nhẹ dành cho cún\r\n', 1),
(6, 'Snack Cho Mèo', 'thức ăn nhẹ dành cho mèo', 1),
(7, 'Snack Cho Chim', 'thức ăn nhẹ dành cho chim', 1),
(8, 'Nhà Ở', 'nhà ngủ dành cho thú cưng', 2),
(9, 'Áo ', 'áo mặc dành cho thú cưng', 4),
(10, 'Vòng Đeo Cổ', 'vòng đeo cổ dành cho thú cưng', 4);

-- --------------------------------------------------------

--
-- Table structure for table `nhacungcap`
--

DROP TABLE IF EXISTS `nhacungcap`;
CREATE TABLE IF NOT EXISTS `nhacungcap` (
  `idNhaCungCap` int NOT NULL,
  `TenNhaCungCap` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `DiaChi` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Sdt` char(10) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idNhaCungCap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`idNhaCungCap`, `TenNhaCungCap`, `DiaChi`, `Sdt`, `Email`) VALUES
(1, 'pethouse', 'newyork, USA', '0909123456', 'pethouse@example.com'),
(2, 'nonmfactory', '123 thủ đức tp.Hồ Chí Minh', '0909123456', 'nonmfactory@example.com'),
(3, 'nonmfoodie', '123 hóc môn tp.Hồ Chí Minh', '0909123456', 'nonmfoodie@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `nhasanxuat`
--

DROP TABLE IF EXISTS `nhasanxuat`;
CREATE TABLE IF NOT EXISTS `nhasanxuat` (
  `idNhaSanXuat` int NOT NULL,
  `TenNhaSanXuat` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `QuocGia` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`idNhaSanXuat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhasanxuat`
--

INSERT INTO `nhasanxuat` (`idNhaSanXuat`, `TenNhaSanXuat`, `QuocGia`) VALUES
(1, 'nonmgroup', 'Việt Nam'),
(2, 'pethouse', 'USA');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `idRole` int NOT NULL,
  `TenRole` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`idRole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`idRole`, `TenRole`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

DROP TABLE IF EXISTS `sanpham`;
CREATE TABLE IF NOT EXISTS `sanpham` (
  `idSanPham` varchar(50) NOT NULL,
  `TenSanPham` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `SoLuong` int DEFAULT NULL,
  `MoTa` text,
  `Gia` decimal(15,2) DEFAULT NULL,
  `idDanhMuc` int NOT NULL,
  `idLoaiSP` int NOT NULL,
  `idNhaCungCap` int NOT NULL,
  `idNhaSanXuat` int NOT NULL,
  `Image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`idSanPham`),
  KEY `idDanhMuc` (`idDanhMuc`),
  KEY `idLoaiSP` (`idLoaiSP`),
  KEY `idNhaCungCap` (`idNhaCungCap`),
  KEY `idNhaSanXuat` (`idNhaSanXuat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`idSanPham`, `TenSanPham`, `SoLuong`, `MoTa`, `Gia`, `idDanhMuc`, `idLoaiSP`, `idNhaCungCap`, `idNhaSanXuat`, `Image`) VALUES
('PK01', 'hoodie xám', 1, 'áo hoodie mặc vào là ấm dành cho cún cưng', 180000.00, 4, 9, 2, 1, '1765597420_blog-lg1.jpg'),
('PK02', 'áo xanh xọc ngang', 1, 'áo mặc vào nhìn dễ cưng dành cho cún cưng', 180000.00, 4, 9, 2, 1, '1765597411_item2.jpg'),
('PK03', 'áo hồng', 1, 'áo mặc vào là đẹp gái dành cho cún cưng', 180000.00, 4, 9, 2, 1, '1765597394_item3.jpg'),
('PK04', 'pyjama đen', 1, 'đồ ngủ mặc vào là rơi vào giấc ngủ dành cho cún cưng', 180000.00, 4, 9, 2, 1, '1765597382_blog-lg2.jpg'),
('PK05', 'hoodie vàng', 1, 'áo ấm màu vàng dành cho thú cưng', 180000.00, 4, 9, 2, 1, '1765597370_blog-lg3.jpg'),
('PK06', 'pyjama chuối', 1, 'áo ngủ hình chái chuối dành cho thú cưng', 180000.00, 4, 9, 2, 1, '1765597358_item8.jpg'),
('TA01', 'snack cho mèo', 1, 'thức ăn dùng để dụ mấy bé mèo hư', 180000.00, 1, 6, 3, 1, '1765597342_item16.jpg'),
('TA02', 'pa-tê cho cún', 1, 'pa-tê cún ăn vào là mê', 180000.00, 1, 3, 3, 1, '1765597322_item6.jpg'),
('TA03', 'snack cho cún', 1, 'snack cún ăn vô hạn ăn là ghiền', 180000.00, 1, 5, 3, 1, '1765597294_1765597207_item9.jpg'),
('TA04', 'pa-tê cho cún con', 1, 'an toàn dành cho cún con uy tín', 180000.00, 1, 1, 3, 1, '1765597285_item6.jpg'),
('TA05', 'snack cho chim', 1, 'snack chim ăn vô hạn ăn là ghiền', 180000.00, 1, 7, 3, 1, '1765597256_1765579567_tetra (1).png'),
('TA06', 'snack cho chim', 1, 'snack chim ăn vô hạn ăn là ghiền', 180000.00, 1, 1, 3, 1, '1765597240_item14.jpg'),
('TA07', 'patê cho cún trưởng thành', 1, 'pa_tê cho cún ăn vào là mê', 180000.00, 1, 3, 3, 1, '1765597224_item15.jpg'),
('TA08', 'snack cho mèo', 1, 'thức ăn dụ mèo', 180000.00, 1, 6, 3, 1, '1765597308_item11.jpg'),
('TA09', 'patê vị cá ngừ dành cho cún', 1, 'pa_tê cho cún ăn vào là mê', 180000.00, 1, 3, 3, 1, '1765597183_item10.jpg'),
('VD01', 'nhà nghỉ dành cho mèo', 1, 'chổ dưng chân dành cho hoàng thượng', 1800000.00, 2, 8, 2, 1, '1765597152_item5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int NOT NULL AUTO_INCREMENT,
  `tenUser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `matkhau` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idRole` int NOT NULL,
  PRIMARY KEY (`idUser`),
  KEY `idKhachHang` (`idRole`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`idUser`, `tenUser`, `matkhau`, `Email`, `idRole`) VALUES
(12, 'admin', '$2y$10$KVWDF0mhCu882A4c3TIfKuQ/LsxXtwZ8NEfcL0aTDBZk2Hg3GfuAO', 'admin@gmail.com', 1),
(15, 'Khách hàng', '$2y$10$4Q0EtEmh8dmHkxDrYnvcHuwsOcKyKMjPpfUPECF0Z6Cb7WOs8ROAi', 'khachhang@gmail.com', 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`idKhachHang`) REFERENCES `khachhang` (`idKhachHang`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD CONSTRAINT `khachhang_ibfk_1` FOREIGN KEY (`idRole`) REFERENCES `role` (`idRole`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `loaisanpham`
--
ALTER TABLE `loaisanpham`
  ADD CONSTRAINT `loaisanpham_ibfk_1` FOREIGN KEY (`idDanhMuc`) REFERENCES `danhmuc` (`idDanhMuc`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`idDanhMuc`) REFERENCES `danhmuc` (`idDanhMuc`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`idLoaiSP`) REFERENCES `loaisanpham` (`idLoaiSP`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanpham_ibfk_3` FOREIGN KEY (`idNhaCungCap`) REFERENCES `nhacungcap` (`idNhaCungCap`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `sanpham_ibfk_4` FOREIGN KEY (`idNhaSanXuat`) REFERENCES `nhasanxuat` (`idNhaSanXuat`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`idRole`) REFERENCES `role` (`idRole`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
