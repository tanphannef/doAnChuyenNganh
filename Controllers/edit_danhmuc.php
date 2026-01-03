<?php
// File: edit_danhmuc.php
if (session_status() == PHP_SESSION_NONE) session_start();

// Đảm bảo đường dẫn đến db.php là chính xác.
// Giả định file này nằm cùng cấp với list_danhmuc.php (trong Admin/ hoặc Views/)
require_once "../Models/db.php"; 

$danhMuc = null;
$error = '';
$idDanhMuc = $_GET['id'] ?? null;

// --- Giai đoạn 1: Xử lý Form POST (Cập nhật dữ liệu) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_update'])) {
    
    $idToUpdate = $_POST['idDanhMuc'] ?? null;
    $tenDanhMucMoi = $_POST['TenDanhMuc'] ?? '';

    if (empty($idToUpdate) || empty($tenDanhMucMoi)) {
        $_SESSION['error'] = "Dữ liệu không được để trống.";
        header("Location: edit_danhmuc.php?id=" . $idToUpdate); // Sửa đường dẫn tương đối
        exit();
    }

    try {
        $pdo = get_pdo_connection();
        
        // Truy vấn UPDATE an toàn bằng Prepared Statements
        $sql = "UPDATE danhmuc SET TenDanhMuc = :tenMoi WHERE idDanhMuc = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            'tenMoi' => $tenDanhMucMoi,
            'id' => $idToUpdate
        ]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Danh mục đã được cập nhật thành công!";
        } else {
            $_SESSION['error'] = "Không có thay đổi nào được thực hiện.";
        }
        
        // Điều hướng về trang danh sách sau khi cập nhật
        header('Location: ../Views/list_danhmuc.php'); // Sửa đường dẫn tương đối
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống khi cập nhật: " . $e->getMessage();
        header("Location: edit_danhmuc.php?id=" . $idToUpdate);
        exit();
    }
} 
// --- Giai đoạn 2: Tải Dữ liệu cũ để hiển thị Form ---
else if ($idDanhMuc) {
    if (!is_numeric($idDanhMuc)) {
        $error = "ID danh mục không hợp lệ.";
    } else {
        try {
            $pdo = get_pdo_connection();
            
            // Truy vấn SELECT an toàn
            $sql = "SELECT idDanhMuc, TenDanhMuc FROM danhmuc WHERE idDanhMuc = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $idDanhMuc]);
            
            $danhMuc = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$danhMuc) {
                $error = "Không tìm thấy danh mục này.";
            }

        } catch (PDOException $e) {
            $error = "Lỗi kết nối cơ sở dữ liệu.";
            error_log("Lỗi PDO tải dữ liệu danh mục: " . $e->getMessage());
        }
    }
} else {
    $error = "Thiếu ID danh mục để chỉnh sửa.";
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
    <title>Chỉnh sửa Danh mục</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fc; padding: 20px; color: #444; }
        .card { background: white; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; overflow: hidden; }
        .card-header { background: #4e73df; color: white; padding: 15px 20px; font-size: 18px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .card-body { padding: 30px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        /* Áp dụng style input cho cả input thường và input readonly */
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; transition: 0.3s; }
        input:focus { border-color: #4e73df; outline: none; box-shadow: 0 0 5px rgba(78, 115, 223, 0.2); }
        input[readonly] { background-color: #f1f1f1; cursor: not-allowed; }
        
        .btn-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; }
        .btn-update { background: #f6c23e; } /* Đổi màu nút update sang màu vàng */
        .btn-update:hover { background: #f5b214; }
        .btn-back { background: #858796; } 
        .btn-back:hover { background: #6e707e; }

        /* Style cho thông báo */
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">✏️ Chỉnh Sửa Danh Mục</div>
    <div class="card-body">
        
        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error); ?></div>
            <div class="btn-group" style="justify-content: center;">
                 <a href="../Views/list_danhmuc.php" class="btn btn-back">Quay Lại Danh Sách</a>
            </div>
        <?php elseif (!empty($successMsg)): ?>
            <div class="alert-success"><?= htmlspecialchars($successMsg); ?></div>
            <div class="btn-group" style="justify-content: center;">
                 <a href="../Views/list_danhmuc.php" class="btn btn-back">Tiếp Tục</a>
            </div>
        <?php elseif ($danhMuc): ?>
            
            <form method="POST" action="edit_danhmuc.php?id=<?php echo $danhMuc['idDanhMuc']; ?>"> 
                
                <div class="form-group">
                    <label>Mã Danh Mục (ID):</label> 
                    <input type="text" name="idDanhMuc" value="<?php echo htmlspecialchars($danhMuc['idDanhMuc']); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Tên Danh Mục:</label> 
                    <input type="text" name="TenDanhMuc" value="<?php echo htmlspecialchars($danhMuc['TenDanhMuc']); ?>" required placeholder="Nhập tên danh mục mới...">
                </div>

                <div class="btn-group">
                    <a href="../Views/list_danhmuc.php" class="btn btn-back">Hủy</a>
                    <button type="submit" name="btn_update" class="btn btn-update">Cập Nhật</button>
                </div>
            </form>
            
        <?php endif; ?>
    </div>
</div>

</body>
</html>