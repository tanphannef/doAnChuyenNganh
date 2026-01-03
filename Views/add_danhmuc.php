<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Kết nối database
if (file_exists("Models/db.php")) require_once "Models/db.php";
elseif (file_exists("../Models/db.php")) require_once "../Models/db.php";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Danh Mục Mới</title>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fc; padding: 20px; color: #444; }
        .card { background: white; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; overflow: hidden; }
        .card-header { background: #4e73df; color: white; padding: 15px 20px; font-size: 18px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .card-body { padding: 30px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; transition: 0.3s; }
        input:focus { border-color: #4e73df; outline: none; box-shadow: 0 0 5px rgba(78, 115, 223, 0.2); }
        
        .btn-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; }
        .btn-save { background: #1cc88a; } 
        .btn-save:hover { background: #17a673; }
        .btn-back { background: #858796; } 
        .btn-back:hover { background: #6e707e; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">📂 Thêm Danh Mục Mới</div>
    <div class="card-body">
        
        <?php if (isset($_SESSION['error'])): ?>
            <div style="color: red; margin-bottom: 15px; text-align: center;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
            <div style="color: green; margin-bottom: 15px; text-align: center;"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <form method="POST" action="../Process/add_danhmuc_process.php"> 
            
            <div class="form-group">
                <label>Mã Danh Mục (ID):</label>
                <input type="text" name="idDanhMuc" required placeholder="Nhập mã danh mục...">
            </div>
            
            <div class="form-group">
                <label>Tên Danh Mục:</label>
                <input type="text" name="TenDanhMuc" required placeholder="Ví dụ: Chó, Mèo, Chim cảnh...">
            </div>

            <div class="btn-group">
                <a href="../Views/list_danhmuc.php" class="btn btn-back">Quay Lại</a>
                <button type="submit" name="btn_them" class="btn btn-save">Lưu Danh Mục</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>