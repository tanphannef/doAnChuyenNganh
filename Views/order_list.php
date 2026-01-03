<?php
// File: Views/Admin/quanlydonhang.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// LƯU Ý: ĐIỀU CHỈNH ĐƯỜNG DẪN NÀY CHO ĐÚNG
// Nếu file này nằm trong Views/Admin, đường dẫn có thể là:
require_once '../Models/order_model.php'; 

$orders = [];
$errorMessage = '';
$statusFilter = $_GET['status'] ?? 'All';

// Lấy thông báo từ session (nếu có sau khi cập nhật trạng thái)
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

// --- LOGIC: Lấy Danh sách Đơn hàng ---
try {
    // Gọi hàm Model để lấy dữ liệu (Hàm này đã được định nghĩa trong order_model.php)
    $orders = getAllOrders($statusFilter); 

} catch (Exception $e) {
    $errorMessage = "Lỗi khi tải danh sách đơn hàng: " . $e->getMessage();
    error_log("Lỗi tải đơn hàng: " . $e->getMessage());
}

$statuses = ['All', 'Chờ xác nhận', 'Đang xử lý', 'Đang giao hàng', 'Đã giao hàng', 'Đã hủy'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-filter a { margin-right: 10px; text-decoration: none; padding: 5px 10px; border: 1px solid #ccc; border-radius: 4px; }
        .status-filter a.active { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <h1>Danh sách Đơn hàng</h1>
    
    <?php if (!empty($message)): ?>
        <p style="color: green; font-weight: bold;"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
        <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <div class="status-filter">
        <?php foreach ($statuses as $s): ?>
            <a href="?status=<?= urlencode($s); ?>" class="<?= ($statusFilter == $s) ? 'active' : ''; ?>">
                <?= $s; ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <?php if (count($orders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID ĐH</th>
                    <th>Khách hàng</th>
                    <th>Ngày tạo</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['idDonHang']; ?></td>
                    <td><?php echo htmlspecialchars($order['TenKhachHang']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['NgayTao'])); ?></td>
                    <td><?php echo number_format($order['TongTien']); ?> VNĐ</td>
                    <td><span style="font-weight: bold; color: <?= ($order['TrangThai'] == 'Đã giao hàng' ? 'green' : 'orange'); ?>;"><?php echo $order['TrangThai']; ?></span></td>
                    <td>
                        <a href="order_details.php?id=<?php echo $order['idDonHang']; ?>">Chi tiết</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không tìm thấy đơn hàng nào trong trạng thái "<?php echo $statusFilter; ?>".</p>
    <?php endif; ?>

    <p><a href="quanlydonhang.php">Quay Lại Dashboard</a></p>
</body>
</html>