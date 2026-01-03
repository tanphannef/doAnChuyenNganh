<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

$danhmucList = [];
$error = '';

try {
    $pdo = get_pdo_connection();
    
    // Lấy danh sách danh mục (PDO)
    $stmt = $pdo->prepare("SELECT idDanhMuc, TenDanhMuc FROM danhmuc ORDER BY TenDanhMuc ASC");
    $stmt->execute();
    $danhmucList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Lỗi tải danh mục: " . $e->getMessage();
}

// Đường dẫn quay lại danh sách Loại Sản phẩm
$backLink = "list_loaisp.php"; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Loại Sản phẩm Mới</title>
    <meta charset="utf-8">
    <style>
        /* CSS đồng bộ với add_danhmuc.php */
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fc; padding: 20px; color: #444; }
        .card { background: white; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; overflow: hidden; }
        .card-header { background: #4e73df; color: white; padding: 15px 20px; font-size: 18px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .card-body { padding: 30px; }
        
        /* Thông báo */
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; text-align: center; }
        .alert-error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .alert-success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }

        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        
        /* Input, Textarea, Select */
        input[type="text"], textarea, select { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
            transition: 0.3s;
            resize: vertical; /* Cho phép thay đổi kích thước textarea */
        }
        input:focus, textarea:focus, select:focus { 
            border-color: #4e73df; 
            outline: none; 
            box-shadow: 0 0 5px rgba(78, 115, 223, 0.2); 
        }
        
        .btn-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; display: inline-block; }
        .btn-save { background: #1cc88a; } 
        .btn-save:hover { background: #17a673; }
        .btn-back { background: #858796; } 
        .btn-back:hover { background: #6e707e; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">📦 Thêm Loại Sản phẩm Mới</div>
    <div class="card-body">
        
        <?php if (isset($_SESSION['error']) || !empty($error)): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error'] ?? $error; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <form method="POST" action="../Process/add_loaisp_process.php"> 
            
            <div class="form-group">
                <label for="idLoaiSP">ID Loại Sản phẩm:</label> 
                <input type="text" id="idLoaiSP" name="idLoaiSP" required placeholder="Nhập mã loại sản phẩm...">
            </div>
            
            <div class="form-group">
                <label for="TenLoai">Tên Loại Sản phẩm:</label> 
                <input type="text" id="TenLoai" name="TenLoai" required placeholder="Ví dụ: Thức ăn khô, Đồ chơi cho mèo...">
            </div>
            
            <div class="form-group">
                <label for="MoTa">Mô tả:</label> 
                <textarea id="MoTa" name="MoTa" rows="3" placeholder="Nhập mô tả ngắn về loại sản phẩm này..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="idDanhMuc">Chọn Danh mục:</label>
                <select id="idDanhMuc" name="idDanhMuc" required>
                    <?php if (empty($danhmucList)): ?>
                        <option value="">-- Không có danh mục nào (Kiểm tra DB) --</option>
                    <?php else: ?>
                        <option value="">-- Chọn Danh mục --</option>
                        <?php foreach ($danhmucList as $row): ?>
                            <option value="<?= htmlspecialchars($row['idDanhMuc']) ?>">
                                <?= htmlspecialchars($row['TenDanhMuc']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="btn-group">
                <a href="<?= $backLink ?>" class="btn btn-back">Quay Lại</a>
                <button type="submit" name="btn_them" class="btn btn-save">Lưu Loại Sản phẩm</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>