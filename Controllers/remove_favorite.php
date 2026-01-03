<?php
// Controllers/remove_favorite.php
// Xử lý việc xóa sản phẩm khỏi danh sách yêu thích (xóa khỏi Session)

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Kiểm tra ID sản phẩm cần xóa và danh sách yêu thích
if (isset($_GET['idSanPham']) && isset($_SESSION['favorites'])) {
    $id_to_remove = $_GET['idSanPham'];
    $favorite_list = $_SESSION['favorites'];
    $new_favorites = [];
    $removed_name = '';

    // 2. Lặp qua danh sách favorites để loại bỏ sản phẩm
    foreach ($favorite_list as $item) {
        if ($item['idSanPham'] != $id_to_remove) {
            // Giữ lại các sản phẩm KHÔNG khớp ID
            $new_favorites[] = $item;
        } else {
            // Lấy tên sản phẩm đã xóa
            $removed_name = $item['TenSanPham'];
        }
    }

    // 3. Cập nhật lại Session
    $_SESSION['favorites'] = $new_favorites;
    
    // 4. Đặt thông báo
    if (!empty($removed_name)) {
        $_SESSION['message'] = "Đã xóa sản phẩm **" . htmlspecialchars($removed_name) . "** khỏi danh sách yêu thích.";
    } else {
        $_SESSION['error'] = "Không tìm thấy sản phẩm cần xóa trong danh sách yêu thích.";
    }
} else {
    $_SESSION['error'] = "Yêu cầu xóa không hợp lệ.";
}

// 5. Chuyển hướng về trang danh sách yêu thích
header('Location: index.php?act=favorites');
exit();
?>