<?php
// Bắt đầu Session để lưu trữ giỏ hàng
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra xem dữ liệu POST từ form có được gửi lên hay không
if (isset($_POST['addtocart'])) {
    
    // Lấy thông tin sản phẩm từ form (đảm bảo tên trùng với input hidden trong form)
    $id = $_POST['idSanPham'];
    $ten = $_POST['tenSanPham'];
    $gia = $_POST['Gia'];
    $hinh = $_POST['img']; // Tên file ảnh (ví dụ: hoodie.jpg)
    
    // Số lượng luôn là 1 khi bấm nút Thêm Giỏ lần đầu
    $soluong = 1; 

    // Kiểm tra xem giỏ hàng đã tồn tại trong session chưa
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Biến cờ kiểm tra xem sản phẩm đã có trong giỏ chưa
    $product_exists = false;
    
    // Duyệt qua giỏ hàng hiện tại
    foreach ($_SESSION['cart'] as $key => $item) {
        // Nếu tìm thấy ID sản phẩm đã có
        if ($item['id'] == $id) {
            // Tăng số lượng lên 1
            $_SESSION['cart'][$key]['soluong'] += 1;
            $product_exists = true;
            break;
        }
    }

    // Nếu sản phẩm chưa có trong giỏ thì thêm mới
    if (!$product_exists) {
        // Tạo một mảng mới cho sản phẩm này
        $new_product = [
            'id' => $id,
            'name' => $ten,
            'price' => $gia,
            'image' => $hinh,
            'soluong' => $soluong
        ];
        // Thêm vào giỏ hàng (mảng session)
        $_SESSION['cart'][] = $new_product;
    }
}

// Chuyển hướng người dùng về trang trước hoặc trang Giỏ hàng
header('Location: ../index.php?act=viewcart');
exit();
?>