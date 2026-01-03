<?php
// File: edit_loaisp.php (Controller/View)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../Models/db.php"; 

$loaiSP = null;
$danhmucList = [];
$error = '';
$idLoaiSP = $_GET['id'] ?? null;

try {
    $pdo = get_pdo_connection();

    // Lấy danh sách danh mục cho dropdown
    $stmtDm = $pdo->prepare("SELECT idDanhMuc, TenDanhMuc FROM danhmuc");
    $stmtDm->execute();
    $danhmucList = $stmtDm->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Lỗi hệ thống: " . $e->getMessage();
}

// --- Giai đoạn 1: Xử lý Form POST (Cập nhật dữ liệu) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_update'])) {
    
    $idToUpdate = $_POST['idLoaiSP'] ?? null;
    $tenMoi = $_POST['TenLoai'] ?? '';
    $moTaMoi = $_POST['MoTa'] ?? null;
    $idDanhMucMoi = $_POST['idDanhMuc'] ?? null;

    if (empty($idToUpdate) || empty($tenMoi) || empty($idDanhMucMoi)) {
        $_SESSION['error'] = "Dữ liệu không được để trống.";
        // Chỉnh sửa header location để phù hợp với cấu trúc views/
        header("Location: edit_loaisp.php?id=" . $idToUpdate); 
        exit();
    }

    try {
        $pdo = get_pdo_connection(); // Lấy lại kết nối nếu cần
        
        // Truy vấn UPDATE an toàn bằng Prepared Statements
        $sql = "UPDATE loaisanpham SET TenLoai = :ten, MoTa = :moTa, idDanhMuc = :idDm WHERE idLoaiSP = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            'ten' => $tenMoi,
            'moTa' => $moTaMoi,
            'idDm' => $idDanhMucMoi,
            'id' => $idToUpdate
        ]);

        $_SESSION['message'] = "Loại sản phẩm đã được cập nhật thành công!";
        // Chỉnh sửa header location để phù hợp với cấu trúc views/
        header('Location: ../Views/list_loaisp.php'); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống khi cập nhật: " . $e->getMessage();
        header("Location: edit_loaisp.php?id=" . $idToUpdate);
        exit();
    }
} 
// --- Giai đoạn 2: Tải Dữ liệu cũ để hiển thị Form (GET request) ---
else if ($idLoaiSP && empty($error)) {
    try {
        $pdo = get_pdo_connection(); // Lấy lại kết nối nếu cần
        $sql = "SELECT idLoaiSP, TenLoai, MoTa, idDanhMuc FROM loaisanpham WHERE idLoaiSP = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $idLoaiSP]);
        
        $loaiSP = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$loaiSP) {
            $error = "Không tìm thấy loại sản phẩm này.";
        }
    } catch (PDOException $e) {
        $error = "Lỗi khi tải dữ liệu cũ.";
    }
} else if (!$idLoaiSP && empty($error)) {
    $error = "Thiếu ID loại sản phẩm để chỉnh sửa.";
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
    <title>Chỉnh sửa Loại Sản phẩm</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fc; padding: 20px; color: #444; }
        .card { background: white; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; overflow: hidden; }
        .card-header { background: #4e73df; color: white; padding: 15px 20px; font-size: 18px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .card-body { padding: 30px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; transition: 0.3s; }
        input:focus, textarea:focus, select:focus { border-color: #4e73df; outline: none; box-shadow: 0 0 5px rgba(78, 115, 223, 0.2); }
        input[readonly] { background-color: #f1f1f1; cursor: not-allowed; }
        
        .btn-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; }
        .btn-update { background: #f6c23e; } 
        .btn-update:hover { background: #f5b214; }
        .btn-back { background: #858796; } 
        .btn-back:hover { background: #6e707e; }

        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">✏️ Chỉnh Sửa Loại Sản Phẩm</div>
    <div class="card-body">
        
        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error); ?></div>
            <div class="btn-group" style="justify-content: center;">
                 <a href="../Views/list_loaisp.php" class="btn btn-back">Quay Lại Danh Sách</a>
            </div>
        <?php elseif (!empty($successMsg)): ?>
            <div class="alert-success"><?= htmlspecialchars($successMsg); ?></div>
            <div class="btn-group" style="justify-content: center;">
                 <a href="../Views/list_loaisp.php" class="btn btn-back">Tiếp Tục</a>
            </div>
        <?php elseif ($loaiSP): ?>
            
            <form method="POST" action="edit_loaisp.php?id=<?php echo $loaiSP['idLoaiSP']; ?>"> 
                
                <div class="form-group">
                    <label>Mã Loại SP:</label> 
                    <input type="text" name="idLoaiSP" value="<?php echo htmlspecialchars($loaiSP['idLoaiSP']); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Tên Loại (*):</label> 
                    <input type="text" name="TenLoai" value="<?php echo htmlspecialchars($loaiSP['TenLoai']); ?>" required placeholder="Nhập tên loại sản phẩm mới...">
                </div>

                <div class="form-group">
                    <label>Mô Tả:</label> 
                    <textarea name="MoTa" rows="3" placeholder="Mô tả về loại sản phẩm này..."><?php echo htmlspecialchars($loaiSP['MoTa']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Danh Mục Cha (*):</label>
                    <select name="idDanhMuc" required>
                        <option value="">-- Chọn Danh Mục --</option>
                        <?php foreach ($danhmucList as $dm): ?>
                            <option value="<?= htmlspecialchars($dm['idDanhMuc']) ?>"
                                <?= ($dm['idDanhMuc'] == $loaiSP['idDanhMuc']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dm['TenDanhMuc']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="btn-group">
                    <a href="../Views/list_loaisp.php" class="btn btn-back">Hủy</a>
                    <button type="submit" name="btn_update" class="btn btn-update">Cập Nhật</button>
                </div>
            </form>
            
        <?php endif; ?>
    </div>
</div>

</body>
</html>