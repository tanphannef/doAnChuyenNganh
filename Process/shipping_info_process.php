<?php
// File: Controllers/shipping_info_process.php
session_start();
require_once '../Models/khachhang_model.php'; 

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Views/login.php');
    exit();
}

$idKhachHang = $_SESSION['user_id'];
$tenKH = trim($_POST['tenKhachHang'] ?? '');
$diaChi = trim($_POST['diaChi'] ?? '');
$emailKH = trim($_POST['emailKH'] ?? '');
$sdt = trim($_POST['sdt'] ?? '');
$idRole = $_POST['idRole'] ?? 2;

if (empty($tenKH) || empty($diaChi) || empty($emailKH) || empty($sdt)) {
    $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin giao hàng.";
    header('Location: ../Views/shipping_info.php');
    exit();
}

$isExists = checkKhachHangExists($idKhachHang);

if (!$isExists) {
    // Nếu chưa có bản ghi trong bảng khachhang, THÊM MỚI
    if (!insertKhachHangInfo($idKhachHang, $tenKH, $diaChi, $emailKH, $sdt, $idRole)) {
        $_SESSION['error'] = "Lỗi hệ thống: Không thể lưu thông tin khách hàng.";
        header('Location: ../Views/shipping_info.php');
        exit();
    }
} else {
    // Nếu đã có bản ghi, CẬP NHẬT (bạn cần viết thêm hàm updateKhachHangInfo)
    // Tạm thời bỏ qua cập nhật, nhưng về lâu dài nên có hàm UPDATE
}

// Chuyển hướng đến trang Thanh toán/Kiểm tra Giỏ hàng
header('Location: ../Views/checkout.php');
exit();
?>