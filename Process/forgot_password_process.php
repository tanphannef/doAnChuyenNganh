<?php
// File: Controllers/forgot_password_process.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Views/forgot_password.php');
    exit();
}

require_once "../Models/auth.php"; 
require_once "../Models/db.php"; // Cần cho kết nối DB
// NOTE: Bạn có thể cần một thư viện gửi email (Ví dụ: PHPMailer)

$email = trim($_POST['email'] ?? '');

// Giả định hàm processPasswordReset là hàm trong Model
$result = processPasswordReset($email); 

if ($result === true) {
    // THÀNH CÔNG: Chuyển hướng về trang quên MK với thông báo thành công
    $_SESSION['message'] = "Yêu cầu đã được gửi! Vui lòng kiểm tra email của bạn để đặt lại mật khẩu.";
    header('Location: ../Views/forgot_password.php');
    exit();
} else {
    // THẤT BẠI: Thiết lập thông báo lỗi và quay về trang quên MK
    $_SESSION['error'] = $result; 
    header('Location: ../Views/forgot_password.php');
    exit();
}
?>