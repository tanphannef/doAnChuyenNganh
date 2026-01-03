<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $idNhaCungCap = $_GET['id'] ?? null;
    
    if (empty($idNhaCungCap)) {
        $_SESSION['error'] = "Thiếu ID Nhà Cung Cấp để xóa.";
        header('Location: ../Views/list_nhacungcap.php'); 
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        $sql = "DELETE FROM nhacungcap WHERE idNhaCungCap = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $idNhaCungCap]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Nhà Cung Cấp đã được xóa thành công.";
        } else {
            $_SESSION['error'] = "Không tìm thấy Nhà Cung Cấp này để xóa.";
        }
        
    } catch (PDOException $e) {
        // Xử lý lỗi Khóa ngoại (nếu NCC đang được dùng bởi Sản phẩm)
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Không thể xóa Nhà Cung Cấp này vì nó đang được liên kết với Sản phẩm.";
        } else {
            $_SESSION['error'] = "Lỗi hệ thống khi xóa Nhà Cung Cấp.";
        }
    }
    
    header('Location: ../Views/list_nhacungcap.php');
    exit();
} else {
    header('Location: ../Views/list_nhacungcap.php');
    exit();
}
?>