<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center" style="color: #FF416C;"><i class="fas fa-credit-card me-2"></i> Tiến Hành Thanh Toán</h2>
    
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    Thông Tin Nhận Hàng
                </div>
                <div class="card-body">
                    <form action="process_checkout.php" method="POST">
                        <div class="mb-3">
                            <label for="hoten" class="form-label">Họ và Tên (*)</label>
                            <input type="text" class="form-control" id="hoten" name="hoten" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email (*)</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="sdt" class="form-label">Số Điện Thoại (*)</label>
                            <input type="tel" class="form-control" id="sdt" name="sdt" required>
                        </div>
                        <div class="mb-3">
                            <label for="diachi" class="form-label">Địa Chỉ Nhận Hàng (*)</label>
                            <textarea class="form-control" id="diachi" name="diachi" rows="3" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ghichu" class="form-label">Ghi Chú Đơn Hàng (Không bắt buộc)</label>
                            <textarea class="form-control" id="ghichu" name="ghichu" rows="2"></textarea>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="fw-bold">Phương Thức Thanh Toán</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                                <label class="form-check-label" for="cod">
                                    <i class="fas fa-money-bill-wave me-1"></i> Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank" value="BANK">
                                <label class="form-check-label" for="bank">
                                    <i class="fas fa-university me-1"></i> Chuyển khoản ngân hàng
                                </label>
                            </div>
                        </div>

                        <hr>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg fw-bold">HOÀN TẤT ĐẶT HÀNG</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark fw-bold">
                    Tóm Tắt Đơn Hàng
                </div>
                <div class="card-body">
                    <?php 
                    $tong_hang = 0;
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            $tong_hang += $item['price'] * $item['soluong'];
                        }
                    }
                    $shipping_fee = 30000; // Phí vận chuyển giả định
                    $total_final = $tong_hang + $shipping_fee;
                    ?>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tổng tiền hàng:</span>
                            <span class="fw-bold"><?= number_format($tong_hang, 0, ',', '.') ?> VNĐ</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success fw-bold"><?= number_format($shipping_fee, 0, ',', '.') ?> VNĐ</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <h5 class="mb-0">TỔNG CỘNG:</h5>
                            <h5 class="mb-0 text-danger fw-bold"><?= number_format($total_final, 0, ',', '.') ?> VNĐ</h5>
                        </li>
                    </ul>
                    <small class="text-muted">Bạn có <?= count($_SESSION['cart'] ?? []) ?> sản phẩm trong giỏ hàng.</small>
                </div>
            </div>
        </div>
    </div>
</div>