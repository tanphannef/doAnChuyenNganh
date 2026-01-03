<?php
// File: Views/shipping_info.php
session_start();
require_once '../Models/khachhang_model.php'; 

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
     $_SESSION['error'] = "Vui lòng đăng nhập để thêm thông tin giao hàng.";
        header('Location: login.php');
        exit();
}

$idKhachHang = $_SESSION['user_id'];
// *LƯU Ý: Đảm bảo hàm getKhachHangInfo() trả về mảng dữ liệu hoặc false/null*
$userInfo = getKhachHangInfo($idKhachHang) ?? []; // Sử dụng ?? [] để tránh lỗi nếu không tìm thấy user
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin Giao hàng</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        .shipping-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .shipping-title {
            color: #007bff;
            font-weight: 700;
            margin-bottom: 25px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            text-align: center;
        }
        .form-control, textarea {
            border-radius: 5px;
        }
        .btn-checkout {
            background-color: #28a745; /* Màu xanh lá (Thành công/Tiến lên) */
            color: white;
            font-weight: 600;
            transition: background-color 0.3s;
            width: 100%; /* Kéo dài hết chiều rộng */
        }
        .btn-checkout:hover {
            background-color: #1e7e34;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="shipping-container"> 
        <h2 class="shipping-title">Thông tin Giao hàng</h2>
        <p class="text-secondary text-center">Xác nhận hoặc điền thông tin chi tiết để tiến hành thanh toán.</p>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="../Process/shipping_info_process.php" method="POST">
            
            <div class="mb-3">
                <label for="tenKhachHang" class="form-label">Họ và Tên:</label>
                <input type="text" name="tenKhachHang" id="tenKhachHang" class="form-control" required value="<?= htmlspecialchars($userInfo['TenKhachHang'] ?? $_SESSION['user_name'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label for="sdt" class="form-label">Số điện thoại:</label>
                <input type="text" name="sdt" id="sdt" class="form-control" required value="<?= htmlspecialchars($userInfo['Sdt'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label for="emailKH" class="form-label">Email:</label>
                <input type="email" name="emailKH" id="emailKH" class="form-control" required value="<?= htmlspecialchars($userInfo['EmailKH'] ?? ''); ?>">
            </div>
            
            <div class="mb-4">
                <label for="diaChi" class="form-label">Địa chỉ chi tiết:</label>
                <textarea name="diaChi" id="diaChi" class="form-control" rows="3" required><?= htmlspecialchars($userInfo['DiaChi'] ?? ''); ?></textarea>
            </div>
            
            <input type="hidden" name="idRole" value="<?= $_SESSION['user_role'] ?? 2; ?>">
            
            <button type="submit" class="btn btn-checkout">
                Tiếp tục Thanh toán &rarr;
            </button>
        </form>
        
        <p class="mt-3 text-center text-muted"><small>Thông tin của bạn sẽ được bảo mật tuyệt đối.</small></p>
    </div>
</body>
</html>