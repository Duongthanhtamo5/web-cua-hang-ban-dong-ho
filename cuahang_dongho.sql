-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 18, 2026 lúc 10:07 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cuahang_dongho`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_hoa_don`
--

CREATE TABLE `chi_tiet_hoa_don` (
  `id` int(11) NOT NULL,
  `hoa_don_id` int(11) DEFAULT NULL,
  `san_pham_id` int(11) DEFAULT NULL,
  `so_luong` int(11) DEFAULT NULL,
  `gia_ban_luc_do` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_hoa_don`
--

INSERT INTO `chi_tiet_hoa_don` (`id`, `hoa_don_id`, `san_pham_id`, `so_luong`, `gia_ban_luc_do`) VALUES
(1, 2, 1, 1, 3500000.00),
(2, 2, 2, 1, 9800000.00),
(3, 3, 2, 2, 9800000.00),
(4, 3, 1, 1, 3500000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_nhap_hang`
--

CREATE TABLE `chi_tiet_nhap_hang` (
  `id` int(11) NOT NULL,
  `nhap_hang_id` int(11) DEFAULT NULL,
  `san_pham_id` int(11) DEFAULT NULL,
  `so_luong_nhap` int(11) DEFAULT NULL,
  `gia_nhap` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `id` int(11) NOT NULL,
  `ten_danh_muc` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`id`, `ten_danh_muc`, `created_at`) VALUES
(1, 'Đồng hồ Nam', '2026-05-18 03:52:54'),
(2, 'Đồng hồ Nữ', '2026-05-18 03:52:54'),
(3, 'Đồng hồ Thể thao', '2026-05-18 03:52:54'),
(4, 'Đồng hồ Cơ / Automatic', '2026-05-18 03:52:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `he_thong_log`
--

CREATE TABLE `he_thong_log` (
  `id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) DEFAULT NULL,
  `hanh_dong` varchar(255) NOT NULL,
  `chi_tiet` text NOT NULL,
  `bang_lien_quan` varchar(100) DEFAULT NULL,
  `thoi_gian` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `he_thong_log`
--

INSERT INTO `he_thong_log` (`id`, `nguoi_dung_id`, `hanh_dong`, `chi_tiet`, `bang_lien_quan`, `thoi_gian`) VALUES
(1, 6, 'Cập nhật', 'Đã thay đổi thông tin/hình ảnh chi tiết của mẫu đồng hồ ID #4 (Tissot Le Locle Powermatic 80)', 'san_pham', '2026-05-18 12:15:26'),
(2, 6, 'Cập nhật', 'Đã thay đổi thông tin/hình ảnh chi tiết của mẫu đồng hồ ID #3 (Apple Watch Series 9)', 'san_pham', '2026-05-18 12:16:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) DEFAULT NULL,
  `nhan_vien_id` int(11) DEFAULT NULL,
  `tong_tien` decimal(15,2) DEFAULT NULL,
  `loai_hoa_don` varchar(20) DEFAULT NULL,
  `trang_thai` varchar(30) DEFAULT NULL,
  `ly_do_huy` text DEFAULT NULL,
  `dia_chi_giao` text DEFAULT NULL,
  `ngay_lap` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`id`, `nguoi_dung_id`, `nhan_vien_id`, `tong_tien`, `loai_hoa_don`, `trang_thai`, `ly_do_huy`, `dia_chi_giao`, `ngay_lap`) VALUES
(1, 2, NULL, 9800000.00, 'COD', 'Da huy', NULL, 'dsfgdfgfhdfsds', '2026-05-17 02:27:44'),
(2, 2, NULL, 13300000.00, 'COD', 'Cho xac nhan', NULL, 'sfgcvbvbvn', '2026-05-17 09:19:32'),
(3, 2, NULL, 23100000.00, NULL, 'Da thanh toan', NULL, NULL, '2026-05-17 10:07:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `log_hoat_dong`
--

CREATE TABLE `log_hoat_dong` (
  `id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) DEFAULT NULL,
  `hanh_dong` varchar(255) DEFAULT NULL,
  `bang_lien_quan` varchar(50) DEFAULT NULL,
  `du_lieu_cu` text DEFAULT NULL,
  `du_lieu_moi` text DEFAULT NULL,
  `thoi_gian` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mat_khau` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `dia_chi` text DEFAULT NULL,
  `vai_tro` varchar(30) DEFAULT NULL,
  `trang_thai` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau`, `so_dien_thoai`, `dia_chi`, `vai_tro`, `trang_thai`, `created_at`) VALUES
(2, 'Dương Thanh Tâm', 'dtam10181@gmail.com', '$2y$12$CaVTwNj/ihO4VMgdnkM6jOP7FaMnVWPymZeHvUHW0xSq7EP7p9UH6', '0352077311', NULL, 'khach_hang', 1, '2026-05-17 06:42:13'),
(6, 'admin', 'admin@gmail.com', '$2y$12$IgrDRZlJOtsxonPxn.nBSOJofYCdfHZe49sqmG8bHCowEfi.nnM.S', '0123456', NULL, 'admin', 1, '2026-05-17 07:13:01'),
(7, 'NV bán hàng', 'nvbanhang@gmail.com', '$2y$12$LewVBnK/hap.lMAfXCa6GeOMjlJ8xIF9I35APjn4QCWwiAEnObXyq', '12345', NULL, 'nhanvien_banhang', 1, '2026-05-17 07:13:48'),
(8, 'NV kho', 'nvkho@gmail.com', '$2y$12$Esw6zu73vhKcNJXuIClWHulRlqqP3GLVgXUI.ut7ur1ELwsdh91jC', '244355657765', NULL, 'nhanvien_kho', 1, '2026-05-17 07:14:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhap_hang`
--

CREATE TABLE `nhap_hang` (
  `id` int(11) NOT NULL,
  `nha_cung_cap` varchar(255) DEFAULT NULL,
  `nhan_vien_kho_id` int(11) DEFAULT NULL,
  `tong_tien_nhap` decimal(15,2) DEFAULT NULL,
  `ngay_nhap` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int(11) NOT NULL,
  `danh_muc_id` int(11) DEFAULT NULL,
  `ten_san_pham` varchar(255) DEFAULT NULL,
  `thuong_hieu` varchar(100) DEFAULT NULL,
  `gia_ban` decimal(15,2) DEFAULT NULL,
  `gia_von` decimal(15,2) DEFAULT 0.00,
  `so_luong_kho` int(11) DEFAULT 0,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`id`, `danh_muc_id`, `ten_san_pham`, `thuong_hieu`, `gia_ban`, `gia_von`, `so_luong_kho`, `hinh_anh`, `mo_ta`, `created_at`) VALUES
(1, 1, 'Casio Edifice EFV-540D', 'Casio', 3500000.00, 2100000.00, 10, 'casio_edifice.jpg', 'Đồng hồ nam dây kim loại, chống nước 100m.', '2026-05-17 03:09:55'),
(2, 1, 'Orient Sun & Moon Gen 3', 'Orient', 9800000.00, 6500000.00, 5, 'orient_sun_moon.jpg', 'Đồng hồ cơ tự động, kính Sapphire nguyên khối.', '2026-05-17 03:09:55'),
(3, 3, 'Apple Watch Series 9', 'Apple', 10500000.00, 8000000.00, 15, '1779131795_1276435436.jpeg', 'Đồng hồ thông minh hỗ trợ sức khỏe và tập luyện.', '2026-05-17 03:09:55'),
(4, 1, 'Tissot Le Locle Powermatic 80', 'Tissot', 15000000.00, 11000000.00, 3, '1779131725_t006.407.11.043.00.webp', 'Đẳng cấp đồng hồ Thụy Sỹ, trữ cót lên đến 80 giờ.', '2026-05-17 03:09:55');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hoa_don_id` (`hoa_don_id`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- Chỉ mục cho bảng `chi_tiet_nhap_hang`
--
ALTER TABLE `chi_tiet_nhap_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nhap_hang_id` (`nhap_hang_id`),
  ADD KEY `san_pham_id` (`san_pham_id`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `he_thong_log`
--
ALTER TABLE `he_thong_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hethonglog_nguoidung` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hoadon_nhanvien` (`nhan_vien_id`);

--
-- Chỉ mục cho bảng `log_hoat_dong`
--
ALTER TABLE `log_hoat_dong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_loghoatdong_nguoidung` (`nguoi_dung_id`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `nhap_hang`
--
ALTER TABLE `nhap_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nhaphang_nhanvien` (`nhan_vien_kho_id`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `danh_muc_id` (`danh_muc_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_nhap_hang`
--
ALTER TABLE `chi_tiet_nhap_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `he_thong_log`
--
ALTER TABLE `he_thong_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `log_hoat_dong`
--
ALTER TABLE `log_hoat_dong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `nhap_hang`
--
ALTER TABLE `nhap_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  ADD CONSTRAINT `chi_tiet_hoa_don_ibfk_1` FOREIGN KEY (`hoa_don_id`) REFERENCES `hoa_don` (`id`),
  ADD CONSTRAINT `chi_tiet_hoa_don_ibfk_2` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`);

--
-- Các ràng buộc cho bảng `chi_tiet_nhap_hang`
--
ALTER TABLE `chi_tiet_nhap_hang`
  ADD CONSTRAINT `chi_tiet_nhap_hang_ibfk_1` FOREIGN KEY (`nhap_hang_id`) REFERENCES `nhap_hang` (`id`),
  ADD CONSTRAINT `chi_tiet_nhap_hang_ibfk_2` FOREIGN KEY (`san_pham_id`) REFERENCES `san_pham` (`id`);

--
-- Các ràng buộc cho bảng `he_thong_log`
--
ALTER TABLE `he_thong_log`
  ADD CONSTRAINT `fk_hethonglog_nguoidung` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD CONSTRAINT `fk_hoadon_nhanvien` FOREIGN KEY (`nhan_vien_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `log_hoat_dong`
--
ALTER TABLE `log_hoat_dong`
  ADD CONSTRAINT `fk_loghoatdong_nguoidung` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `nhap_hang`
--
ALTER TABLE `nhap_hang`
  ADD CONSTRAINT `fk_nhaphang_nhanvien` FOREIGN KEY (`nhan_vien_kho_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`danh_muc_id`) REFERENCES `danh_muc` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
