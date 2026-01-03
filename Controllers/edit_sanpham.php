<?php
// Controllers/edit_sanpham.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Giả định file db.php nằm ở vị trí đúng và có hàm get_pdo_connection()
require_once "../Models/db.php"; 

$sanPham = null;
$lists = ['danhmuc' => [], 'loaisanpham' => [], 'nhacungcap' => [], 'nhasanxuat' => []];
$error = '';
$idSanPham = $_REQUEST['id'] ?? null; // Lấy ID từ GET hoặc POST

try {
    $pdo = get_pdo_connection();

    // Hàm chung để lấy dữ liệu cho dropdown
    $getList = function($pdo, $tableName, $idCol, $nameCol) {
        $stmt = $pdo->prepare("SELECT $idCol, $nameCol FROM $tableName ORDER BY $nameCol ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    };

    $lists['danhmuc'] = $getList($pdo, 'danhmuc', 'idDanhMuc', 'TenDanhMuc');
    $lists['loaisanpham'] = $getList($pdo, 'loaisanpham', 'idLoaiSP', 'TenLoai');
    $lists['nhacungcap'] = $getList($pdo, 'nhacungcap', 'idNhaCungCap', 'TenNhaCungCap');
    $lists['nhasanxuat'] = $getList($pdo, 'nhasanxuat', 'idNhaSanXuat', 'TenNhaSanXuat');

} catch (PDOException $e) {
    $error = "Lỗi tải dữ liệu cho form: " . $e->getMessage();
}

// --- Giai đoạn 1: Xử lý Form POST (Cập nhật dữ liệu) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_update'])) {
    
    $idToUpdate = $_POST['idSanPham'] ?? null;
    $TenSanPham = $_POST['TenSanPham'] ?? '';
    $SoLuong = $_POST['SoLuong'] ?? 0;
    $MoTa = $_POST['MoTa'] ?? null;
    $Gia = $_POST['Gia'] ?? 0;
    $idDanhMuc = $_POST['idDanhMuc'] ?? null;
    $idLoaiSP = $_POST['idLoaiSP'] ?? null;
    $idNhaCungCap = $_POST['idNhaCungCap'] ?? null;
    $idNhaSanXuat = $_POST['idNhaSanXuat'] ?? null;
    
    // Khởi tạo ảnh hiện tại (Tên cột: Image - Chữ I hoa)
    $currentImagePath = $_POST['current_Image'] ?? null; 
    
    // --- Bổ sung Logic xử lý Upload ảnh mới ---
    if (isset($_FILES['Image']) && $_FILES['Image']['name'] != "") {
        $target_dir = "../Public/images/"; 
        $new_image_name = time() . "_" . basename($_FILES["Image"]["name"]);
        $target_file = $target_dir . $new_image_name;
        
        if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
            $currentImagePath = $new_image_name; // Cập nhật tên ảnh mới vào DB
        }
    }
    // -------------------------------------------------

    if (empty($idToUpdate) || empty($TenSanPham) || empty($idDanhMuc)) {
        $_SESSION['error'] = "Dữ liệu bắt buộc không được để trống.";
        header("Location: ../Controllers/edit_sanpham.php?id=" . $idToUpdate);
        exit();
    }

    try {
        $pdo = get_pdo_connection();
        
        // Sửa tên cột 'image' thành 'Image' trong câu UPDATE
        $sql = "UPDATE sanpham SET TenSanPham = :tenSP, SoLuong = :sl, MoTa = :mt, Gia = :gia, 
                 idDanhMuc = :idDm, idLoaiSP = :idLsp, idNhaCungCap = :idNcc, idNhaSanXuat = :idNsx, Image = :img 
                 WHERE idSanPham = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            'tenSP' => $TenSanPham,
            'sl' => $SoLuong,
            'mt' => $MoTa,
            'gia' => $Gia,
            'idDm' => $idDanhMuc,
            'idLsp' => $idLoaiSP,
            'idNcc' => $idNhaCungCap,
            'idNsx' => $idNhaSanXuat,
            'img' => $currentImagePath, // Lấy tên ảnh đã được cập nhật
            'id' => $idToUpdate
        ]);

        $_SESSION['message'] = "Sản phẩm đã được cập nhật thành công!";
        header('Location: ../Views/list_sanpham.php'); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi hệ thống khi cập nhật: " . $e->getMessage();
        header("Location: ../Controllers/edit_sanpham.php?id=" . $idToUpdate);
        exit();
    }
} 
// --- Giai đoạn 2: Tải Dữ liệu cũ để hiển thị Form (GET request) ---
else if ($idSanPham && empty($error)) {
    try {
        $sql = "SELECT * FROM sanpham WHERE idSanPham = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $idSanPham]);
        $sanPham = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sanPham) {
            $error = "Không tìm thấy sản phẩm này.";
        }
    } catch (PDOException $e) {
        $error = "Lỗi khi tải dữ liệu cũ.";
    }
} else if (!$idSanPham && empty($error)) {
    $error = "Thiếu ID sản phẩm để chỉnh sửa.";
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
    <title>Chỉnh sửa Sản phẩm: <?= htmlspecialchars($idSanPham ?? '') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS ĐỒNG BỘ TỪ TRANG ADD */
        body { font-family: 'Quicksand', sans-serif; background-color: #f8f9fa; }
        .card { border: none; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
        .card-header { background: linear-gradient(135deg, #36D1DC, #5B86E5); color: white; border-radius: 15px 15px 0 0 !important; padding: 20px; }
        .form-label { font-weight: 700; color: #555; }
        .form-control, .form-select { border-radius: 10px; padding: 10px 15px; border: 1px solid #eee; }
        .form-control[readonly] { background-color: #e9ecef; } /* Làm nổi bật ô ID không sửa */
        .btn-update { background: linear-gradient(135deg, #FFD700, #FFA500); border: none; padding: 12px 30px; font-weight: bold; color: #444; border-radius: 50px; transition: 0.3s; }
        .btn-update:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(255, 165, 0, 0.4); color: #444;}
        .btn-back { background: #6c757d; color: white; border-radius: 50px; padding: 12px 25px; text-decoration: none; font-weight: bold; display: inline-block; }
        .img-preview-box { 
            width: 100%; 
            height: 250px; 
            border: 2px dashed #ddd; 
            border-radius: 15px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden; 
            background: #fff; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        .img-preview-box img { 
            max-width: 100%; 
            max-height: 100%; 
            object-fit: contain; 
            display: block; /* Mặc định hiển thị nếu có ảnh cũ */
        }
        .upload-text { display: none; } /* Mặc định ẩn vì có ảnh cũ */
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="m-0">🛠️ Chỉnh Sửa Sản Phẩm: <?= htmlspecialchars($idSanPham ?? 'ID') ?></h3>
                    <a href="../Views/list_sanpham.php" class="text-white text-decoration-none fw-bold">← Quay lại</a>
                </div>
                <div class="card-body p-5">
                    
                    <?php if (!empty($error)): ?>
                        <div class='alert alert-danger'><?= $error ?></div>
                        <p class="mt-3"><a href="../Views/list_sanpham.php">Quay lại danh sách</a></p>
                    <?php elseif ($sanPham): 
                        // Lấy tên file ảnh từ DB (chữ I hoa)
                        $currentImageFile = $sanPham['Image']; 
                        
                        // Xử lý đường dẫn tương đối (FIX LỖI ẢNH GÃY)
                        $imageSrc = '';
                        $isImageExist = false;
                        if (!empty($currentImageFile)) {
                            // Kiểm tra nếu nó là link mạng (chủ động tránh lỗi)
                            if (strpos($currentImageFile, 'http') !== false) {
                                $imageSrc = $currentImageFile;
                            } 
                            // Nếu là tên file (hoodie.jpg), thêm đường dẫn tương đối
                            else {
                                $imageSrc = "../Public/images/" . $currentImageFile; 
                            }
                            $isImageExist = true;
                        }
                    ?>
                        
                    <form method="POST" action="../Controllers/edit_sanpham.php" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-7">
                                
                                <div class="mb-3">
                                    <label class="form-label">Mã Sản Phẩm (ID)</label>
                                    <input type="text" name="idSanPham" class="form-control" value="<?= htmlspecialchars($sanPham['idSanPham']); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tên Sản Phẩm (*)</label>
                                    <input type="text" name="TenSanPham" class="form-control" value="<?= htmlspecialchars($sanPham['TenSanPham']); ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá Bán (*)</label>
                                        <input type="number" name="Gia" class="form-control" value="<?= htmlspecialchars($sanPham['Gia']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Số Lượng</label>
                                        <input type="number" name="SoLuong" class="form-control" value="<?= htmlspecialchars($sanPham['SoLuong']); ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Danh Mục</label>
                                        <select name="idDanhMuc" class="form-select" required>
                                            <option value="">-- Chọn --</option>
                                            <?php foreach ($lists['danhmuc'] as $dm): ?>
                                                <option value="<?= $dm['idDanhMuc'] ?>" <?= ($dm['idDanhMuc'] == $sanPham['idDanhMuc']) ? 'selected' : '' ?>>
                                                    <?= $dm['TenDanhMuc'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Loại Sản Phẩm</label>
                                        <select name="idLoaiSP" class="form-select" required>
                                            <option value="">-- Chọn Loại --</option>
                                            <?php foreach ($lists['loaisanpham'] as $lsp): ?>
                                                <option value="<?= $lsp['idLoaiSP'] ?>" <?= ($lsp['idLoaiSP'] == $sanPham['idLoaiSP']) ? 'selected' : '' ?>>
                                                    <?= $lsp['TenLoai'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nhà Cung Cấp</label>
                                        <select name="idNhaCungCap" class="form-select" required>
                                            <option value="">-- Chọn NCC --</option>
                                            <?php foreach ($lists['nhacungcap'] as $ncc): ?>
                                                <option value="<?= $ncc['idNhaCungCap'] ?>" <?= ($ncc['idNhaCungCap'] == $sanPham['idNhaCungCap']) ? 'selected' : '' ?>>
                                                    <?= $ncc['TenNhaCungCap'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nhà Sản Xuất</label>
                                        <select name="idNhaSanXuat" class="form-select" required>
                                            <option value="">-- Chọn NSX --</option>
                                            <?php foreach ($lists['nhasanxuat'] as $nsx): ?>
                                                <option value="<?= $nsx['idNhaSanXuat'] ?>" <?= ($nsx['idNhaSanXuat'] == $sanPham['idNhaSanXuat']) ? 'selected' : '' ?>>
                                                    <?= $nsx['TenNhaSanXuat'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô Tả</label>
                                    <textarea name="MoTa" class="form-control" rows="3"><?= htmlspecialchars($sanPham['MoTa']); ?></textarea>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Hình Ảnh (Click để thay đổi)</label>
                                <input type="hidden" name="current_Image" value="<?= htmlspecialchars($sanPham['Image']); ?>">
                                
                                <input type="file" name="Image" id="fileInput" class="d-none" onchange="previewImage(this)">
                                
                                <div class="img-preview-box" onclick="document.getElementById('fileInput').click()">
                                    <div id="uploadText" class="text-center text-muted" style="<?= $isImageExist ? 'display: none;' : 'display: block;' ?>">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i><br>Chọn ảnh mới
                                    </div>
                                    <img id="imgPreview" src="<?= htmlspecialchars($imageSrc); ?>" 
                                         style="<?= $isImageExist ? 'display: block;' : 'display: none;' ?>" 
                                         alt="Ảnh Sản Phẩm Hiện Tại"
                                         onerror="this.style.display='none'; document.getElementById('uploadText').style.display='block';">
                                </div>
                                <small class="text-muted">Nhấn vào ảnh để tải ảnh mới.</small>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="../Views/list_sanpham.php" class="btn-back me-2">Hủy</a>
                            <button type="submit" name="btn_update" class="btn-update">📝 Cập Nhật Sản Phẩm</button>
                        </div>
                    </form>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        const imgPreview = document.getElementById('imgPreview');
        const uploadText = document.getElementById('uploadText');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                imgPreview.src = e.target.result;
                imgPreview.style.display = 'block';
                uploadText.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            // Nếu người dùng hủy chọn file, hiển thị lại ảnh cũ hoặc uploadText
            // Dùng src ảnh cũ (nếu có) hoặc hiển thị text
            if (imgPreview.src && imgPreview.src.includes('http')) {
                 imgPreview.style.display = 'block';
                 uploadText.style.display = 'none';
            } else {
                 imgPreview.style.display = 'none';
                 uploadText.style.display = 'block';
            }
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>