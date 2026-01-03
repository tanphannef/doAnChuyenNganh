<?php
// =================================================================
// 1. KHỞI TẠO HỆ THỐNG & LOGIC PHP
// =================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

require_once 'Models/db.php'; // Nếu db.php nằm trong Models
require_once 'Models/khachhang_model.php'; 
require_once 'Models/product_model.php';
require_once 'Models/order_model.php'; // Các model khác...

// Hàm kết nối Database (Đã chỉnh chuẩn cho qlpetshop)
if (!function_exists('get_db_conn')) {
    function get_db_conn() {
        try {
            // Ưu tiên kết nối qlpetshop
            return new PDO("mysql:host=localhost;dbname=qlpetshop;charset=utf8", "root", "");
        } catch (Exception $e) {
            try {
                // Dự phòng
                return new PDO("mysql:host=localhost;dbname=shop_thu_cung;charset=utf8", "root", "");
            } catch (Exception $ex) { return null; }
        }
    }
}
$conn = get_db_conn();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NONM Pet Shop - Thế giới thú cưng</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <style>
        /* ================= GIAO DIỆN TÙY CHỈNH (BEAUTIFUL UI) ================= */
        :root {
            --primary-grad: linear-gradient(135deg, #36D1DC 0%, #5B86E5 100%);
            --secondary-grad: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%);
            --bg-light: #f8f9fa;
            --text-dark: #333;
        }

        body { font-family: 'Quicksand', sans-serif; background-color: #fff; color: var(--text-dark); }
        a { text-decoration: none; color: inherit; transition: 0.3s; }
        
        /* HEADER */
        .top-header { background: var(--primary-grad); color: white; font-size: 13px; padding: 8px 0; font-weight: 600; }
        .main-header { background: #fff; padding-top: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 1000; }
        
        .logo-text { font-size: 28px; font-weight: 800; background: var(--primary-grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-transform: uppercase; letter-spacing: 1px; }
        
        .search-input { border: 2px solid #eee; border-radius: 50px 0 0 50px; padding: 12px 20px; height: 48px; font-size: 14px; }
        .search-input:focus { border-color: #5B86E5; box-shadow: none; }
        .search-btn { background: var(--primary-grad); color: white; border: none; padding: 0 30px; height: 48px; border-radius: 0 50px 50px 0; font-weight: 600; }
        
        .header-actions a { color: #555; margin-left: 20px; text-align: center; font-size: 13px; display: flex; flex-direction: column; align-items: center; }
        .header-actions a:hover { color: #5B86E5; transform: translateY(-3px); }
        .header-actions i { font-size: 22px; margin-bottom: 3px; }
        .cart-badge { background: var(--secondary-grad); color: white; font-size: 10px; padding: 2px 6px; border-radius: 50%; position: absolute; top: -5px; right: -5px; }

        .nav-link { color: #333 !important; font-weight: 700; text-transform: uppercase; padding: 15px 20px !important; font-size: 14px; position: relative; }
        .nav-link:hover { color: #5B86E5 !important; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 3px; bottom: 0; left: 50%; background: var(--primary-grad); transition: all 0.3s; transform: translateX(-50%); }
        .nav-link:hover::after { width: 80%; }

        /* BANNER */
        .banner-section { background-color: #F9F3EC; padding: 60px 0; }
        .banner-title { font-size: 3.5rem; font-weight: 700; line-height: 1.2; margin-bottom: 20px; }
        .btn-shop { background: var(--text-dark); color: white; padding: 12px 30px; border-radius: 50px; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; }
        .btn-shop:hover { background: var(--primary-grad); color: white; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(54, 209, 220, 0.4); }

        /* CATEGORIES */
        .cat-item { background: white; border-radius: 20px; padding: 25px; text-align: center; border: 1px solid #eee; transition: 0.3s; display: block; height: 100%; }
        .cat-item:hover { border-color: #5B86E5; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transform: translateY(-5px); }
        .cat-icon { font-size: 40px; color: #5B86E5; margin-bottom: 15px; }
        .cat-title { font-weight: 700; color: #333; margin: 0; }

        /* PRODUCT CARDS */
        .product-card { border: none; border-radius: 20px; overflow: hidden; background: white; transition: 0.3s; height: 100%; position: relative; border: 1px solid #f0f0f0; }
        .product-card:hover { box-shadow: 0 15px 30px rgba(0,0,0,0.1); transform: translateY(-5px); }
        .product-img-wrap { position: relative; overflow: hidden; height: 280px; }
        .product-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .product-card:hover .product-img { transform: scale(1.05); }
        .badge-new { position: absolute; top: 15px; left: 15px; background: var(--secondary-grad); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; z-index: 2; }
        
        .card-body { padding: 20px; }
        .card-title { font-weight: 700; font-size: 16px; margin-bottom: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .price { font-size: 18px; font-weight: 700; color: #5B86E5; }
        .rating { color: #FFC107; font-size: 12px; margin-bottom: 10px; display: block; }
        
        .action-buttons { display: flex; gap: 10px; margin-top: 15px; }
        .btn-add-cart { flex: 1; background: #f1f3f5; border: none; padding: 10px; border-radius: 10px; font-weight: 600; color: #333; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 5px;}
        .btn-add-cart:hover { background: var(--primary-grad); color: white; }
        .btn-love { width: 40px; height: 40px; border-radius: 10px; background: #fff0f3; color: #ff4757; border: none; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
        .btn-love:hover { background: #ff4757; color: white; }

        /* FOOTER */
        footer { background-color: #f8f9fa; padding-top: 60px; margin-top: 60px; border-top: 1px solid #eee; }
        .footer-logo { font-size: 24px; font-weight: 800; color: #333; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .footer-title { font-weight: 700; margin-bottom: 20px; font-size: 18px; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: #666; font-size: 14px; }
        .footer-links a:hover { color: #5B86E5; padding-left: 5px; }
        .social-icons a { width: 35px; height: 35px; background: white; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 10px; color: #5B86E5; box-shadow: 0 3px 10px rgba(0,0,0,0.05); transition: 0.3s; }
        .social-icons a:hover { background: var(--primary-grad); color: white; transform: translateY(-3px); }
    </style>
</head>
<body>

<div class="top-header">
    <div class="container d-flex justify-content-between align-items-center">
        <span><i class="fas fa-envelope me-2"></i> hotro@nonmpetshop.com</span>
        <span class="d-none d-md-block">Chào mừng đến với NONM Pet Shop - Thế giới thú cưng! 🐾</span>
        <span><i class="fas fa-phone-alt me-2"></i> 1900 6789</span>
    </div>
</div>

<header class="main-header">
    <div class="container">
        <div class="row align-items-center pb-3">
            <div class="col-md-3">
                <a href="index.php" class="text-decoration-none d-flex align-items-center gap-2">
                    <img src="./Public/images/cloud1.png" width="60" alt="Logo">
                    <span class="logo-text">NONM PET</span>
                </a>
            </div>
            
            <div class="col-md-5">
                <form action="index.php" method="GET" class="d-flex position-relative">
                    <input type="hidden" name="act" value="timkiem">
                    <input type="text" class="form-control search-input" name="keyword" placeholder="Bạn tìm gì cho Boss hôm nay?..." required>
                    <button class="search-btn" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="col-md-4 d-flex justify-content-end header-actions align-items-center">
                <div class="col-md-4 d-flex justify-content-end header-actions align-items-center">
                <?php
                // BẮT BUỘC: Khởi tạo Session ở đầu file (hoặc file index.php)
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // 1. KIỂM TRA ĐĂNG NHẬP: Dựa trên $_SESSION['user_id']
                $isLoggedIn = isset($_SESSION['user_id']); 

                // 2. KIỂM TRA ADMIN: Dựa trên $_SESSION['user_role']
                // Giả định: 1 là Admin
                $isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1); 

                // 3. TÊN HIỂN THỊ: Dựa trên $_SESSION['user_name']
                $displayName = $_SESSION['user_name'] ?? 'Tài khoản'; 

                // Lấy số lượng giỏ hàng (nếu cần)
                $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                ?>

                <div class="col-md-4 d-flex justify-content-end header-actions align-items-center">
                    
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="far fa-user"></i>
                            <span><?= htmlspecialchars($displayName) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-2 rounded-3">
                            
                            <?php if ($isLoggedIn): ?>
                                <li><a class="dropdown-item rounded-2" href="index.php?act=profile">Hồ sơ cá nhân</a></li>
                                
                                <?php if ($isAdmin): ?>
                                    <li><a class="dropdown-item rounded-2 text-primary" href="Views/dashboard.php">Trang quản trị</a></li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-2 text-danger" href="Process/logout_process.php">Đăng xuất</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item rounded-2" href="Views/login.php">Đăng nhập</a></li>
                                <li><a class="dropdown-item rounded-2" href="Views/register.php">Đăng ký</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                </div>

                <a href="index.php?act=favorites"><i class="far fa-heart"></i><span>Yêu thích</span></a>
                
                <a href="index.php?act=viewcart" style="position: relative;">
                    <i class="fas fa-shopping-bag"></i><span>Giỏ hàng</span>
                    <span class="cart-badge"><?= $cartCount ?></span>
                </a>
            </div>
        </div>

        <div class="border-top pt-2">
            <ul class="nav justify-content-center">
                <li class="nav-item"><a class="nav-link" href="index.php">TRANG CHỦ</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?act=shop">CỬA HÀNG</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?act=dichvuspa">DỊCH VỤ SPA</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?act=blog">BLOG THÚ CƯNG</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?act=lienhe">LIÊN HỆ</a></li>
            </ul>
        </div>
    </div>
</header>

<main>
<?php
// ================= PHẦN ĐIỀU HƯỚNG =================
if (isset($_GET['act'])) {
    $act = $_GET['act'];
    switch ($act) {
        
        // --- TRANG CỬA HÀNG (SHOP) ---
        case 'shop':
            $all_products = [];
            if ($conn) {
                $sql = "SELECT * FROM sanpham ORDER BY idSanPham DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            if (file_exists('Views/shop.php')) {
                include "Views/shop.php";
            } else {
                echo "<div class='container text-center py-5'>Chưa tạo file Views/shop.php</div>";
            }
            break;

        // --- TRANG DANH SÁCH YÊU THÍCH (VIEW) ---
        case 'add_favorite':
            // Controller xử lý thêm yêu thích
            // Giống như 'addtocart', Controller này phải xử lý logic, header() và exit()
            if (file_exists('Controllers/add_favorite.php')) {
                include "Controllers/add_favorite.php";
            } else {
                header('Location: index.php?act=shop&error=Controller+add_favorite+chua+tao');
                exit();
            }
            break;
            
        case 'remove_favorite':
            // Controller xử lý xóa yêu thích
            // Giống như 'delete_cart', Controller này phải xử lý logic, header() và exit()
            if (file_exists('Controllers/remove_favorite.php')) {
                include "Controllers/remove_favorite.php";
            } else {
                header('Location: index.php?act=favorites&error=Controller+remove_favorite+chua+tao');
                exit();
            }
            break;

        case 'favorites':
            // HIỂN THỊ DANH SÁCH YÊU THÍCH
            // Giống như 'viewcart', chỉ include View sau khi Controller đã xử lý xong (ở các case trên)
            $page_title = "Danh sách Yêu Thích";
            include "Views/favorites.php";
            break;
        
        // --- [MỚI] TRANG GIỎ HÀNG (VIEWCART) ---
        case 'viewcart':
            if (file_exists('Views/viewcart.php')) {
                include "Views/viewcart.php";
            } else {
                // Tùy chọn: Thay thế bằng code Giỏ hàng nếu bạn không muốn tạo file viewcart.php
                echo "<div class='container py-5 text-center'>
                        <h2 class='text-danger'>⚠️ Lỗi</h2>
                        <p>Vui lòng tạo file <strong>Views/viewcart.php</strong> để hiển thị giỏ hàng.</p>
                      </div>";
            }
            break;

        case 'delcart':
            // Gọi Controller xử lý xóa
            if (file_exists('Controllers/delcart.php')) {
                include "Controllers/delcart.php";
                // Controller này phải tự gọi header() và exit()
            } else {
                header('Location: index.php?act=viewcart&error=Controller+delcart+chua+tao');
                exit();
            }
            break;

        case 'order_detail':
            // Kiểm tra ID đơn hàng được truyền
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $idDonHang = (int)$_GET['id'];
                
                // Gọi View chi tiết
                $page_title = "Chi tiết Đơn hàng #".$idDonHang;
                include "Views/order_details.php";
            } else {
                // Xử lý nếu ID không hợp lệ hoặc không có
                header('Location: index.php?act=profile&error=Ma+don+hang+khong+hop+le');
                exit();
            }
            break;

        case 'single_product':
            // 1. Kiểm tra ID sản phẩm
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $idSanPham = (int)$_GET['id'];
                
                // 2. Khởi tạo Model và lấy dữ liệu
                // Giả sử tên Class là ProductModel
                $sanPhamModel = new ProductModel(); 
                $productDetail = $sanPhamModel->getProductDetailById($idSanPham);

                if ($productDetail) {
                    // 3. Nạp View nếu có dữ liệu
                    $page_title = "Chi tiết Sản phẩm";
                    include "Views/single_product.php";
                } else {
                    // 4. Xử lý không tìm thấy (Chuyển hướng về trang chủ)
                    $_SESSION['error'] = "Không tìm thấy sản phẩm này.";
                    header('Location: index.php'); 
                    exit();
                }
            } else {
                // Xử lý nếu thiếu ID
                header('Location: index.php');
                exit();
            }
            break;
        // ------------------------------------------

        case 'timkiem':
            echo "<div class='container py-5 text-center'><h2>🔍 Kết quả tìm kiếm: " . htmlspecialchars($_GET['keyword']) . "</h2></div>";
            break;

        case 'profile':
            if (file_exists('Views/profile.php')) include "Views/profile.php";
            break;

        // --- DỊCH VỤ SPA ---
        case 'dichvuspa':
            ?>
            <style>
                .spa-header { background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; height: 300px; display: flex; align-items: center; justify-content: center; color: white; text-align: center; }
                .spa-title { font-size: 3rem; font-weight: 800; text-shadow: 2px 2px 10px rgba(0,0,0,0.5); }
                .service-card { border: 2px solid #f0f0f0; border-radius: 20px; padding: 30px; text-align: center; transition: 0.3s; height: 100%; background: #fff; }
                .service-card:hover { border-color: #FF416C; transform: translateY(-10px); box-shadow: 0 15px 40px rgba(255, 65, 108, 0.15); }
                .service-icon { font-size: 50px; color: #5B86E5; margin-bottom: 20px; }
                .service-name { font-weight: 700; font-size: 1.5rem; margin-bottom: 15px; color: #333; }
                .service-price { color: #FF416C; font-size: 1.25rem; font-weight: 700; margin-bottom: 20px; }
                .service-list { list-style: none; padding: 0; margin-bottom: 25px; text-align: left; }
                .service-list li { padding: 8px 0; border-bottom: 1px dashed #eee; font-size: 0.95rem; color: #666; }
                .service-list li i { color: #28a745; margin-right: 10px; }
                .btn-book { background: linear-gradient(135deg, #36D1DC 0%, #5B86E5 100%); color: white; padding: 10px 30px; border-radius: 50px; font-weight: 600; border: none; width: 100%; transition: 0.3s; }
            </style>
            <div class="spa-header"><div><h1 class="spa-title">NONM PET SPA</h1><p class="fs-5">Dịch vụ chăm sóc & làm đẹp chuẩn 5 sao</p></div></div>
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-md-4"><div class="service-card"><div class="service-icon"><i class="fas fa-bath"></i></div><h3 class="service-name">Tắm Gội & Vệ Sinh</h3><div class="service-price">Từ 150.000đ</div><ul class="service-list"><li><i class="fas fa-check"></i> Tắm massage</li><li><i class="fas fa-check"></i> Vệ sinh tai, mắt</li></ul><a href="index.php?act=lienhe" class="btn btn-book">Đặt Lịch Ngay</a></div></div>
                    <div class="col-md-4"><div class="service-card"><div class="service-icon"><i class="fas fa-cut"></i></div><h3 class="service-name">Cắt Tỉa Tạo Kiểu</h3><div class="service-price">Từ 350.000đ</div><ul class="service-list"><li><i class="fas fa-check"></i> Gồm gói Tắm</li><li><i class="fas fa-check"></i> Tạo kiểu thời trang</li></ul><a href="index.php?act=lienhe" class="btn btn-book">Đặt Lịch Ngay</a></div></div>
                    <div class="col-md-4"><div class="service-card"><div class="service-icon"><i class="fas fa-hotel"></i></div><h3 class="service-name">Khách Sạn Thú Cưng</h3><div class="service-price">200.000đ / ngày</div><ul class="service-list"><li><i class="fas fa-check"></i> Phòng điều hòa 24/7</li><li><i class="fas fa-check"></i> Camera quan sát</li></ul><a href="index.php?act=lienhe" class="btn btn-book">Đặt Phòng Ngay</a></div></div>
                </div>
            </div>
            <?php
            break;

        // --- BLOG SECTION ---
        case 'blog':
            $posts = [
                ['id'=>1, 'title'=>'Top 5 loại thức ăn dinh dưỡng tốt nhất cho Cún con 2025', 'desc'=>'Dinh dưỡng trong những năm tháng đầu đời quyết định sự phát triển của cún...', 'cat'=>'Dinh Dưỡng', 'date'=>'12/12/2025', 'icon'=>'fa-bone'],
                ['id'=>2, 'title'=>'Cách giữ ấm cho Mèo cưng trong mùa đông lạnh giá', 'desc'=>'Mùa đông đang đến, làm sao để Boss không bị ốm? Những bí kíp giữ ấm...', 'cat'=>'Chăm Sóc', 'date'=>'10/12/2025', 'icon'=>'fa-snowflake'],
                ['id'=>3, 'title'=>'Tại sao thú cưng biếng ăn? Nguyên nhân và cách khắc phục', 'desc'=>'Biếng ăn là dấu hiệu của nhiều vấn đề sức khỏe. Đừng chủ quan...', 'cat'=>'Sức Khỏe', 'date'=>'08/12/2025', 'icon'=>'fa-heartbeat'],
                ['id'=>4, 'title'=>'Hướng dẫn huấn luyện Cún đi vệ sinh đúng chỗ', 'desc'=>'Nỗi ám ảnh mang tên vệ sinh bừa bãi sẽ chấm dứt nếu bạn áp dụng phương pháp này...', 'cat'=>'Huấn Luyện', 'date'=>'05/12/2025', 'icon'=>'fa-dog'],
            ];
            ?>
            <style>
                .blog-header { text-align: center; padding: 60px 0 40px; background: #fdfbf7; }
                .blog-card { border: none; border-radius: 15px; overflow: hidden; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; height: 100%; border: 1px solid #f0f0f0; }
                .blog-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); border-color: #5B86E5; }
                .blog-thumb { height: 200px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 3rem; }
                .blog-body { padding: 25px; }
                .blog-meta { font-size: 0.8rem; color: #888; margin-bottom: 10px; display: flex; gap: 15px; font-weight: 600; text-transform: uppercase; }
                .blog-cat { color: #5B86E5; }
                .blog-title { font-weight: 700; margin-bottom: 12px; font-size: 1.2rem; line-height: 1.4; color: #333; }
                .blog-desc { color: #666; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; }
                .btn-read-more { color: #FF416C; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; }
            </style>
            <div class="blog-header">
                <div class="container">
                    <h2 class="fw-bold mb-2">Blog Thú Cưng 🐾</h2>
                    <p class="text-muted">Kiến thức chăm sóc, nuôi dạy và yêu thương thú cưng</p>
                </div>
            </div>
            <div class="container py-5">
                <div class="row g-4">
                    <?php foreach($posts as $post): ?>
                    <div class="col-md-6 col-lg-3">
                        <article class="blog-card">
                            <div class="blog-thumb"><i class="fas <?= $post['icon'] ?>"></i></div>
                            <div class="blog-body">
                                <div class="blog-meta"><span class="blog-cat"><?= $post['cat'] ?></span><span>•</span><span><?= $post['date'] ?></span></div>
                                <h3 class="blog-title"><a href="#" class="text-decoration-none text-dark"><?= $post['title'] ?></a></h3>
                                <p class="blog-desc"><?= $post['desc'] ?></p>
                                <a href="#" class="btn-read-more">Đọc tiếp <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            break;

        case 'lienhe':
            // --- CONTACT SECTION ---
            ?>
            <style>
                .contact-wrapper { background-color: #fcf9f5; padding: 50px 0; }
                .contact-section { max-width: 1000px; margin: 0 auto; padding: 0 15px; }
                .section-header { text-align: center; margin-bottom: 40px; }
                .section-header h2 { color: #333; font-size: 2.2rem; font-weight: bold; margin-bottom: 10px; }
                .contact-container { display: flex; flex-wrap: wrap; background: #ffffff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
                .contact-info { flex: 1; background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%); color: #fff; padding: 50px; min-width: 300px; }
                .contact-info h3 { margin-top: 0; font-size: 1.5rem; margin-bottom: 30px; }
                .info-item { margin-bottom: 25px; display: flex; align-items: flex-start; gap: 15px; }
                .info-item i { font-size: 1.2rem; margin-top: 3px; }
                .contact-form { flex: 1.5; padding: 50px; min-width: 300px; }
                .form-group { margin-bottom: 20px; }
                .form-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 600; }
                .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; font-size: 1rem; background: #fafafa; }
                .form-group input:focus, .form-group textarea:focus { border-color: #FF416C; outline: none; background: #fff; }
                .btn-send { background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%); color: white; border: none; padding: 12px 30px; font-size: 1rem; border-radius: 50px; cursor: pointer; transition: 0.3s; width: 100%; font-weight: bold; }
            </style>
            <div class="contact-wrapper">
                <section class="contact-section">
                    <div class="section-header">
                        <h2>Liên Hệ Với Chúng Tôi</h2>
                        <p class="text-muted">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn chăm sóc thú cưng</p>
                    </div>
                    <div class="contact-container">
                        <div class="contact-info">
                            <h3>Thông Tin Liên Lạc</h3>
                            <div class="info-item"><i class="fas fa-map-marker-alt"></i><div><strong>Địa chỉ:</strong><br>123 Đường Thú Cưng, Q.1, TP.HCM</div></div>
                            <div class="info-item"><i class="fas fa-phone-alt"></i><div><strong>Hotline:</strong><br>090 123 4567</div></div>
                            <div class="info-item"><i class="fas fa-envelope"></i><div><strong>Email:</strong><br>hotro@nonmpetshop.com</div></div>
                        </div>
                        <div class="contact-form">
                            <form action="" method="post">
                                <div class="form-group"><label>Họ và tên</label><input type="text" name="hoten" placeholder="Nhập tên..."></div>
                                <div class="form-group"><label>Email liên hệ</label><input type="email" name="email" placeholder="Nhập email..."></div>
                                <div class="form-group"><label>Lời nhắn</label><textarea name="noidung" rows="4" placeholder="Bạn cần hỗ trợ gì?"></textarea></div>
                                <button type="submit" class="btn-send">Gửi Tin Nhắn <i class="fas fa-paper-plane ms-2"></i></button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
            <?php
            break;

        case 'thanhtoan':
            if (isset($_SESSION['user'])) {
                if (file_exists('Views/thanhtoan.php')) include "Views/thanhtoan.php";
                else echo "<div class='container py-5 text-center'>Chưa có trang thanh toán</div>";
            } else {
                echo "<script>alert('Bạn cần đăng nhập để thanh toán!'); window.location.href='Views/login.php';</script>";
            }
            break;

        default:
            echo "<script>window.location.href='index.php';</script>";
            break;
    }
} 
else {
    // ================= 4. TRANG CHỦ (HIỂN THỊ MẶC ĐỊNH) =================
    // Lấy sản phẩm từ DB
    $products = [];
    if($conn) {
        $stmt = $conn->prepare("SELECT * FROM sanpham ORDER BY idSanPham DESC LIMIT 10");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>

    <section class="banner-section">
        <div class="container">
            <div class="swiper main-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="row align-items-center">
                            <div class="col-md-5 order-md-2 text-center">
                                <img src="./Public/images/banner-img.png" class="img-fluid" style="max-height: 400px;">
                            </div>
                            <div class="col-md-7 order-md-1">
                                <span class="text-primary fw-bold text-uppercase ls-2">Giảm giá 20% hôm nay</span>
                                <h1 class="banner-title mt-3">Thức ăn tốt nhất cho <span style="color: #5B86E5;">Boss Cưng</span></h1>
                                <p class="mb-4 text-muted fs-5">Cung cấp dinh dưỡng trọn vẹn, giúp thú cưng khỏe mạnh và vui vẻ mỗi ngày.</p>
                                <a href="index.php?act=shop" class="btn-shop">MUA NGAY <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="row align-items-center">
                            <div class="col-md-5 order-md-2 text-center">
                                <img src="./Public/images/banner-img3.png" class="img-fluid" style="max-height: 400px;">
                            </div>
                            <div class="col-md-7 order-md-1">
                                <span class="text-primary fw-bold text-uppercase ls-2">Bộ sưu tập mới</span>
                                <h1 class="banner-title mt-3">Thời trang <span style="color: #FF416C;">Mùa Đông</span></h1>
                                <p class="mb-4 text-muted fs-5">Ấm áp, sành điệu và vô cùng đáng yêu.</p>
                                <a href="index.php?act=shop" class="btn-shop">KHÁM PHÁ NGAY <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Danh Mục Phổ Biến</h2>
                <p class="text-muted">Chúng tôi có mọi thứ bạn cần</p>
            </div>
            <div class="row g-4">
                <div class="col-6 col-md-3"><a href="#" class="cat-item"><iconify-icon class="cat-icon" icon="ph:bowl-food-duotone"></iconify-icon><h5 class="cat-title">Thức Ăn</h5></a></div>
                <div class="col-6 col-md-3"><a href="#" class="cat-item"><iconify-icon class="cat-icon" icon="ph:dog-duotone"></iconify-icon><h5 class="cat-title">Chó Cưng</h5></a></div>
                <div class="col-6 col-md-3"><a href="#" class="cat-item"><iconify-icon class="cat-icon" icon="ph:cat-duotone"></iconify-icon><h5 class="cat-title">Mèo Cưng</h5></a></div>
                <div class="col-6 col-md-3"><a href="#" class="cat-item"><iconify-icon class="cat-icon" icon="ph:first-aid-kit-duotone"></iconify-icon><h5 class="cat-title">Chăm Sóc</h5></a></div>
            </div>
        </div>
    </section>

    <section class="my-5 bg-light py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div><h2 class="fw-bold m-0">Sản Phẩm Mới</h2><p class="text-muted m-0">Cập nhật xu hướng mới nhất</p></div>
                <a href="index.php?act=shop" class="btn btn-outline-primary rounded-pill px-4">Xem tất cả</a>
            </div>

            <div class="swiper products-carousel">
                <div class="swiper-wrapper py-3"> 
                <?php if(!empty($products)): ?>
                    <?php foreach($products as $sp): ?>
                        <?php 
                            // XỬ LÝ ẢNH
                            $tenAnh = $sp['Image'] ?? ''; 
                            $img_src = "Public/images/default.png"; // Ảnh mặc định
                            
                            if (!empty($tenAnh) && $tenAnh != '1') {
                                if (strpos($tenAnh, 'http') !== false) {
                                    $img_src = $tenAnh; // Giữ nguyên link http
                                } else {
                                    $img_src = "Public/images/" . $tenAnh; // Thêm thư mục
                                }
                            }
                        ?>
                        <div class="swiper-slide">
                            <div class="product-card h-100">
                                <span class="badge-new">New</span>
                                <div class="product-img-wrap">
                                    <a href="index.php?act=detail&id=<?= $sp['idSanPham'] ?>">
                                        <img src="<?= $img_src ?>" class="product-img" alt="<?= htmlspecialchars($sp['TenSanPham']) ?>" onerror="this.src='https://via.placeholder.com/300?text=No+Image'">
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                    <h5 class="card-title"><?= htmlspecialchars($sp['TenSanPham']) ?></h5>
                                    <div class="price"><?= number_format($sp['Gia'], 0, ',', '.') ?> VNĐ</div>
                                    
                                    <form action="./Controllers/addcart.php" method="post" class="action-buttons">
                                        <input type="hidden" name="idSanPham" value="<?= $sp['idSanPham'] ?>">
                                        <input type="hidden" name="img" value="<?= $sp['Image'] ?>">
                                        <input type="hidden" name="tenSanPham" value="<?= htmlspecialchars($sp['TenSanPham']) ?>">
                                        <input type="hidden" name="Gia" value="<?= $sp['Gia'] ?>">
                                        <button type="submit" name="addtocart" class="btn-add-cart"><i class="fas fa-cart-plus"></i> Thêm giỏ</button>
                                        <button type="button" class="btn-love"><i class="far fa-heart"></i></button>
                                    </form>
                                    </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5"><p class="text-muted">Chưa có sản phẩm nào trong Database!</p></div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background: #F9F3EC;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 order-md-2 text-center">
                    <img src="./Public/images/banner-img2.png" class="img-fluid" style="transform: scale(1.1);">
                </div>
                <div class="col-md-6 order-md-1 p-5">
                    <span class="badge bg-danger mb-3 px-3 py-2">HẾT HẠN SẮP TỚI</span>
                    <h2 class="display-4 fw-bold mb-3">Xả Kho Cuối Năm</h2>
                    <p class="fs-5 text-muted mb-4">Giảm giá lên đến 50% cho các sản phẩm thức ăn và phụ kiện. Đừng bỏ lỡ cơ hội này!</p>
                    <a href="index.php?act=shop" class="btn-shop" style="background: var(--secondary-grad);">SĂN SALE NGAY <i class="fas fa-fire"></i></a>
                </div>
            </div>
        </div>
    </section>

<?php 
    } // KẾT THÚC TRANG CHỦ (ELSE)
?> 
</main>

<footer>
    <div class="container pb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="footer-logo"><img src="./Public/images/cloud1.png" width="50" alt="Logo"><span>NONM PET</span></div>
                <p class="text-muted pe-4">Nơi cung cấp những sản phẩm tốt nhất cho thú cưng của bạn.</p>
                <div class="social-icons mt-3">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="col-md-2 col-6">
                <h5 class="footer-title">Liên kết</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="index.php?act=shop">Cửa hàng</a></li>
                    <li><a href="index.php?act=dichvuspa">Spa & Grooming</a></li>
                    <li><a href="index.php?act=blog">Blog</a></li>
                </ul>
            </div>

            <div class="col-md-2 col-6">
                <h5 class="footer-title">Hỗ trợ</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Vận chuyển</a></li>
                    <li><a href="#">Thanh toán</a></li>
                    <li><a href="index.php?act=lienhe">Liên hệ</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5 class="footer-title">Đăng ký nhận tin</h5>
                <div class="input-group mt-3">
                    <input type="text" class="form-control" placeholder="Email của bạn..." style="border-radius: 50px 0 0 50px; border: 1px solid #eee; padding-left: 20px;">
                    <button class="btn btn-primary" style="background: var(--secondary-grad); border: none; border-radius: 0 50px 50px 0; padding: 0 20px;"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center py-3 bg-light border-top">
        <p class="m-0 small text-muted">© 2025 NONM Pet Shop. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<script>
    new Swiper(".main-swiper", {
        loop: true, autoplay: { delay: 4000 }, pagination: { el: ".swiper-pagination", clickable: true },
    });

    new Swiper(".products-carousel", {
        slidesPerView: 1, spaceBetween: 20, loop: true,
        breakpoints: {
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1200: { slidesPerView: 4 },
        },
    });
</script>

</body>
</html>