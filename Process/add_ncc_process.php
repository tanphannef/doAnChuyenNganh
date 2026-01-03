<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $idNhaCungCap = $_POST['idNhaCungCap'] ?? '';
    $TenNhaCungCap = $_POST['TenNhaCungCap'] ?? '';
    $DiaChi = $_POST['DiaChi'] ?? null;
    $Sdt = $_POST['Sdt'] ?? null;
    $Email = $_POST['Email'] ?? null;
    
    if (empty($idNhaCungCap) || empty($TenNhaCungCap)) {
        $_SESSION['error'] = "ID và Tên Nhà Cung Cấp không được để trống.";
        header('Location: ../Views/add_nhacungcap.php');
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        // Chuẩn bị truy vấn INSERT an toàn
        $sql = "INSERT INTO nhacungcap (idNhaCungCap, TenNhaCungCap, DiaChi, Sdt, Email) 
                VALUES (:idNcc, :tenNcc, :diaChi, :sdt, :email)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'idNcc' => $idNhaCungCap,
            'tenNcc' => $TenNhaCungCap,
            'diaChi' => $DiaChi,
            'sdt' => $Sdt,
            'email' => $Email
        ]);

        $_SESSION['message'] = "Thêm Nhà Cung Cấp thành công.";
        header('Location: ../Views/list_nhacungcap.php'); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi thêm Nhà Cung Cấp: ID có thể đã tồn tại hoặc lỗi DB.";
        header('Location: ../Views/add_nhacungcap.php');
        exit();
    }
} else {
    header('Location: ../Views/add_nhacungcap.php');
    exit();
}
?>