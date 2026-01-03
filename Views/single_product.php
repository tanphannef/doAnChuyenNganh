<?php 
// File: Views/single_product.php

if (empty($productDetail)) {
    echo '<div class="container py-5 text-center"><h1>Sản phẩm không tồn tại.</h1></div>';
    return;
}

$idSanPham = $productDetail['idSanPham'];
$tenSanPham = htmlspecialchars($productDetail['TenSanPham']);
$gia = number_format($productDetail['Gia'], 0, ',', '.') . ' VNĐ';
$moTa = nl2br(htmlspecialchars($productDetail['MoTa'])); 
$tenDM = htmlspecialchars($productDetail['TenDanhMuc'] ?? 'Chưa rõ'); // Sửa khóa thành TenDanhMuc
$tenNCC = htmlspecialchars($productDetail['TenNhaCungCap'] ?? 'Chưa rõ'); // Sửa khóa thành TenNhaCungCap
$image = htmlspecialchars($productDetail['Image']); // Sửa khóa thành Image

// Xử lý hình ảnh (đường dẫn tương đối)
$img_src = "Public/images/default.png"; 
if (!empty($image) && $image != '1') {
    if (strpos($image, 'http') !== false) {
        $img_src = $image;
    } else {
        $img_src = "Public/images/" . $image;
    }
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <img src="<?= $img_src ?>" class="img-fluid rounded-start p-3" alt="<?= $tenSanPham ?>" style="max-height: 500px; object-fit: contain;">
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="display-5 fw-bold mb-3"><?= $tenSanPham ?></h1>
            
            <p class="lead text-danger fw-bolder mb-4" style="font-size: 2rem;"><?= $gia ?></p>

            <hr>

            <div class="mb-4">
                <p class="mb-1"><strong>Danh mục:</strong> <span class="badge bg-primary"><?= $tenDM ?></span></p>
                <p class="mb-1"><strong>Nhà cung cấp:</strong> <span class="badge bg-secondary"><?= $tenNCC ?></span></p>
                <p class="mb-1"><strong>Tình trạng:</strong> 
                    <?php if ($productDetail['SoLuong'] > 0): ?>
                        <span class="text-success fw-bold">Còn hàng (<?= $productDetail['SoLuong'] ?>)</span>
                    <?php else: ?>
                        <span class="text-danger fw-bold">Hết hàng</span>
                    <?php endif; ?>
                </p>
            </div>
            
            <?php if ($productDetail['SoLuong'] > 0): ?>
                <form action="index.php?act=addcart" method="post" class="mb-4">
                    <input type="hidden" name="idSanPham" value="<?= $idSanPham ?>">
                    <input type="hidden" name="img" value="<?= $image ?>">
                    <input type="hidden" name="tenSanPham" value="<?= $tenSanPham ?>">
                    <input type="hidden" name="Gia" value="<?= $productDetail['Gia'] ?>">

                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="soluong" class="col-form-label">Số lượng:</label>
                        </div>
                        <div class="col-3">
                            <input type="number" id="soluong" name="soluong" value="1" min="1" max="<?= $productDetail['SoLuong'] ?>" class="form-control text-center">
                        </div>
                        <div class="col-auto">
                            <button type="submit" name="addtocart" class="btn btn-lg text-white" style="background-color: #5B86E5; border-color: #5B86E5;">
                                <i class="fas fa-cart-plus me-2"></i> Thêm vào Giỏ hàng
                            </button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <button type="button" class="btn btn-secondary btn-lg mb-4" disabled>Sản phẩm đã Hết hàng</button>
            <?php endif; ?>

            <hr>

            <h4 class="mb-3">Mô tả Sản phẩm</h4>
            <div class="text-muted" style="line-height: 1.8;">
                <?= $moTa ?>
            </div>
            
        </div>
    </div>
</div>