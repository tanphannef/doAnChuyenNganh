<?php
// File: Views/favorites.php

// 1. Logic chuẩn bị dữ liệu (Nếu dùng Front Controller, ta sẽ giả định các biến này đã có)
// Nếu chưa có, ta phải đảm bảo nó được khởi tạo ở đây hoặc ở file Controller chính.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Lấy danh sách yêu thích để hiển thị
$favorites = $_SESSION['favorites'];
$total_items = count($favorites);

// Xử lý thông báo (từ Controller 'add_favorite.php' chuyển hướng đến)
$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);


// ******************************************************************
// *** LƯU Ý QUAN TRỌNG:
// *** PHẦN XỬ LÝ POST (THÊM/XÓA) ĐÃ BỊ LOẠI BỎ KHỎI VIEW NÀY.
// *** HÀNH ĐỘNG XÓA SẼ TRUYỀN VỀ CONTROLLER CHÍNH
// ******************************************************************
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Yêu Thích</title>
    <style>
        /* CSS CƠ BẢN ĐỂ ĐỒNG BỘ (Bạn có thể bỏ qua nếu đã có file style chung) */
        body { font-family: 'Quicksand', sans-serif; background-color: #f8f9fa; }
        .favorites_container { max-width: 960px; margin: 0 auto; padding: 0 15px; }
        
        .favorites-header {
            background-color: #fcf9f5;
            padding: 40px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        .favorites-title {
            font-family: 'Quicksand', sans-serif;
            font-weight: 700;
            color: #333;
            font-size: 2.5rem;
        }
        
        /* Cấu trúc item trong danh sách yêu thích (tương tự item giỏ hàng) */
        .favorite-item {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            padding: 15px;
        }
        .favorite-img {
            width: 80px;
            height: 80px;
            overflow: hidden;
            border-radius: 8px;
            margin-right: 15px;
            flex-shrink: 0;
            background: #f9f9f9;
        }
        .favorite-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .favorite-details {
            flex-grow: 1;
        }
        .favorite-details h5 {
            margin-top: 0;
            font-size: 1.1rem;
            color: #333;
        }
        .favorite-price {
            color: #FF416C;
            font-weight: bold;
            font-size: 1rem;
        }
        
        /* Nút hành động (Xóa và Thêm giỏ) */
        .favorite-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .btn-action {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-add-to-cart {
            background: #1cc88a; /* Màu xanh lá */
            color: white;
        }
        .btn-add-to-cart:hover {
            background: #17a673;
        }
        .btn-remove {
            background: #ff416c; /* Màu đỏ */
            color: white;
            font-size: 1.1rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .btn-remove:hover {
            background: #e03b60;
        }
        
        /* Thông báo */
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="favorites-header">
    <div class="favorites_container">
        <h1 class="favorites-title">❤️ Danh Sách Yêu Thích</h1>
        <p class="text-muted">Bạn có <span class="fw-bold"><?= $total_items ?></span> sản phẩm yêu thích.</p>
    </div>
</div>

<div class="container pb-5">
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($total_items > 0): ?>
        
        <?php foreach ($favorites as $item): ?>
            <div class="favorite-item">
                <div class="favorite-img">
                    <img src="Public/images/<?= htmlspecialchars($item['Image']) ?>" alt="<?= htmlspecialchars($item['TenSanPham']) ?>" onerror="this.src='https://via.placeholder.com/80?text=SP'">
                </div>
                
                <div class="favorite-details">
                    <h5 class="fw-bold"><?= htmlspecialchars($item['TenSanPham']) ?></h5>
                    <div class="favorite-price"><?= number_format($item['Gia'], 0, ',', '.') ?> VNĐ</div>
                </div>
                
                <div class="favorite-actions">
                    <form action="./Controllers/addcart.php" method="POST">
                        <input type="hidden" name="idSanPham" value="<?= $item['idSanPham'] ?>">
                        <input type="hidden" name="tenSanPham" value="<?= htmlspecialchars($item['TenSanPham']) ?>">
                        <input type="hidden" name="Gia" value="<?= $item['Gia'] ?>">
                        <input type="hidden" name="img" value="<?= $item['Image'] ?>">
                        <input type="hidden" name="addtocart" value="1">
                        <button type="submit" class="btn-action btn-add-to-cart">
                            <i class="fas fa-shopping-cart"></i> Thêm Giỏ
                        </button>
                    </form>
                    
                    <form action="index.php" method="GET">
                        <input type="hidden" name="act" value="remove_favorite"> <input type="hidden" name="idSanPham" value="<?= $item['idSanPham'] ?>">
                        <button type="submit" class="btn-action btn-remove" title="Xóa khỏi Yêu thích">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div style="text-align: right; margin-top: 30px;">
            <a href="index.php?act=shop" style="text-decoration: none; color: #4e73df; font-weight: 600;">
                <i class="fas fa-arrow-left me-2"></i> Hãy mau thêm sản phẩm vào mục yêu thích
            </a>
        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 80px 0; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <img src="https://cdn-icons-png.flaticon.com/512/825/825656.png" width="80" class="mb-3">
            <h4>Danh sách yêu thích của bạn đang trống!</h4>
            <p class="text-muted">Hãy thêm những sản phẩm bạn thích vào đây để dễ dàng tìm lại nhé.</p>
            <a href="index.php?act=shop" class="btn-action btn-add-to-cart" style="background: #4e73df; margin-top: 15px;">
                <i class="fas fa-shopping-bag"></i> Mua sắm ngay
            </a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>