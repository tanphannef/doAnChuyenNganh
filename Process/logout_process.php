<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Xóa tất cả dữ liệu session
$_SESSION = array();

// 2. Hủy bỏ cookie session (để đảm bảo không còn session ID trên client)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hủy session
session_destroy();

// 4. Chuyển hướng người dùng về trang chủ hoặc trang đăng nhập
header('Location: ../index.php'); // Hoặc ../Views/login.php
exit();
?>