<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $idSanPham = $_GET['id'] ?? null;
    
    if (empty($idSanPham)) {
        $_SESSION['error'] = "Thiếu ID sản phẩm để xóa.";
        header('Location: ../Views/list_sanpham.php'); 
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        // Sử dụng Prepared Statement để xóa (An toàn)
        $sql = "DELETE FROM sanpham WHERE idSanPham = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $idSanPham]);
        
        if ($stmt->rowCount() > 0) {
            // Thường thì bạn sẽ xóa cả file ảnh liên quan ở đây
            $_SESSION['message'] = "Sản phẩm có ID: $idSanPham đã được xóa thành công.";
        } else {
            $_SESSION['error'] = "Không tìm thấy sản phẩm này để xóa.";
        }
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống khi xóa sản phẩm: " . $e->getMessage();
        error_log("Lỗi PDO xóa sản phẩm: " . $e->getMessage());
    }
    
    header('Location: ../Views/list_sanpham.php');
    exit();
} else {
    header('Location: ../Views/list_sanpham.php');
    exit();
}
?>