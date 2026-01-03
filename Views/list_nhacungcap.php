<?php
// File: list_nhacungcap.php (View)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

$nccList = [];
$errorMessage = '';

try {
    $pdo = get_pdo_connection();
    
    // Truy vấn SELECT an toàn bằng PDO
    $sql = "SELECT idNhaCungCap, TenNhaCungCap, DiaChi, Sdt, Email FROM nhacungcap ORDER BY TenNhaCungCap ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $nccList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errorMessage = "Lỗi khi tải danh sách nhà cung cấp: " . $e->getMessage();
}

// Đường dẫn quay lại (Thay thế bằng đường dẫn chính xác của bạn)
$backLink = "../Views/quanlynguoncung.php"; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý Nhà Cung Cấp</title>
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
            max-width: 1200px; /* Tăng chiều rộng cho nhiều thông tin hơn */
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
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; max-width: 1200px; margin: 15px auto; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    
    <div style="max-width: 1200px; margin: 0 auto;">
        <a href="<?= htmlspecialchars($backLink) ?>" class="btn-back">
            &larr; Quay lại Quản lý Nguồn Cung
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
            <h3 style="margin:0;">👥 Danh Sách Nhà Cung Cấp</h3>
            <a href="../Views/add_nhacungcap.php" class="btn-add">+ Thêm Mới</a>
        </div>
        
        <?php if (count($nccList) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">Tên Nhà Cung Cấp</th>
                        <th width="30%">Địa Chỉ</th>
                        <th width="15%">SĐT</th>
                        <th width="20%">Email</th>
                        <th width="10%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nccList as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['idNhaCungCap']) ?></td>
                            <td style="font-weight: bold; color: #4e73df;"><?= htmlspecialchars($row['TenNhaCungCap']) ?></td>
                            <td><?= htmlspecialchars($row['DiaChi']) ?></td>
                            <td><?= htmlspecialchars($row['Sdt']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td>
                                <a href="../Controllers/edit_nhacungcap.php?id=<?= $row['idNhaCungCap'] ?>" class="action-link edit">Sửa</a>
                                <a href="../Controllers/delete_nhacungcap.php?id=<?= $row['idNhaCungCap'] ?>" class="action-link delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; padding:20px;">Chưa có nhà cung cấp nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>