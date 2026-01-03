<?php
// File: list_loaisp.php (View - Giả định nằm trong Views/ hoặc Admin/)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sửa lỗi Redeclare: Dùng require_once để nạp hàm get_pdo_connection()
// Điều chỉnh đường dẫn này tùy thuộc vào vị trí của file
require_once "../Models/db.php"; 

$loaiSPList = [];
$errorMessage = '';

try {
    $pdo = get_pdo_connection();
    
    // Truy vấn kết hợp (JOIN) để lấy tên Danh mục
    $sql = "SELECT lsp.*, dm.TenDanhMuc 
            FROM loaisanpham lsp
            JOIN danhmuc dm ON lsp.idDanhMuc = dm.idDanhMuc
            ORDER BY lsp.idLoaiSP ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $loaiSPList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errorMessage = "Lỗi khi tải danh sách loại sản phẩm: " . $e->getMessage();
    error_log("Lỗi tải loại sản phẩm: " . $e->getMessage());
}

// Xác định đường dẫn Quay lại (Admin Dashboard/Quản lý Sản phẩm)
$backLink = "index_admin.php"; // Thay thế bằng đường dẫn chính xác của bạn

// Nếu cần quay lại Quản lý Sản phẩm
// $backLink = "../Views/quanlysanpham.php"; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Loại Sản phẩm</title>
    <style>
        /* 1. Reset và Font */
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fc; padding: 20px; }
        
        /* 2. Container (Card) */
        .card { 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
            overflow: hidden; 
            max-width: 1000px; /* Tăng chiều rộng để chứa nhiều cột hơn */
            margin: 0 auto; 
        }
        .card-header { 
            background: #4e73df; 
            color: white; 
            padding: 15px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }

        /* 3. Nút Thêm Mới */
        .btn-add { 
            background: #1cc88a; 
            color: white; 
            padding: 8px 15px; 
            text-decoration: none; 
            border-radius: 4px; 
            font-weight: bold; 
            transition: background 0.3s;
        }
        .btn-add:hover { background: #17a673; }

        /* 4. Nút Quay lại */
        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 15px;
            background: #6c757d; 
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-back:hover { background: #5a6268; }

        /* 5. Bảng */
        table { width: 100%; border-collapse: collapse; }
        th { 
            background: #f1f3f9; 
            padding: 12px; 
            text-align: left; 
            border-bottom: 2px solid #ddd; 
            color: #333; 
        }
        td { 
            padding: 12px; 
            border-bottom: 1px solid #eee; 
            vertical-align: middle;
        }
        tr:hover { background: #fafafa; }
        
        /* 6. Hành động và Liên kết */
        .action-link { text-decoration: none; margin-right: 10px; font-weight: bold; }
        .edit { color: #f6c23e; }
        .delete { color: #e74a3b; }

        /* 7. Thông báo */
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; max-width: 1000px; margin: 15px auto; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
    </style>
</head>
<body>
    
    <div style="max-width: 1000px; margin: 0 auto;">
        <a href="quanlysanpham.php" class="btn-back">
            &larr; Quay lại Trang quản trị
        </a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <p class="message success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($errorMessage)): ?>
        <p class="message error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3 style="margin:0;">📦 Danh Sách Loại Sản phẩm</h3>
            <a href="add_loaisp.php" class="btn-add">+ Thêm Mới</a>
        </div>
        
        <?php if (count($loaiSPList) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="25%">Tên Loại</th>
                        <th width="35%">Mô tả</th>
                        <th width="15%">Danh mục</th>
                        <th width="15%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loaiSPList as $lsp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($lsp['idLoaiSP']); ?></td>
                            <td style="font-weight: bold; color: #4e73df;"><?php echo htmlspecialchars($lsp['TenLoai']); ?></td>
                            <td><?php echo htmlspecialchars($lsp['MoTa']); ?></td>
                            <td><?php echo htmlspecialchars($lsp['TenDanhMuc']); ?></td>
                            <td>
                                <a href="../Controllers/edit_loaisp.php?id=<?php echo $lsp['idLoaiSP']; ?>" class="action-link edit">Sửa</a>
                                <a href="../Controllers/delete_loaisp.php?id=<?php echo $lsp['idLoaiSP']; ?>" class="action-link delete" onclick="return confirm('Bạn có chắc chắn muốn xóa loại sản phẩm này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; padding:20px;">Chưa có loại sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>