<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// HÀM KẾT NỐI DATABASE (Ưu tiên qlpetshop)
if (!function_exists('get_pdo_connection')) {
    function get_pdo_connection() {
        try {
            return new PDO("mysql:host=localhost;dbname=qlpetshop;charset=utf8", "root", "");
        } catch (Exception $e) {
            try {
                return new PDO("mysql:host=localhost;dbname=shop_thu_cung;charset=utf8", "root", "");
            } catch (Exception $ex) { return null; }
        }
    }
}
$pdo = get_pdo_connection();

$sanPhamList = [];
$error = '';
if (!$pdo) {
    $error = "Không thể kết nối Database.";
} else {
    try {
        $sql = "SELECT sp.*, dm.TenDanhMuc, lsp.TenLoai, ncc.TenNhaCungCap, nsx.TenNhaSanXuat
                FROM sanpham sp
                JOIN danhmuc dm ON sp.idDanhMuc = dm.idDanhMuc
                JOIN loaisanpham lsp ON sp.idLoaiSP = lsp.idLoaiSP
                JOIN nhacungcap ncc ON sp.idNhaCungCap = ncc.idNhaCungCap
                JOIN nhasanxuat nsx ON sp.idNhaSanXuat = nsx.idNhaSanXuat
                ORDER BY sp.idSanPham DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $sanPhamList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Lỗi SQL: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý sản phẩm</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fc; padding: 20px; }
        .card { background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); overflow: hidden; max-width: 1200px; margin: 0 auto; }
        .card-header { background: #4e73df; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
        .btn-add { background: #1cc88a; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f3f9; padding: 12px; text-align: left; border-bottom: 2px solid #ddd; color: #333; }
        td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        tr:hover { background: #fafafa; }
        
        .img-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
        .price { color: #e74a3b; font-weight: bold; }
        .action-link { text-decoration: none; margin-right: 10px; font-weight: bold; }
        .edit { color: #f6c23e; }
        .delete { color: #e74a3b; }
        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 15px;
            background: #6c757d; /* Màu xám */
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-back:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
<a href="quanlysanpham.php" class="btn-back">
    &larr; Quay lại quản lý sản phẩm
</a>
<div class="card">
    <div class="card-header">
        <h3 style="margin:0;">📦 Danh Sách Sản Phẩm</h3>
        <a href="add_sanpham.php" class="btn-add"><i class="fas fa-plus"></i> Thêm Mới</a>
    </div>
    
    <?php if (!empty($error)): ?>
        <div style="padding: 15px; color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></div>
    <?php else: ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Thông tin</th>
                <th>Giá & Kho</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sanPhamList as $row): ?>
            <?php 
                // --- DÒNG 79 ĐÃ SỬA CHUẨN CÚ PHÁP VÀ TÊN CỘT 'Image' ---
                $tenAnh = $row['Image'] ?? 'default.png'; // Lấy tên file (chữ I HOA)
                $img_src = '';
                
                // Kiểm tra và xử lý đường dẫn
                if (!empty($tenAnh) && $tenAnh != '1') {
                    // Nếu là link http (có thể là tên file trong Database của bạn đã được format)
                    if (strpos($tenAnh, 'http') !== false || strpos($tenAnh, 'C:\\') !== false) {
                        // Tách tên file nếu là đường dẫn ổ đĩa (ví dụ: lấy 'hoodie.jpg' từ 'C:\...')
                        $parts = explode('\\', $tenAnh);
                        $final_file_name = end($parts);
                        $img_src = '../Public/images/' . $final_file_name;
                    } 
                    // Nếu là tên file hợp lệ (item5, hoodie.jpg)
                    else {
                        $img_src = '../Public/images/' . $tenAnh; 
                    }
                } else {
                    $img_src = 'https://via.placeholder.com/60?text=N/A';
                }
            ?>
            <tr>
                <td>#<?= htmlspecialchars($row['idSanPham']) ?></td>
                <td>
                    <img src="<?= $img_src ?>" class="img-thumb" onerror="this.src='https://via.placeholder.com/60?text=N/A'">
                </td>
                <td style="font-weight: bold; color: #4e73df;"><?= htmlspecialchars($row['TenSanPham']) ?></td>
                <td style="font-size: 13px; color: #666;">
                    DM: <?= htmlspecialchars($row['TenDanhMuc']) ?><br>
                    Loại: <?= htmlspecialchars($row['TenLoai']) ?><br>
                    NCC: <?= htmlspecialchars($row['TenNhaCungCap']) ?>
                </td>
                <td>
                    <div class="price"><?= number_format($row['Gia']) ?> đ</div>
                    <small>Kho: <?= $row['SoLuong'] ?></small>
                </td>
                <td>
                    <a href="../Controllers/edit_sanpham.php?id=<?= $row['idSanPham'] ?>" class="action-link edit">Sửa</a>
                    <a href="../Controllers/delete_sanpham.php?id=<?= $row['idSanPham'] ?>" class="action-link delete" onclick="return confirm('Xóa thật chứ?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php endif; ?>
</div>

</body>
</html>