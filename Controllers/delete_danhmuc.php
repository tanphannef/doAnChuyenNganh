<?php
// File: delete_danhmuc.php (Controller)
session_start();

// Đảm bảo đường dẫn đến db.php là chính xác.
// Nếu file này nằm cùng cấp với list_danhmuc.php (trong Views/ hoặc Admin/), 
// thì đường dẫn là:
require_once "../Models/db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    // 1. Lấy ID từ URL
    $idDanhMuc = $_GET['id'] ?? null;
    
    // 2. Kiểm tra ID có hợp lệ hay không
    if (empty($idDanhMuc) || !is_numeric($idDanhMuc)) {
        $_SESSION['error'] = "ID danh mục không hợp lệ.";
        header('Location: ../Views/list_danhmuc.php'); // Điều hướng về trang danh sách
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        // 3. Sử dụng Prepared Statement để xóa (An toàn)
        $sql = "DELETE FROM danhmuc WHERE idDanhMuc = :id";
        $stmt = $pdo->prepare($sql);
        
        // Thực thi truy vấn với tham số
        $stmt->execute(['id' => $idDanhMuc]);
        
        // Kiểm tra số lượng dòng bị ảnh hưởng
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Danh mục có ID: $idDanhMuc đã được xóa thành công.";
        } else {
            $_SESSION['error'] = "Không tìm thấy danh mục có ID: $idDanhMuc để xóa.";
        }
        
    } catch (PDOException $e) {
        // Xử lý lỗi (ví dụ: lỗi khóa ngoại - Foreign Key Constraint)
        // Nếu danh mục đang được sử dụng bởi sản phẩm khác, việc xóa sẽ thất bại.
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Không thể xóa danh mục này vì nó đang được sử dụng bởi các Sản phẩm khác.";
        } else {
            $_SESSION['error'] = "Lỗi hệ thống khi xóa danh mục.";
            error_log("Lỗi PDO xóa danh mục: " . $e->getMessage());
        }
    }
    
    // 4. Điều hướng về trang danh sách
    header('Location: ../Views/list_danhmuc.php');
    exit();
} else {
    // Nếu truy cập file bằng POST hoặc không có tham số
    header('Location: ../Views/list_danhmuc.php');
    exit();
}
?>