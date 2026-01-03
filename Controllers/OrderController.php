<?php
// File: Admin/OrderController.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../Models/order_model.php'; 

// Kiểm tra quyền Admin (Ví dụ đơn giản, bạn nên thay thế bằng logic Auth thực tế)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    // header('Location: ../Views/login.php'); 
    // exit();
}

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        // Lọc trạng thái từ URL
        $statusFilter = $_GET['status'] ?? 'All';
        $orders = getAllOrders($statusFilter);
        
        // Load View danh sách
        include '../Views/order_list.php'; 
        break;

    case 'details':
    $idDonHang = $_GET['id'] ?? 0;
    
    if ($idDonHang > 0) {
        $order = getOrderDetails($idDonHang); // <--- Gọi Model

        // --- CODE DEBUG QUAN TRỌNG: KIỂM TRA $order ---
        if (empty($order)) {
            echo "LỖI TRUY VẤN: Không tìm thấy đơn hàng #$idDonHang hoặc lỗi SQL trong Model.";
            // Sau khi sửa lỗi, xóa exit()
            exit(); 
        } else {
            echo "<h2>DEBUG: Đã tìm thấy dữ liệu đơn hàng #$idDonHang</h2>";
            echo "<pre>";
            print_r($order); // <--- Hiển thị toàn bộ cấu trúc dữ liệu
            echo "</pre>";
            // Sau khi sửa lỗi, xóa exit()
            exit(); // Dừng Controller tại đây, KHÔNG LOAD VIEW
        }
        // ---------------------------------------------
        
        /* // Sau khi debug thành công, bạn sẽ sử dụng lại code này:
        if ($order) {
            include '../Views/order_details.php';
        } else {
            echo "Không tìm thấy đơn hàng.";
        }
        */
    } else {
        header('Location: ?action=list');
    }
    break;

    case 'update_status':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idDonHang = $_POST['idDonHang'] ?? 0;
            $newStatus = $_POST['newStatus'] ?? '';

            if ($idDonHang > 0 && !empty($newStatus)) {
                if (updateOrderStatus($idDonHang, $newStatus)) {
                    $_SESSION['message'] = "Cập nhật trạng thái đơn hàng #$idDonHang thành công.";
                } else {
                    $_SESSION['error'] = "Lỗi khi cập nhật trạng thái đơn hàng.";
                }
            }
        }
        header('Location: ?action=details&id=' . $idDonHang);
        exit();
}