<?php
// File: add_nsx_process.php (Controller)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Giả định nằm trong Controllers/
require_once "../Models/db.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $idNhaSanXuat = $_POST['idNhaSanXuat'] ?? '';
    $TenNhaSanXuat = $_POST['TenNhaSanXuat'] ?? '';
    $QuocGia = $_POST['QuocGia'] ?? null;
    
    if (empty($idNhaSanXuat) || empty($TenNhaSanXuat)) {
        $_SESSION['error'] = "ID và Tên Nhà Sản Xuất không được để trống.";
        header('Location: ../Views/add_nhasanxuat.php');
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        // Chuẩn bị truy vấn INSERT an toàn
        $sql = "INSERT INTO nhasanxuat (idNhaSanXuat, TenNhaSanXuat, QuocGia) 
                VALUES (:idNsx, :tenNsx, :quocGia)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'idNsx' => $idNhaSanXuat,
            'tenNsx' => $TenNhaSanXuat,
            'quocGia' => $QuocGia
        ]);

        $_SESSION['message'] = "Thêm Nhà Sản Xuất thành công.";
        header('Location: ../Views/list_nhasanxuat.php'); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi thêm Nhà Sản Xuất: ID có thể đã tồn tại hoặc lỗi DB.";
        header('Location: ../Views/add_nhasanxuat.php');
        exit();
    }
} else {
    header('Location: ../Views/add_nhasanxuat.php');
    exit();
}
?>