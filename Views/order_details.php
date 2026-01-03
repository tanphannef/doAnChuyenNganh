<?php
// File: Views/order_detail.php

// Đảm bảo session và Models đã được nạp ở index.php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?act=login');
    exit();
}

// Khởi tạo Model và lấy chi tiết đơn hàng
// Biến $idDonHang được lấy từ index.php
$donHangModel = new DonHang(); 
$order = $donHangModel->getOrderDetails($idDonHang);

// Kiểm tra quyền sở hữu (Nếu đơn hàng không tồn tại hoặc không phải của user này)
if (!$order || $order['idKhachHang'] != $_SESSION['user_id']) {
    $_SESSION['error'] = "Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập.";
    header('Location: index.php?act=profile');
    exit();
}

// Hàm hỗ trợ hiển thị badge trạng thái
function displayStatusBadge($status) {
    $class = 'bg-secondary';
    if ($status == 'Đã giao hàng') $class = 'bg-success';
    elseif ($status == 'Chờ xác nhận') $class = 'bg-warning text-dark';
    elseif ($status == 'Đang vận chuyển') $class = 'bg-primary';
    return "<span class='badge {$class}'>{$status}</span>";
}

$tong_thanh_tien = 0; // Để tính tổng tiền các sản phẩm (nên khớp với TongTien của đơn hàng)
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-primary mb-4">
                <i class="fas fa-clipboard-list me-2"></i>Chi tiết Đơn hàng #<?= htmlspecialchars($order['idDonHang']) ?>
            </h2>
        </div>
    </div>
    
    <div class="row">
        
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin Chung</h5>
                </div>
                <div class="card-body">
                    <p><strong>Ngày đặt hàng:</strong> <?= date('d/m/Y H:i', strtotime($order['NgayTao'])) ?></p>
                    <p><strong>Trạng thái:</strong> <?= displayStatusBadge($order['TrangThai']) ?></p>
                    <p><strong>Tổng giá trị:</strong> <span class="text-danger fw-bold fs-5"><?= number_format($order['TongTien'], 0, ',', '.') ?> VNĐ</span></p>
                    <hr>
                    <h6><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ Giao hàng</h6>
                    <p class="mb-1"><strong>Người nhận:</strong> <?= htmlspecialchars($order['TenKhachHang'] ?? 'N/A') ?></p>
                    <p class="mb-1"><strong>Điện thoại:</strong> <?= htmlspecialchars($order['Sdt'] ?? 'N/A') ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['DiaChiNhanHang'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Sản phẩm đã mua</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php 
                        if (!empty($order['items'])):
                            foreach ($order['items'] as $item): 
                                $thanh_tien = $item['Gia'] * $item['SoLuong'];
                                $tong_thanh_tien += $thanh_tien;
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <img src="Public/images/<?= htmlspecialchars($item['HinhAnh'] ?? 'default.jpg') ?>" 
                                     alt="<?= htmlspecialchars($item['TenSanPham']) ?>" 
                                     class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <strong><?= htmlspecialchars($item['TenSanPham']) ?></strong>
                                <br><small class="text-muted">Mã SP: <?= htmlspecialchars($item['idSanPham']) ?></small>
                            </div>
                            <div class="text-end">
                                <span class="text-secondary"><?= number_format($item['Gia'], 0, ',', '.') ?> VNĐ x <?= $item['SoLuong'] ?></span>
                                <br><strong class="text-danger"><?= number_format($thanh_tien, 0, ',', '.') ?> VNĐ</strong>
                            </div>
                        </li>
                        <?php endforeach; endif; ?>
                        
                        <li class="list-group-item bg-light text-end">
                            <span class="fs-5 me-3">Thành tiền (<?= count($order['items'] ?? []) ?> sản phẩm):</span>
                            <span class="text-danger fw-bold fs-4"><?= number_format($order['TongTien'], 0, ',', '.') ?> VNĐ</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="index.php?act=profile" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Quay lại Hồ sơ cá nhân</a>
        </div>
    </div>

</div>