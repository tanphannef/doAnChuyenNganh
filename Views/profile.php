<?php
// File: Views/profile.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Đảm bảo đường dẫn include file Model chính xác (Nếu bạn dùng Front Controller thì có thể đã include ở index.php)
// require_once '../Models/db.php';
// require_once '../Models/khachhang_model.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?act=login');
    exit();
}

$idKhachHang = $_SESSION['user_id'];
// Gọi hàm lấy thông tin khách hàng
$userInfo = getKhachHangInfo($idKhachHang) ?? []; 

$donHangModel = new DonHang();
$orders = $donHangModel->getOrdersByUserId($idKhachHang);

// Lấy thông báo (Nếu có từ Process/update_profile.php)
$message = $_SESSION['profile_message'] ?? '';
$error = $_SESSION['profile_error'] ?? '';
unset($_SESSION['profile_message'], $_SESSION['profile_error']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ Cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Quicksand', sans-serif; background-color: #f8f9fa; }
        .profile-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            padding: 30px;
        }
        .profile-header {
            color: #007bff;
            font-weight: 700;
            margin-bottom: 25px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .nav-link.active {
            background-color: #007bff !important;
            color: white !important;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="profile-header text-center mb-5"><i class="fas fa-user-circle me-3"></i>Hồ sơ Cá nhân</h2>

    <?php if ($message): ?>
        <div class="alert alert-success text-center"><?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills profile-card" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-info-tab" data-bs-toggle="pill" href="#v-pills-info" role="tab" aria-controls="v-pills-info" aria-selected="true">
                    <i class="fas fa-id-card me-2"></i> Thông tin Cá nhân
                </a>
                <a class="nav-link" id="v-pills-orders-tab" data-bs-toggle="pill" href="#v-pills-orders" role="tab" aria-controls="v-pills-orders" aria-selected="false">
                    <i class="fas fa-shopping-bag me-2"></i> Đơn hàng Của tôi
                </a>
                <a class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">
                    <i class="fas fa-lock me-2"></i> Bảo mật & Mật khẩu
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content profile-card" id="v-pills-tabContent">
                
                <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
                    <h4>Cập nhật Thông tin</h4>
                    <p class="text-muted">Quản lý tên, email và số điện thoại của bạn.</p>
                    <hr>
                    <form action="../Process/update_profile_info.php" method="POST">
                        <div class="mb-3">
                            <label for="tenKhachHang" class="form-label">Họ và Tên</label>
                            <input type="text" class="form-control" id="tenKhachHang" name="tenKhachHang" 
                                value="<?= htmlspecialchars($userInfo['TenKhachHang'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailKH" class="form-label">Email</label>
                            <input type="email" class="form-control" id="emailKH" name="emailKH" 
                                value="<?= htmlspecialchars($userInfo['EmailKH'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="sdt" name="sdt" 
                                value="<?= htmlspecialchars($userInfo['Sdt'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="diaChi" class="form-label">Địa chỉ (Địa chỉ này sẽ dùng cho đơn hàng mặc định)</label>
                            <textarea class="form-control" id="diaChi" name="diaChi" rows="3"><?= htmlspecialchars($userInfo['DiaChi'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                    </form>
                </div>

<div class="tab-pane fade" id="v-pills-orders" role="tabpanel" aria-labelledby="v-pills-orders-tab">
                    <h4>Lịch sử Đơn hàng</h4>
                    <p class="text-muted">Theo dõi trạng thái các đơn hàng bạn đã đặt.</p>
                    <hr>
                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Mã Đơn</th>
                                        <th scope="col">Ngày Đặt</th>
                                        <th scope="col">Tổng Tiền</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <th scope="row">#<?= htmlspecialchars($order['idDonHang']) ?></th>
                                        <td><?= date('d/m/Y', strtotime($order['NgayTao'])) ?></td>
                                        <td><?= number_format($order['TongTien'], 0, ',', '.') ?> VNĐ</td>
                                        <td>
                                            <span class="badge 
                                                <?php 
                                                    // CSS tùy theo trạng thái
                                                    $status = htmlspecialchars($order['TrangThai']);
                                                    if ($status == 'Đã giao hàng') echo 'bg-success';
                                                    elseif ($status == 'Chờ xác nhận') echo 'bg-warning text-dark';
                                                    elseif ($status == 'Đang vận chuyển') echo 'bg-primary';
                                                    else echo 'bg-secondary';
                                                ?>">
                                                <?= $status ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="index.php?act=order_detail&id=<?= $order['idDonHang'] ?>" class="btn btn-sm btn-info text-white">
                                                Xem <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-box-open me-2"></i>Bạn chưa có đơn hàng nào được đặt.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                    <h4>Thay đổi Mật khẩu</h4>
                    <p class="text-muted">Hãy đảm bảo mật khẩu của bạn là duy nhất và mạnh mẽ.</p>
                    <hr>
                    <form action="../Process/update_password.php" method="POST">
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Mật khẩu Cũ</label>
                            <input type="password" class="form-control" id="old_password" name="old_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu Mới</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận Mật khẩu Mới</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Đổi Mật khẩu</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>