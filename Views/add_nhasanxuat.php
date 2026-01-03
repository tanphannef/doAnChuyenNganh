<?php
// File: add_nhasanxuat.php (View)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Đường dẫn quay lại danh sách Nhà Sản Xuất
$backLink = "list_nhasanxuat.php"; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Nhà Sản Xuất Mới</title>
    <meta charset="utf-8">
    <style>
        /* CSS đồng bộ với các trang Thêm/Sửa khác */
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
        
        /* Input */
        input[type="text"] { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            box-sizing: border-box; 
            transition: 0.3s;
        }
        input:focus { 
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
    <div class="card-header">🏭 Thêm Nhà Sản Xuất Mới</div>
    <div class="card-body">
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <form method="POST" action="../Process/add_nsx_process.php">
            
            <div class="form-group">
                <label for="idNhaSanXuat">ID Nhà Sản Xuất:</label>
                <input type="text" id="idNhaSanXuat" name="idNhaSanXuat" required placeholder="Nhập mã nhà sản xuất...">
            </div>
            
            <div class="form-group">
                <label for="TenNhaSanXuat">Tên Nhà Sản Xuất:</label>
                <input type="text" id="TenNhaSanXuat" name="TenNhaSanXuat" required placeholder="Nhập tên công ty/thương hiệu...">
            </div>
            
            <div class="form-group">
                <label for="QuocGia">Quốc Gia:</label>
                <input type="text" id="QuocGia" name="QuocGia" placeholder="Nhập quốc gia...">
            </div>
            
            <div class="btn-group">
                <a href="<?= $backLink ?>" class="btn btn-back">Quay Lại</a>
                <button type="submit" name="btn_them" class="btn btn-save">Lưu Nhà Sản Xuất</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>