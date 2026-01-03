<?php
session_start();
// Đảm bảo chỉ hiển thị trang này sau khi Đặt hàng thành công
if (!isset($_GET['order']) || !isset($_SESSION['message'])) {
    header('Location: ../index.php');
    exit();
}

$orderId = htmlspecialchars($_GET['order']);
$message = $_SESSION['message'];
unset($_SESSION['message']); // Xóa thông báo để không hiện lại
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cảm ơn bạn đã đặt hàng!</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; padding: 50px; }
        .thankyou-box { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-top: 5px solid #28a745; }
        .icon { font-size: 50px; color: #28a745; margin-bottom: 20px; }
        h1 { color: #333; }
        p { color: #666; font-size: 1.1em; }
        .order-details { margin-top: 30px; padding: 15px; border: 1px dashed #ccc; background-color: #f9f9f9; display: inline-block; }
        .btn-home { margin-top: 30px; display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="thankyou-box">
        <div class="icon">&#10003;</div>
        <h1>ĐẶT HÀNG THÀNH CÔNG!</h1>
        
        <p><?= htmlspecialchars($message); ?></p>
        
        <div class="order-details">
            <p style="margin: 0;">Mã đơn hàng của bạn là: <strong>#<?= $orderId; ?></strong></p>
            <p style="margin: 5px 0 0 0;">Chúng tôi sẽ xử lý đơn hàng sớm nhất có thể.</p>
        </div>
        
        <a href="../index.php" class="btn-home">Quay lại Trang Chủ</a>
        </div>
</body>
</html>