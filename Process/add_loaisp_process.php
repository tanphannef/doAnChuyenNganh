<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Lấy dữ liệu và làm sạch
    $idLoaiSP = $_POST['idLoaiSP'] ?? '';
    $TenLoai = $_POST['TenLoai'] ?? '';
    $MoTa = $_POST['MoTa'] ?? null;
    $idDanhMuc = $_POST['idDanhMuc'] ?? null; // ID Danh mục được chọn từ dropdown

    if (empty($idLoaiSP) || empty($TenLoai) || empty($idDanhMuc)) {
        $_SESSION['error'] = "Vui lòng điền đầy đủ các trường bắt buộc.";
        // Điều hướng về trang form
        header('Location: ../Views/add_loaisp.php'); 
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        // 1. SỬ DỤNG PREPARED STATEMENTS (An toàn khỏi SQL Injection)
        $sql = "INSERT INTO loaisanpham (idLoaiSP, TenLoai, MoTa, idDanhMuc) 
                VALUES (:idLoaiSP, :TenLoai, :MoTa, :idDanhMuc)";
        $stmt = $pdo->prepare($sql);

        // 2. Thực thi truy vấn với các tham số
        $stmt->execute([
            'idLoaiSP' => $idLoaiSP,
            'TenLoai' => $TenLoai,
            'MoTa' => $MoTa,
            'idDanhMuc' => $idDanhMuc
        ]);

        // Thành công: Điều hướng về trang danh sách
        $_SESSION['message'] = "Thêm loại sản phẩm thành công.";
        header('Location: ../Views/list_loaisp.php'); 
        exit();

    } catch (PDOException $e) {
        // Xử lý lỗi (ví dụ: trùng IDLoaiSP, lỗi khóa ngoại, lỗi DB)
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Lỗi: ID Loại Sản phẩm đã tồn tại hoặc ID Danh mục không hợp lệ.";
        } else {
            $_SESSION['error'] = "Lỗi hệ thống khi thêm loại sản phẩm.";
            error_log("Lỗi PDO thêm loại sản phẩm: " . $e->getMessage());
        }
        header('Location: ../Views/add_loaisp.php');
        exit();
    }
} else {
    // Nếu truy cập trực tiếp mà không phải POST
    header('Location: ../Views/add_loaisp.php');
    exit();
}
?>