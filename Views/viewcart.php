<?php
// Views/viewcart.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy thông báo từ URL nếu có (từ Controllers/delcart.php gửi sang)
$message = '';
if (isset($_GET['msg'])) {
    $message = htmlspecialchars(urldecode($_GET['msg']));
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng Của Bạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> 
        body { font-family: 'Quicksand', sans-serif; background-color: #f8f9fa; }
        .cart-title { color: #5B86E5; font-weight: bold; margin-bottom: 25px; }
        .table-primary { background: linear-gradient(135deg, #36D1DC 0%, #5B86E5 100%) !important; color: white; }
        .btn-continue { background-color: #28a745; }
        .btn-checkout { background-color: #007bff; }
        .img-product { object-fit: cover; border-radius: 4px; }
        .btn-delete { background-color: #dc3545; color: white; text-decoration: none; padding: 5px 10px; border-radius: 5px; } 
    </style>
</head>
<body>
    <div class="container py-5">
        <?php if (!empty($message)): ?>
            <div class="alert alert-success text-center mb-4" style="max-width: 600px; margin: 0 auto;">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <h2 class="cart-title text-center">Đơn Hàng Của Bạn</h2>
        <table class="table table-bordered align-middle shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>Số Đơn Hàng</th>
                    <th>Hình Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Đơn Giá</th>
                    <th>Số Lượng</th>
                    <th>Thành Tiền</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $tong = 0;
                    $i = 0;
                    foreach($_SESSION['cart'] as $index => $sp):
                        
                        $gia = $sp['price'];
                        $soluong = $sp['soluong'];
                        $ttien = $gia * $soluong;
                        $tong += $ttien;
                        
                        $hinh_anh = 'Public/images/' . $sp['image'];
                ?>
                    <tr>
                        <td><?= ($i + 1) ?></td>
                        <td>
                            <img src="<?= $hinh_anh ?>" width="80" height="80" class="img-product" onerror="this.src='https://via.placeholder.com/80?text=No+Image'">
                        </td>
                        <td style="text-align: left;"><?= htmlspecialchars($sp['name']) ?></td>
                        <td><?= number_format($gia) ?> VNĐ</td>
                        <td><?= $soluong ?></td>
                        <td style="font-weight: bold; color: #dc3545;"><?= number_format($ttien) ?> VNĐ</td>
                        <td style="text-align:center">
                            <a href="index.php?act=delcart&index=<?= $index ?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php 
                    $i++;
                    endforeach; 
                ?>
                
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">Tổng Đơn Hàng:</td>
                    <td colspan="2" style="background-color: #ffcccc; font-weight: bold; color: red;">
                        <?= number_format($tong); ?> VNĐ
                    </td>
                </tr>
                
                <tr>
                    <td colspan="7" style="text-align: right; padding: 15px;">
                        <a href="index.php" class="btn btn-continue me-3">
                            <i class="fas fa-arrow-left"></i> Tiếp Tục Mua Hàng
                        </a>

                        <a href="index.php?act=delcart&clear=1" class="btn btn-delete me-3" onclick="return confirm('Bạn có chắc muốn xóa hết giỏ hàng?');">
                            <i class="fas fa-trash-alt"></i> Xóa Giỏ Hàng
                        </a>>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="./Views/shipping_info.php" class="btn btn-checkout">
                                Tiến hành Thanh toán <i class="fas fa-arrow-right"></i>
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-warning" style="color: black;">
                                Đăng nhập để Thanh toán <i class="fas fa-user"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
<?php 
} else {
    // Trường hợp giỏ hàng trống
    echo '<div class="container py-5">
            <div class="alert alert-info text-center p-5 shadow-sm">
                <i class="fas fa-shopping-cart fa-3x mb-3" style="color: #5B86E5;"></i>
                <h3>Giỏ Hàng Trống</h3>
                <p>Bạn chưa có sản phẩm nào trong giỏ. Hãy thêm vào nhé!</p>
                <a href="./index.php" class="btn btn-continue"><i class="fas fa-plus"></i> Quay lại đặt hàng</a>
            </div>
          </div>';
}
?>