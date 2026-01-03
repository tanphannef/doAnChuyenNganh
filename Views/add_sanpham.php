<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// 1. KẾT NỐI DATABASE
// (Dùng code kết nối trực tiếp để đảm bảo chạy được ngay lập tức)
function get_db_connection() {
    try {
        // Tên DB của bạn là 'qlpetshop' hay 'shop_thu_cung'? 
        // Dựa vào ảnh lỗi bạn gửi thì tên DB là `qlpetshop`. Mình để mặc định là `qlpetshop` nhé.
        return new PDO("mysql:host=localhost;dbname=qlpetshop;charset=utf8", "root", "");
    } catch (Exception $e) { 
        // Nếu lỗi kết nối, thử tên DB kia (phòng hờ)
        try {
            return new PDO("mysql:host=localhost;dbname=shop_thu_cung;charset=utf8", "root", "");
        } catch (Exception $ex) { return null; }
    }
}
$conn = get_db_connection();

// 2. LẤY DỮ LIỆU TỪ CÁC BẢNG KHÓA NGOẠI (QUAN TRỌNG)
// Phải lấy danh sách ID có thật để đổ vào ô Select

// Bảng Danh Mục
$categories = [];
if($conn) $categories = $conn->query("SELECT * FROM danhmuc")->fetchAll(PDO::FETCH_ASSOC);

// Bảng Loại Sản Phẩm (Dựa theo ảnh bạn gửi: idLoaiSP, TenLoai)
$types = [];
if($conn) $types = $conn->query("SELECT * FROM loaisanpham")->fetchAll(PDO::FETCH_ASSOC);

// Bảng Nhà Cung Cấp (Dựa theo ảnh bạn gửi: idNhaCungCap, TenNhaCungCap)
$suppliers = [];
if($conn) $suppliers = $conn->query("SELECT * FROM nhacungcap")->fetchAll(PDO::FETCH_ASSOC);

// Bảng Nhà Sản Xuất (Dựa theo ảnh bạn gửi: idNhaSanXuat, TenNhaSanXuat)
$manufacturers = [];
if($conn) $manufacturers = $conn->query("SELECT * FROM nhasanxuat")->fetchAll(PDO::FETCH_ASSOC);


// 3. XỬ LÝ KHI BẤM LƯU
$msg = "";
if (isset($_POST['btn_save'])) {
    // Lấy dữ liệu từ form
    $id = $_POST['idSanPham'];      // Varchar(50) - Ví dụ: TA10, PK05
    $ten = $_POST['TenSanPham'];
    $gia = $_POST['Gia'];
    $soluong = $_POST['SoLuong'];
    $mota = $_POST['MoTa'];
    
    // Các ID khóa ngoại (Lấy từ ô chọn)
    $iddm = $_POST['idDanhMuc'];
    $idloai = $_POST['idLoaiSP'];
    $idncc = $_POST['idNhaCungCap'];
    $idnsx = $_POST['idNhaSanXuat'];

    // Xử lý ảnh
    $hinh = "";
    if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['name'] != "") {
        $target_dir = "../Public/images/"; 
        // Kiểm tra đường dẫn thư mục, nếu đang ở thư mục gốc thì chỉnh lại
        if (!is_dir($target_dir)) $target_dir = "Public/images/"; 
        
        $hinh = basename($_FILES["HinhAnh"]["name"]);
        $target_file = $target_dir . $hinh;
        move_uploaded_file($_FILES["HinhAnh"]["tmp_name"], $target_file);
    } else {
        // Nếu không chọn ảnh, để rỗng hoặc mặc định
        $hinh = "default.png"; 
    }

    if ($conn) {
        try {
            // Câu lệnh INSERT đầy đủ các cột trong bảng sanpham
            $sql = "INSERT INTO sanpham 
                    (idSanPham, TenSanPham, SoLuong, MoTa, Gia, idDanhMuc, idLoaiSP, idNhaCungCap, idNhaSanXuat, Image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            // Thực thi
            if ($stmt->execute([$id, $ten, $soluong, $mota, $gia, $iddm, $idloai, $idncc, $idnsx, $hinh])) {
                $msg = "<div class='alert alert-success'>✅ Thêm sản phẩm thành công!</div>";
            } else {
                $msg = "<div class='alert alert-danger'>❌ Thất bại. Kiểm tra lại Mã Sản Phẩm (có thể bị trùng).</div>";
            }
        } catch (PDOException $e) {
            $msg = "<div class='alert alert-danger'>❌ Lỗi Database: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Quicksand', sans-serif; background-color: #f8f9fa; }
        .card { border: none; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
        .card-header { background: linear-gradient(135deg, #36D1DC, #5B86E5); color: white; border-radius: 15px 15px 0 0 !important; padding: 20px; }
        .form-label { font-weight: 700; color: #555; }
        .form-control, .form-select { border-radius: 10px; padding: 10px 15px; border: 1px solid #eee; }
        .btn-save { background: linear-gradient(135deg, #11998e, #38ef7d); border: none; padding: 12px 30px; font-weight: bold; color: white; border-radius: 50px; transition: 0.3s; }
        .btn-save:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(56, 239, 125, 0.4); color: white;}
        .btn-back { background: #6c757d; color: white; border-radius: 50px; padding: 12px 25px; text-decoration: none; font-weight: bold; display: inline-block; }
        .img-preview-box { width: 100%; height: 250px; border: 2px dashed #ddd; border-radius: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fff; cursor: pointer; transition: 0.3s; }
        .img-preview-box img { max-width: 100%; max-height: 100%; object-fit: contain; display: none; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="m-0">🐶 Thêm Sản Phẩm Mới</h3>
                    <a href="list_sanpham.php" class="text-white text-decoration-none fw-bold">← Quay lại</a>
                </div>
                <div class="card-body p-5">
                    
                    <?= $msg ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-7">
                                
                                <div class="mb-3">
                                    <label class="form-label">Mã Sản Phẩm (ID) (*)</label>
                                    <input type="text" name="idSanPham" class="form-control" placeholder="Ví dụ: TA10, PK05..." required>
                                    <small class="text-muted">Nhập mã mới, không được trùng với mã đã có.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tên Sản Phẩm (*)</label>
                                    <input type="text" name="TenSanPham" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá Bán (*)</label>
                                        <input type="number" name="Gia" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Số Lượng</label>
                                        <input type="number" name="SoLuong" class="form-control" value="10">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Danh Mục</label>
                                        <select name="idDanhMuc" class="form-select" required>
                                            <option value="">-- Chọn --</option>
                                            <?php foreach($categories as $item): ?>
                                                <option value="<?= $item['idDanhMuc'] ?>"><?= $item['TenDanhMuc'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Loại Sản Phẩm</label>
                                        <select name="idLoaiSP" class="form-select" required>
                                            <option value="">-- Chọn Loại --</option>
                                            <?php foreach($types as $item): ?>
                                                <option value="<?= $item['idLoaiSP'] ?>"><?= $item['TenLoai'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nhà Cung Cấp</label>
                                        <select name="idNhaCungCap" class="form-select" required>
                                            <option value="">-- Chọn NCC --</option>
                                            <?php foreach($suppliers as $item): ?>
                                                <option value="<?= $item['idNhaCungCap'] ?>"><?= $item['TenNhaCungCap'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nhà Sản Xuất</label>
                                        <select name="idNhaSanXuat" class="form-select" required>
                                            <option value="">-- Chọn NSX --</option>
                                            <?php foreach($manufacturers as $item): ?>
                                                <option value="<?= $item['idNhaSanXuat'] ?>"><?= $item['TenNhaSanXuat'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô Tả</label>
                                    <textarea name="MoTa" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Hình Ảnh</label>
                                <input type="file" name="HinhAnh" id="fileInput" class="d-none" onchange="previewImage(this)">
                                <div class="img-preview-box" onclick="document.getElementById('fileInput').click()">
                                    <div id="uploadText" class="text-center text-muted">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i><br>Chọn ảnh
                                    </div>
                                    <img id="imgPreview" src="#">
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="add_sanpham.php?act=list_sanpham" class="btn-back me-2">Hủy</a>
                            <button type="submit" name="btn_save" class="btn-save">💾 Lưu Ngay</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('imgPreview').src = e.target.result;
                document.getElementById('imgPreview').style.display = 'block';
                document.getElementById('uploadText').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>