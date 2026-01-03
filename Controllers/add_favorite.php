<?php
// Controllers/add_favorite.php
// Xử lý việc thêm sản phẩm vào danh sách yêu thích (lưu vào Session)

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. KIỂM TRA ĐĂNG NHẬP (Nên kiểm tra, nhưng có thể bỏ qua nếu bạn cho phép khách)
// if (!isset($_SESSION['user_id'])) {
//     $_SESSION['error'] = 'Vui lòng đăng nhập để thêm sản phẩm yêu thích.';
//     header('Location: index.php?act=login');
//     exit(); 
// }

// 2. LẤY DỮ LIỆU SẢN PHẨM TỪ URL (GET)
if (isset($_GET['idSanPham'])) {
    $id = $_GET['idSanPham'];
    $ten = $_GET['tenSanPham'] ?? 'Sản phẩm không rõ tên';
    $gia = $_GET['Gia'] ?? 0;
    $hinh = $_GET['Image'] ?? '';

    // 3. KHỞI TẠO HOẶC LẤY DANH SÁCH YÊU THÍCH TỪ SESSION
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }
    
    $favorites = $_SESSION['favorites'];
    $is_existing = false;
    
    // 4. KIỂM TRA SẢN PHẨM ĐÃ CÓ TRONG DANH SÁCH CHƯA
    foreach ($favorites as $item) {
        if ($item['idSanPham'] == $id) {
            $is_existing = true;
            break;
        }
    }

    // 5. THÊM SẢN PHẨM MỚI VÀO DANH SÁCH
    if (!$is_existing) {
        $product_to_add = [
            'idSanPham' => $id,
            'TenSanPham' => $ten,
            'Gia' => $gia,
            'Image' => $hinh,
        ];
        $_SESSION['favorites'][] = $product_to_add;
        
        // Thông báo thành công (sẽ hiển thị ở Views/favorites.php)
        $_SESSION['message'] = "Đã thêm sản phẩm **" . htmlspecialchars($ten) . "** vào danh sách yêu thích!";
    } else {
         $_SESSION['message'] = "Sản phẩm **" . htmlspecialchars($ten) . "** đã có trong danh sách yêu thích.";
    }
} else {
    $_SESSION['error'] = "Lỗi: Không tìm thấy ID sản phẩm cần thêm.";
}

// 6. CHUYỂN HƯỚNG VỀ TRANG HIỂN THỊ DANH SÁCH YÊU THÍCH
header('Location: index.php?act=favorites');
exit();
?>