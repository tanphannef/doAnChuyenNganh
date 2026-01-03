<?php
// File: delete_nhasanxuat.php (Controller)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $idNhaSanXuat = $_GET['id'] ?? null;
    
    if (empty($idNhaSanXuat)) {
        $_SESSION['error'] = "Thiếu ID Nhà Sản Xuất để xóa.";
        header('Location: ../Views/list_nhasanxuat.php'); 
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        $sql = "DELETE FROM nhasanxuat WHERE idNhaSanXuat = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $idNhaSanXuat]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Nhà Sản Xuất đã được xóa thành công.";
        } else {
            $_SESSION['error'] = "Không tìm thấy Nhà Sản Xuất này để xóa.";
        }
        
    } catch (PDOException $e) {
        // Xử lý lỗi Khóa ngoại (nếu NSX đang được dùng bởi Sản phẩm)
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Không thể xóa Nhà Sản Xuất này vì nó đang được liên kết với Sản phẩm.";
        } else {
            $_SESSION['error'] = "Lỗi hệ thống khi xóa Nhà Sản Xuất.";
        }
    }
    
    header('Location: ../Views/list_nhasanxuat.php');
    exit();
} else {
    header('Location: ../Views/list_nhasanxuat.php');
    exit();
}
?>