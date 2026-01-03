<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (file_exists("Models/db.php")) require_once "Models/db.php";
elseif (file_exists("../Models/db.php")) require_once "../Models/db.php";

$danhmucList = [];
try {
    if(function_exists('get_pdo_connection')) $pdo = get_pdo_connection();
    else {
        $pdo = new PDO("mysql:host=localhost;dbname=shop_thu_cung;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    // Lấy tất cả danh mục
    $stmt = $pdo->prepare("SELECT * FROM danhmuc ORDER BY idDanhMuc DESC");
    $stmt->execute();
    $danhmucList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản Lý Danh Mục</title>
    <meta charset="utf-8">
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
<a href="quanlysanpham.php" class="btn-back">
    &larr; Quay lại quản lý sản phẩm
</a>
<div class="card">
    <div class="card-header">
        <h3 style="margin:0;">📂 Danh Sách Danh Mục</h3>
        <a href="../Views/add_danhmuc.php" class="btn-add">+ Thêm Mới</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="30%">Mã Danh Mục</th>
                <th width="50%">Tên Danh Mục</th>
                <th width="20%">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($danhmucList) > 0): ?>
                <?php foreach ($danhmucList as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['idDanhMuc']) ?></td>
                    <td style="font-weight: bold; color: #4e73df;"><?= htmlspecialchars($row['TenDanhMuc']) ?></td>
                    <td>
                        <a href="../Controllers/edit_danhmuc.php?id=<?= $row['idDanhMuc'] ?>" class="action-link edit">Sửa</a>
                        <a href="../Controllers/delete_danhmuc.php?id=<?= $row['idDanhMuc'] ?>" class="action-link delete" onclick="return confirm('Bạn có chắc muốn xóa danh mục này?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align:center; padding:20px;">Chưa có danh mục nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>