<?php
// File: edit_nhasanxuat.php (Controller/View)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

$nsx = null;
$error = '';
$idNhaSanXuat = $_REQUEST['id'] ?? null; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_update'])) {
    
    $idToUpdate = $_POST['idNhaSanXuat'] ?? null;
    $TenNhaSanXuat = $_POST['TenNhaSanXuat'] ?? '';
    $QuocGia = $_POST['QuocGia'] ?? null;

    if (empty($idToUpdate) || empty($TenNhaSanXuat)) {
        $_SESSION['error'] = "Tên Nhà Sản Xuất không được để trống.";
        header("Location: edit_nhasanxuat.php?id=" . $idToUpdate); // Chỉnh sửa đường dẫn tương đối
        exit();
    }

    try {
        $pdo = get_pdo_connection();
        
        $sql = "UPDATE nhasanxuat SET TenNhaSanXuat = :tenNsx, QuocGia = :quocGia 
                 WHERE idNhaSanXuat = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            'tenNsx' => $TenNhaSanXuat,
            'quocGia' => $QuocGia,
            'id' => $idToUpdate
        ]);

        $_SESSION['message'] = "Nhà Sản Xuất đã được cập nhật thành công!";
        header('Location: ../Views/list_nhasanxuat.php'); // Chỉnh sửa đường dẫn tương đối
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống khi cập nhật: " . $e->getMessage();
        header("Location: edit_nhasanxuat.php?id=" . $idToUpdate);
        exit();
    }
} 
// --- Tải Dữ liệu cũ để hiển thị Form (GET request) ---
else if ($idNhaSanXuat) {
    try {
        $pdo = get_pdo_connection();
        $sql = "SELECT * FROM nhasanxuat WHERE idNhaSanXuat = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $idNhaSanXuat]);
        $nsx = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$nsx) {
            $error = "Không tìm thấy Nhà Sản Xuất này.";
        }
    } catch (PDOException $e) {
        $error = "Lỗi khi tải dữ liệu cũ.";
    }
} else {
    $error = "Thiếu ID Nhà Sản Xuất để chỉnh sửa.";
}

// Kiểm tra và lấy thông báo từ session
$successMsg = '';
if (isset($_SESSION['message'])) {
    $successMsg = $_SESSION['message'];
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa Nhà Sản Xuất</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fc; padding: 20px; color: #444; }
        .card { background: white; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; overflow: hidden; }
        .card-header { background: #4e73df; color: white; padding: 15px 20px; font-size: 18px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .card-body { padding: 30px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        /* Áp dụng style cho input */
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; transition: 0.3s; }
        input:focus { border-color: #4e73df; outline: none; box-shadow: 0 0 5px rgba(78, 115, 223, 0.2); }
        input[readonly] { background-color: #f1f1f1; cursor: not-allowed; }
        
        .btn-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; }
        .btn-update { background: #f6c23e; } /* Màu update */
        .btn-update:hover { background: #f5b214; }
        .btn-back { background: #858796; } /* Màu quay lại */
        .btn-back:hover { background: #6e707e; }

        /* Style cho thông báo */
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">✏️ Chỉnh Sửa Nhà Sản Xuất</div>
    <div class="card-body">
        
        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error); ?></div>
            <div class="btn-group" style="justify-content: center;">
                 <a href="../Views/list_nhasanxuat.php" class="btn btn-back">Quay Lại Danh Sách</a>
            </div>
        <?php elseif (!empty($successMsg)): ?>
            <div class="alert-success"><?= htmlspecialchars($successMsg); ?></div>
            <div class="btn-group" style="justify-content: center;">
                 <a href="../Views/list_nhasanxuat.php" class="btn btn-back">Tiếp Tục</a>
            </div>
        <?php elseif ($nsx): ?>
            
            <form method="POST" action="edit_nhasanxuat.php?id=<?php echo $nsx['idNhaSanXuat']; ?>"> 
                
                <div class="form-group">
                    <label>ID Nhà Sản Xuất:</label> 
                    <input type="text" name="idNhaSanXuat" value="<?php echo htmlspecialchars($nsx['idNhaSanXuat']); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Tên Nhà Sản Xuất (*):</label>
                    <input type="text" name="TenNhaSanXuat" value="<?php echo htmlspecialchars($nsx['TenNhaSanXuat']); ?>" required placeholder="Nhập tên nhà sản xuất...">
                </div>
                
                <div class="form-group">
                    <label>Quốc Gia:</label>
                    <input type="text" name="QuocGia" value="<?php echo htmlspecialchars($nsx['QuocGia']); ?>" placeholder="Nhập quốc gia...">
                </div>

                <div class="btn-group">
                    <a href="../Views/list_nhasanxuat.php" class="btn btn-back">Hủy</a>
                    <button type="submit" name="btn_update" class="btn btn-update">Cập Nhật</button>
                </div>
            </form>
            
        <?php endif; ?>
    </div>
</div>

</body>
</html>