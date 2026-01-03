<?php
// Controllers/product_controller.php
session_start();
ob_start();

// Kiểm tra và nhúng file Model (Cần đảm bảo file này tồn tại)
if (file_exists("../Models/db.php")) require_once "../Models/db.php"; 
if (file_exists("../Models/product_model.php")) require_once "../Models/product_model.php"; 

$act = $_GET['act'] ?? '';
$idSanPham = $_GET['id'] ?? null;

if ($act == 'detail' && !empty($idSanPham)) {
    
    // 1. GỌI MODEL: Lấy dữ liệu chi tiết sản phẩm
    // Giả định hàm getProductDetail đã có trong product_model.php và lấy đúng 1 sản phẩm theo ID
    // Cần đảm bảo hàm này trả về kết quả hoặc null/false nếu không tìm thấy
    $productDetail = getProductDetail($idSanPham); 

    if ($productDetail) {
        // 2. NHÚNG VIEW: Truyền biến $productDetail vào View
        include '../Views/single_product.php'; 
    } else {
        // Xử lý khi không tìm thấy sản phẩm
        $_SESSION['error'] = "Sản phẩm không tồn tại hoặc lỗi hệ thống.";
        header('Location: ../index.php');
        exit();
    }
} else if ($act == 'detail' && empty($idSanPham)) {
     // Xử lý khi có act=detail nhưng thiếu ID
     $_SESSION['error'] = "Thiếu mã sản phẩm để hiển thị chi tiết.";
     header('Location: ../index.php');
     exit();
} else {
    // Xử lý các act khác (nếu có) hoặc chuyển hướng về trang chủ
    header('Location: ../index.php');
    exit();
}

ob_end_flush();
?>