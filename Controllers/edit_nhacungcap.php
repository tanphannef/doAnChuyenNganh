<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../Models/db.php"; 

$ncc = null;
$error = '';
$idNhaCungCap = $_REQUEST['id'] ?? null; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_update'])) {
    
    $idToUpdate = $_POST['idNhaCungCap'] ?? null;
    $TenNhaCungCap = $_POST['TenNhaCungCap'] ?? '';
    $DiaChi = $_POST['DiaChi'] ?? null;
    $Sdt = $_POST['Sdt'] ?? null;
    $Email = $_POST['Email'] ?? null;

    if (empty($idToUpdate) || empty($TenNhaCungCap)) {
        $_SESSION['error'] = "Tên Nhà Cung Cấp không được để trống.";
        header("Location: edit_nhacungcap.php?id=" . $idToUpdate); // Chỉnh sửa đường dẫn tương đối
        exit();
    }

    try {
        $pdo = get_pdo_connection();
        
        $sql = "UPDATE nhacungcap SET TenNhaCungCap = :tenNcc, DiaChi = :diaChi, Sdt = :sdt, Email = :email 
                 WHERE idNhaCungCap = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            'tenNcc' => $TenNhaCungCap,
            'diaChi' => $DiaChi,
            'sdt' => $Sdt,
            'email' => $Email,
            'id' => $idToUpdate
        ]);

        $_SESSION['message'] = "Nhà Cung Cấp đã được cập nhật thành công!";
        header('Location: ../Views/list_nhacungcap.php'); // Chỉnh sửa đường dẫn tương đối
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống khi cập nhật: " . $e->getMessage();
        header("Location: edit_nhacungcap.php?id=" . $idToUpdate);
        exit();
    }
} 
// --- Tải Dữ liệu cũ để hiển thị Form (GET request) ---
else if ($idNhaCungCap) {
    try {
        $pdo = get_pdo_connection();
        $sql = "SELECT * FROM nhacungcap WHERE idNhaCungCap = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $idNhaCungCap]);
        $ncc = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ncc) {
            $error = "Không tìm thấy Nhà Cung Cấp này.";
        }
    } catch (PDOException $e) {
        $error = "Lỗi khi tải dữ liệu cũ.";
    }
} else {
    $error = "Thiếu ID Nhà Cung Cấp để chỉnh sửa.";
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
    <title>Chỉnh sửa Nhà Cung Cấp</title>
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
    <div class="card-header">✏️ Chỉnh Sửa Nhà Cung Cấp</div>
    <div class="card-body">
        
        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error); ?></div>
            <div class="btn-group" style="justify-content: center;">
                 <a href="../Views/list_nhacungcap.php" class="btn btn-back">Quay Lại Danh Sách</a>
            </div>
        <?php elseif (!empty($successMsg)): ?>
            <div class="alert-success"><?= htmlspecialchars($successMsg); ?></div>
            <div class="btn-group" style="justify-content: center;">
                 <a href="../Views/list_nhacungcap.php" class="btn btn-back">Tiếp Tục</a>
            </div>
        <?php elseif ($ncc): ?>
            
            <form method="POST" action="edit_nhacungcap.php?id=<?php echo $ncc['idNhaCungCap']; ?>"> 
                
                <div class="form-group">
                    <label>ID Nhà Cung Cấp:</label> 
                    <input type="text" name="idNhaCungCap" value="<?php echo htmlspecialchars($ncc['idNhaCungCap']); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Tên Nhà Cung Cấp (*):</label>
                    <input type="text" name="TenNhaCungCap" value="<?php echo htmlspecialchars($ncc['TenNhaCungCap']); ?>" required placeholder="Nhập tên nhà cung cấp...">
                </div>
                
                <div class="form-group">
                    <label>Địa Chỉ:</label>
                    <input type="text" name="DiaChi" value="<?php echo htmlspecialchars($ncc['DiaChi']); ?>" placeholder="Nhập địa chỉ công ty...">
                </div>
                
                <div class="form-group">
                    <label>Số Điện Thoại:</label>
                    <input type="text" name="Sdt" value="<?php echo htmlspecialchars($ncc['Sdt']); ?>" placeholder="Nhập số điện thoại...">
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="Email" value="<?php echo htmlspecialchars($ncc['Email']); ?>" placeholder="Nhập email liên hệ...">
                </div>

                <div class="btn-group">
                    <a href="../Views/list_nhacungcap.php" class="btn btn-back">Hủy</a>
                    <button type="submit" name="btn_update" class="btn btn-update">Cập Nhật</button>
                </div>
            </form>
            
        <?php endif; ?>
    </div>
</div>

</body>
</html>