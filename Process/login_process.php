<?php
// File: Controllers/login_process.php

// 1. KHỞI TẠO SESSION AN TOÀN
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. CHẶN TRUY CẬP TRỰC TIẾP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Views/login.php'); 
    exit();
}

// 3. NHÚNG MODEL VÀ KẾT NỐI DB
require_once "../Models/auth.php"; 
require_once "../Models/db.php"; 

// 4. LẤY DỮ LIỆU TỪ FORM (Đã thêm trim() cho mật khẩu)
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['matkhau'] ?? '');

// 5. GỌI HÀM XỬ LÝ TRONG MODEL
$login_result = loginUser($email, $password); 

// KIỂM TRA KẾT QUẢ ĐĂNG NHẬP
if ($login_result === true) {
    
    // --- KHỐI XỬ LÝ CHUYỂN HƯỚNG THEO ROLE ĐÃ HOÀN THIỆN ---
    
    // Lấy role từ Session (Đã được lưu trong Models/auth.php)
    $role = $_SESSION['user_role'] ?? 0; 
    
    $_SESSION['message'] = "Đăng nhập thành công!";

    // Giả định: 1 là Admin, 2 là User/Client
    if ($role == 1) {
        // ADMIN: Chuyển hướng đến trang quản trị (Thường nằm trong thư mục 'admin')
        header('Location: ../Views/dashboard.php'); 
        exit();
    } else if ($role == 2) {
        // USER/CLIENT: Chuyển hướng đến trang chủ hoặc trang dành cho khách hàng
        header('Location: ../index.php'); // Chuyển về trang chủ chính
        exit();
    } else {
        // Trường hợp khẩn cấp (Role không xác định), chuyển về trang chủ
        header('Location: ../index.php');
        exit();
    }
    // --- KẾT THÚC CHUYỂN HƯỚNG ---

} else {
    // THẤT BẠI: Thiết lập thông báo lỗi và quay về trang đăng nhập
    $_SESSION['error'] = $login_result; 
    header('Location: ../Views/login.php');
    exit();
}
?>