<?php
// Controllers/delcart.php
// Logic xóa sản phẩm khỏi Giỏ hàng (dùng ID sản phẩm hoặc xóa toàn bộ)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. XỬ LÝ XÓA TOÀN BỘ GIỎ HÀNG
if (isset($_GET['clear']) && $_GET['clear'] == 1) {
    
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
        $_SESSION['cart_message'] = "Đã xóa toàn bộ giỏ hàng.";
    }

} 
// 2. XỬ LÝ XÓA TỪNG SẢN PHẨM (THEO ID SẢN PHẨM)
// Đây là logic được đồng bộ với remove_favorite.php (Xóa dựa trên ID)
else if (isset($_GET['index']) && is_numeric($_GET['index'])) { // <-- KIỂM TRA INDEX
    
    $index_to_remove = (int)$_GET['index']; // Ép kiểu index thành số nguyên
    
    if (isset($_SESSION['cart']) && array_key_exists($index_to_remove, $_SESSION['cart'])) {
        
        $removed_item = $_SESSION['cart'][$index_to_remove];
        $removed_name = $removed_item['name'];
        
        // Dùng array_splice để xóa phần tử theo key
        array_splice($_SESSION['cart'], $index_to_remove, 1); 
        
        // Đặt thông báo
        $_SESSION['cart_message'] = "Đã xóa sản phẩm **" . htmlspecialchars($removed_name) . "** khỏi giỏ hàng.";

        // Sau khi xóa, bạn nên unset key thông báo lỗi nếu có
        unset($_SESSION['cart_error']); 
    } else {
        $_SESSION['cart_error'] = "Không tìm thấy sản phẩm cần xóa trong giỏ hàng.";
    }
}


// 3. CHUYỂN HƯỚNG BẮT BUỘC
// Chuyển hướng về Front Controller: index.php?act=viewcart (Đã đồng bộ)
header('Location: index.php?act=viewcart');
exit(); 
?>