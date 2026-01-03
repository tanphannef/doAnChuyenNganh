<?php
session_start();
require_once "../Models/db.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idDanhMuc = $_POST['idDanhMuc'] ?? null;
    $TenDanhMuc = $_POST['TenDanhMuc'] ?? '';

    if (empty($TenDanhMuc) || empty($idDanhMuc)) {
        $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
        header('Location: ../Views/add_danhmuc.php');
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        // 1. SỬ DỤNG PREPARED STATEMENTS (An toàn khỏi SQL Injection)
        $sql = "INSERT INTO danhmuc (idDanhMuc, TenDanhMuc) VALUES (:idDanhMuc, :TenDanhMuc)";
        $stmt = $pdo->prepare($sql);

        // 2. Thực thi truy vấn
        $stmt->execute([
            'idDanhMuc' => $idDanhMuc,
            'TenDanhMuc' => $TenDanhMuc
        ]);

        // Thành công: Điều hướng
        header('Location: ../Views/list_danhmuc.php');
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi thêm danh mục: ID có thể đã tồn tại hoặc lỗi hệ thống.";
        error_log("Lỗi PDO thêm danh mục: " . $e->getMessage());
        header('Location: ../Views/add_danhmuc.php');
        exit();
    }
} else {
    header('Location: ../Views/add_danhmuc.php');
    exit();
}
?>