<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
// require_once '../Models/user_model.php'; 

// 1. KIỂM TRA ĐĂNG NHẬP VÀ GIỎ HÀNG
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vui lòng đăng nhập để tiến hành thanh toán.";
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: viewcart.php');
    exit();
}

// Lấy ID Khách hàng
$idKhachHang = $_SESSION['user_id'];
// Lấy thông tin khách hàng mặc định (nếu cần: Tên, SĐT, Email từ DB)
// $userInfo = getUserInfoById($idKhachHang); 

$tong = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận Thanh toán</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); display: flex; gap: 25px; }
        .order-summary { flex: 4; }
        .shipping-info { flex: 3; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f0f0f0; }
        .total-row td { background-color: #ffcccc; font-weight: bold; color: red; text-align: right; }
        .btn-checkout { background-color: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; float: right; font-size: 1.1em; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="order-summary">
            <h2>Đơn hàng của bạn</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <table>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>SL</th>
                    <th>Thành tiền</th>
                </tr>
                <?php foreach($_SESSION['cart'] as $sp): 
                    // Dựa vào cấu trúc giỏ hàng của bạn:
                    $ten_sp = $sp['name'] ?? 'Không rõ'; // Thêm kiểm tra tồn tại cho 'name'
                    $gia = $sp['price'] ?? 0;
                    $soluong = $sp['soluong'] ?? 0; // Thêm kiểm tra tồn tại cho 'soluong'
                    
                    $ttien = $gia * $soluong;
                    $tong += $ttien;
                    
                    // $hinh_anh = '../Public/images/' . $sp['image']; // Dòng này không cần thiết trong bảng checkout
                ?>
                <tr>
                    <td><?= htmlspecialchars($ten_sp); ?></td>
                    
                    <td><?= number_format($gia); ?></td>
                    
                    <td><?= $soluong; ?></td>
                    
                    <td><?= number_format($ttien); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3">TỔNG CỘNG:</td>
                    <td><?= number_format($tong); ?> VNĐ</td>
                </tr>
            </table>
            <a href="viewcart.php" style="color: #007bff; text-decoration: none;">&larr; Quay lại chỉnh sửa giỏ hàng</a>
        </div>

        <div class="shipping-info">
            <h2>Thông tin giao hàng</h2>
            <form action="../Process/checkout_process.php" method="POST">
                
                <div class="form-group">
                    <label for="name">Tên người nhận:</label>
                    <input type="text" id="name" name="name" required value="<?php echo $_SESSION['user_name'] ?? 'Khách hàng'; ?>"> 
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ nhận hàng (*):</label>
                    <textarea id="address" name="address" rows="3" required placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"></textarea>
                </div>

                <div class="form-group">
                    <label for="payment_method">Phương thức thanh toán:</label>
                    <select id="payment_method" name="payment_method" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                        <option value="BANK">Chuyển khoản ngân hàng</option>
                    </select>
                </div>
                
                <input type="hidden" name="total_amount" value="<?= $tong; ?>">
                
                <button type="submit" class="btn-checkout">HOÀN TẤT ĐẶT HÀNG</button>
            </form>
        </div>

    </div>
</body>
</html>