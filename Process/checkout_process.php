<?php
// File: Process/checkout_process.php
session_start();

// 1. NHÚNG CÁC MODEL CẦN THIẾT
require_once "../Models/order_model.php"; 
require_once "../Models/db.php"; 

// --- ĐỊNH NGHĨA BIẾN LỖI (Để đảm bảo không có warning Undefined variable) ---
$login_error = false;
$cart_error = false;

$donHangModel = new DonHang();

// 2. KIỂM TRA PHƯƠNG THỨC VÀ GIỎ HÀNG
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Views/viewcart.php');
    exit();
}

// Kiểm tra giỏ hàng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Giỏ hàng của bạn đang trống.";
    header('Location: ../Views/viewcart.php');
    exit();
}

// Kiểm tra đăng nhập (user_id)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vui lòng đăng nhập để hoàn tất đơn hàng.";
    header('Location: ../Views/login.php');
    exit();
}

// 3. LẤY VÀ KHỞI TẠO DỮ LIỆU TỪ POST VÀ SESSION
// Lỗi Undefined variable được khắc phục bằng cách định nghĩa các biến này ở đây.
$idKhachHang = $_SESSION['user_id'];
// Lấy dữ liệu từ FORM (Views/checkout.php)
$TongTien = (float)($_POST['total_amount'] ?? 0); 
$DiaChiNhanHang = trim($_POST['address'] ?? ''); 
$cartItems = $_SESSION['cart'];


// 4. KIỂM TRA DỮ LIỆU CẦN THIẾT
if (empty($DiaChiNhanHang) || $TongTien <= 0) {
    $_SESSION['error'] = "Vui lòng cung cấp đầy đủ địa chỉ và đảm bảo tổng tiền hợp lệ.";
    header('Location: ../Views/checkout.php');
    exit();
}


// 5. CHUYỂN DỮ LIỆU GIỎ HÀNG SANG ĐỊNH DẠNG CÓ TÊN (cho hàm placeOrder)
$itemsToOrder = [];
foreach ($cartItems as $item) {
    $itemsToOrder[] = [
    'idSanPham' => $item['id'],
    'SoLuong' => $item['soluong'], 
    'Gia' => $item['price'] 
    ];
}

$idKhachHang = $_SESSION['user_id']; 

// --- DÒNG CODE DEBUG MỚI: ---
echo "ID Khách hàng đang sử dụng: " . $idKhachHang . "<br>";
// -----------------------------

// 6. GỌI MODEL ĐỂ ĐẶT HÀNG
$orderId = $donHangModel->placeOrder($idKhachHang, $TongTien, $DiaChiNhanHang, $itemsToOrder);

if ($orderId) {
    // Đặt hàng thành công
    unset($_SESSION['cart']); 
    $_SESSION['message'] = "Đơn hàng của bạn (#" . $orderId . ") đã được đặt thành công!";
    header('Location: ../Views/thankyou.php?order=' . $orderId); // Dòng 61 (ĐÃ THÊM EXIT)
    exit(); // BẮT BUỘC
} else {
    // Đặt hàng thất bại (Do lỗi DB, Transaction Rollback)
    $_SESSION['error'] = "Đã xảy ra lỗi trong quá trình đặt hàng. Vui lòng thử lại.";
    header('Location: ../Views/checkout.php');
    exit(); // BẮT BUỘC
}
?>