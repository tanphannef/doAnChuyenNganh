<?php
// File: delete_loaisp.php (Controller - Giả định nằm trong Controllers/)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../Models/db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $idLoaiSP = $_GET['id'] ?? null;
    
    if (empty($idLoaiSP)) {
        $_SESSION['error'] = "Thiếu ID loại sản phẩm để xóa.";
        header('Location: ../Views/list_loaisp.php'); 
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        // Sử dụng Prepared Statement để xóa (An toàn)
        $sql = "DELETE FROM loaisanpham WHERE idLoaiSP = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $idLoaiSP]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Loại sản phẩm có ID: $idLoaiSP đã được xóa thành công.";
        } else {
            $_SESSION['error'] = "Không tìm thấy loại sản phẩm này để xóa.";
        }
        
    } catch (PDOException $e) {
        // Xử lý lỗi Khóa ngoại (nếu loại sản phẩm đang được dùng bởi sản phẩm khác)
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Không thể xóa loại sản phẩm này vì nó đang được liên kết với Sản phẩm khác.";
        } else {
            $_SESSION['error'] = "Lỗi hệ thống khi xóa loại sản phẩm.";
            error_log("Lỗi PDO xóa loại sản phẩm: " . $e->getMessage());
        }
    }
    
    header('Location: ../Views/list_loaisp.php');
    exit();
} else {
    header('Location: ../Views/list_loaisp.php');
    exit();
}
?>