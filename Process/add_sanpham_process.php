<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Lấy dữ liệu form
    $idSanPham = $_POST['idSanPham'] ?? '';
    $TenSanPham = $_POST['TenSanPham'] ?? '';
    $SoLuong = $_POST['SoLuong'] ?? 0;
    $MoTa = $_POST['MoTa'] ?? null;
    $Gia = $_POST['Gia'] ?? 0;
    $idDanhMuc = $_POST['idDanhMuc'] ?? null;
    $idLoaiSP = $_POST['idLoaiSP'] ?? null;
    $idNhaCungCap = $_POST['idNhaCungCap'] ?? null;
    $idNhaSanXuat = $_POST['idNhaSanXuat'] ?? null;
    
    // Ví dụ về logic upload ảnh (chỉ là placeholder)
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // ... Logic xử lý và lưu file vào thư mục public/images
        // Giả định: $imagePath = '/public/images/ten_file_da_luu.jpg';
        $imagePath = 'path/to/uploaded/image.jpg'; 
    }

    if (empty($idSanPham) || empty($TenSanPham) || empty($idDanhMuc) || empty($idLoaiSP)) {
        $_SESSION['error'] = "Vui lòng điền đầy đủ các trường bắt buộc.";
        header('Location: ../Views/add_sanpham.php');
        exit();
    }
    
    try {
        $pdo = get_pdo_connection();

        // Chuẩn bị truy vấn INSERT an toàn
        $sql = "INSERT INTO sanpham (idSanPham, TenSanPham, SoLuong, MoTa, Gia, idDanhMuc, idLoaiSP, idNhaCungCap, idNhaSanXuat, image) 
                VALUES (:idSP, :tenSP, :soLuong, :moTa, :gia, :idDm, :idLsp, :idNcc, :idNsx, :image)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'idSP' => $idSanPham,
            'tenSP' => $TenSanPham,
            'soLuong' => $SoLuong,
            'moTa' => $MoTa,
            'gia' => $Gia,
            'idDm' => $idDanhMuc,
            'idLsp' => $idLoaiSP,
            'idNcc' => $idNhaCungCap,
            'idNsx' => $idNhaSanXuat,
            'image' => $imagePath // Lưu đường dẫn ảnh vào DB
        ]);

        $_SESSION['message'] = "Thêm sản phẩm thành công.";
        header('Location: ../Views/list_sanpham.php'); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi thêm sản phẩm: ID có thể đã tồn tại hoặc lỗi DB.";
        header('Location: ../Views/add_sanpham.php');
        exit();
    }
} else {
    header('Location: ../Views/add_sanpham.php');
    exit();
}
?>