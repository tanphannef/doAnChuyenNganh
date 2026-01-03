<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// 1. Kết nối Controller Đơn Hàng
// Kiểm tra đường dẫn để tránh lỗi khi include
// Gọi file Model
if (file_exists("../Models/order_model.php")) {
    require_once "../Models/order_model.php";
} elseif (file_exists("Models/order_model.php")) {
    require_once "Models/order_model.php";
}

// Khởi tạo đối tượng (Vì trong Model giờ đã có class DonHang)
$orderModel = new DonHang(); 
$orders = $orderModel->getAllOrders();

$orderModel = new    DonHang();
$orders = $orderModel->getAllOrders();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản Lý Đơn Hàng</title>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fc; padding: 20px; color: #444; }
        
        /* Card Container */
        .card { background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); overflow: hidden; max-width: 1000px; margin: 0 auto; }
        .card-header { background: #4e73df; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .card-header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        
        /* Table Styles */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f3f9; padding: 12px; text-align: left; border-bottom: 2px solid #ddd; color: #333; font-weight: bold; }
        td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        tr:hover { background: #fafafa; }
        
        /* Status Badges (Màu sắc trạng thái) */
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; color: white; display: inline-block; }
        .bg-warning { background-color: #f6c23e; color: #333; } /* Chờ xử lý - Vàng */
        .bg-info { background-color: #36b9cc; } /* Đang giao - Xanh dương nhạt */
        .bg-success { background-color: #1cc88a; } /* Hoàn thành - Xanh lá */
        .bg-danger { background-color: #e74a3b; } /* Hủy - Đỏ */
        
        /* Nút bấm */
        .btn-view { background: #4e73df; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; transition: 0.2s;}
        .btn-view:hover { background: #2e59d9; }
        
        .btn-back { color: white; text-decoration: none; font-size: 14px; opacity: 0.8; }
        .btn-back:hover { opacity: 1; text-decoration: underline; }

        .price { font-weight: bold; color: #e74a3b; }
        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 15px;
            background: #6c757d; /* Màu xám */
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-back:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h2>🧾 Quản Lý Đơn Hàng</h2>
        <a href="dashboard.php" class="btn-back">&larr; Về Dashboard</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách Hàng (ID)</th>
                    <th>Ngày Đặt</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $row): ?>
                        <?php 
                            // === Xử lý màu sắc trạng thái ===
                            // Giả sử trạng thái trong DB lưu là số hoặc chữ, bạn chỉnh lại case bên dưới cho khớp nhé
                            $statusClass = 'bg-warning'; // Mặc định màu vàng
                            $statusText = $row['TrangThai'];

                            if ($statusText == 'Hoàn thành' || $statusText == 1) {
                                $statusClass = 'bg-success';
                            } elseif ($statusText == 'Đã hủy' || $statusText == 3) {
                                $statusClass = 'bg-danger';
                            } elseif ($statusText == 'Đang giao' || $statusText == 2) {
                                $statusClass = 'bg-info';
                            }
                        ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($row['idDonHang']) ?></strong></td>
                            
                            <td>
                                <span style="color:#666;">KH-<?= htmlspecialchars($row['idKhachHang']) ?></span>
                            </td>
                            
                            <td><?= date('d/m/Y', strtotime($row['NgayTao'])) ?></td>
                            
                            <td class="price">
                                <?= number_format($row['TongTien'], 0, ',', '.') ?> VNĐ
                            </td>
                            
                            <td>
                                <span class="badge <?= $statusClass ?>">
                                    <?= htmlspecialchars($row['TrangThai']) ?>
                                </span>
                            </td>
                            
                            <td>
                                <a href="chitietdonhang.php?idChitietdonhang=<?= $row['idDonHang'] ?>" class="btn-view">
                                    Xem Chi Tiết &#10140;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: #888;">Chưa có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>