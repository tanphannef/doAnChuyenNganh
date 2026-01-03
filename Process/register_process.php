<?php
// File: Controllers/register_process.php

// 1. KHỞI TẠO SESSION AN TOÀN
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. CHẶN TRUY CẬP TRỰC TIẾP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Views/register.php');
    exit();
}

// 3. NHÚNG MODEL VÀ KẾT NỐI DB
require_once "../Models/auth.php"; 
require_once "../Models/db.php"; // File chứa hàm get_pdo_connection()

// 4. LẤY DỮ LIỆU TỪ FORM (Lọc dữ liệu)
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// 5. GỌI HÀM XỬ LÝ TRONG MODEL
$register_result = registerUser($email, $password, $name); 

if ($register_result === true) {
    // THÀNH CÔNG: Chuyển hướng đến trang đăng nhập
    $_SESSION['message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
    header('Location: ../Views/login.php');
    exit();
} else {
    // THẤT BẠI: Thiết lập thông báo lỗi và quay về trang đăng ký
    $_SESSION['error'] = $register_result; // Model trả về thông báo lỗi
    header('Location: ../Views/register.php');
    exit();
}
?>