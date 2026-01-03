<style>
    /* CSS Riêng cho trang Cửa Hàng */
    .shop-header {
        background-color: #fcf9f5;
        padding: 40px 0;
        text-align: center;
        margin-bottom: 40px;
    }
    .shop-title {
        font-family: 'Quicksand', sans-serif;
        font-weight: 700;
        color: #333;
        font-size: 2.5rem;
    }
    /* Card sản phẩm */
    .product-grid-card {
        border: 1px solid #eee;
        border-radius: 15px;
        overflow: hidden;
        transition: 0.3s;
        height: 100%;
        background: #fff;
    }
    .product-grid-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(-5px);
        border-color: #5B86E5;
    }
    .img-wrapper {
        height: 250px;
        overflow: hidden;
        position: relative;
        background: #f9f9f9;
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Giúp ảnh không bị cắt mất góc */
        transition: 0.5s;
    }
    .product-grid-card:hover .img-wrapper img {
        transform: scale(1.08);
    }
    .card-info {
        padding: 15px;
        text-align: center;
    }
    .shop-price {
        color: #FF416C;
        font-weight: bold;
        font-size: 1.1rem;
        margin: 10px 0;
    }
    .btn-buy-now {
        background: linear-gradient(135deg, #36D1DC 0%, #5B86E5 100%);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.9rem;
        width: 100%;
        transition: 0.3s;
    }
    .btn-buy-now:hover {
        background: linear-gradient(135deg, #5B86E5 0%, #36D1DC 100%);
        box-shadow: 0 4px 15px rgba(91, 134, 229, 0.3);
    }
    .btn-wishlist {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        color: #FF416C;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .btn-wishlist:hover {
        background: #FF416C;
        color: white;
        border-color: #FF416C;
        box-shadow: 0 4px 10px rgba(255, 65, 108, 0.3);
    }
</style>

<div class="shop-header">
    <div class="container">
        <h1 class="shop-title">🛍️ Tất Cả Sản Phẩm</h1>
        <p class="text-muted">Khám phá thế giới đồ dùng, thức ăn tốt nhất cho thú cưng của bạn</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <?php if (!empty($all_products)): ?>
            <?php foreach ($all_products as $sp): ?>
                <?php
                    // --- XỬ LÝ ẢNH THÔNG MINH ---
                    $tenAnh = $sp['Image']; 
                    $img_src = "Public/images/default.png"; 

                    if (!empty($tenAnh) && $tenAnh != '1') {
                        if (strpos($tenAnh, 'http') !== false) {
                            $img_src = $tenAnh;
                        } 
                        else {
                            $img_src = "Public/images/" . $tenAnh;
                        }
                    }
                    
                    // --- ĐÃ SỬA ĐỔI ĐƯỜNG DẪN YÊU THÍCH (BỎ ../) ---
                    // Trỏ về index.php với act=add_favorite
                    $favorite_link_for_controller = "index.php?act=add_favorite" . 
                        "&idSanPham=" . urlencode($sp['idSanPham']) . 
                        "&tenSanPham=" . urlencode($sp['TenSanPham']) . 
                        "&Gia=" . urlencode($sp['Gia']) . 
                        "&Image=" . urlencode($sp['Image']);
                    // --- KẾT THÚC SỬA ĐỔI ---
                    $product_id = htmlspecialchars($sp['idSanPham']);
                    $detail_link = "index.php?act=single_product&id=" . $sp['idSanPham'];
                ?>
                
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-grid-card">
                        <a href="<?= $favorite_link_for_controller ?>" class="btn-wishlist" title="Thêm vào Yêu thích">
                            <i class="far fa-heart"></i>
                        </a>
                    <div class="img-wrapper">
                        <a href="<?php echo $detail_link; ?>"> 
                            <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($sp['TenSanPham']) ?>" onerror="this.src='https://via.placeholder.com/300?text=No+Image'">
                        </a>
                    </div>
                        <div class="card-info">
                            <h5 class="fw-bold text-truncate"><?= htmlspecialchars($sp['TenSanPham']) ?></h5>
                            <div class="shop-price"><?= number_format($sp['Gia'], 0, ',', '.') ?> VNĐ</div>
                            
                            <div style="display: flex; gap: 10px; align-items: center;">
                                
                                <form action="./Controllers/addcart.php" method="post" style="flex-grow: 1;">
                                    <input type="hidden" name="idSanPham" value="<?= $sp['idSanPham'] ?>">
                                    <input type="hidden" name="img" value="<?= $sp['Image'] ?>">
                                    <input type="hidden" name="tenSanPham" value="<?= htmlspecialchars($sp['TenSanPham']) ?>">
                                    <input type="hidden" name="Gia" value="<?= $sp['Gia'] ?>">
                                    
                                    <button type="submit" name="addtocart" class="btn-buy-now">
                                        <i class="fas fa-shopping-cart me-1"></i> Thêm Giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="100" class="mb-3">
                <h4>Chưa có sản phẩm nào trong cửa hàng!</h4>
                <p>Hãy thêm sản phẩm từ trang Admin.</p>
            </div>
        <?php endif; ?>
    </div>
</div>