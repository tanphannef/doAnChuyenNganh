<?php
    include "../Public/Access/dboard/header.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // 1. KIỂM TRA ĐÃ ĐĂNG NHẬP CHƯA
    // Chúng ta dùng $_SESSION['user_id'] đã lưu trong Models/auth.php
    if (!isset($_SESSION['user_id'])) {
        // Chưa đăng nhập, chuyển hướng về trang login
        header("Location: ../login.php"); 
        exit();
    }
    
    // 2. KIỂM TRA QUYỀN TRUY CẬP (Giả định Admin là idRole = 1)
    if ($_SESSION['user_role'] != 1) { 
        // Nếu đã đăng nhập nhưng KHÔNG phải Admin, chuyển về trang chủ hoặc báo lỗi
        $_SESSION['error'] = "Bạn không có quyền truy cập trang quản trị.";
        header("Location: ../index.php"); 
        exit();
    }

    if(isset($_GET['act'])){
        switch($_GET['act']){
            case 'quanlysanpham':
                include './quanlysanpham.php';
                break;
            case 'quanlynguoncung':
                include './quanlynguoncung.php';
                break;
            case 'quanlydonhang':
                include './quanlydonhang.php';
                break;
            default:
                include '../Public/Access/dboard/maincontent.php' ;
                break;
        }
    } else{
        include '../Public/Access/dboard/maincontent.php' ;
    }

    include "../Public/Access/dboard/footer.php";
?>