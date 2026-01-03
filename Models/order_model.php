<?php
require_once "db.php"; // Đảm bảo đường dẫn này đúng với cấu trúc thư mục của bạn

class DonHang {

    /**
     * Lấy tất cả đơn hàng (Hỗ trợ lọc trạng thái cho Admin)
     */
    public function getAllOrders($status = 'All') {
        try {
            $pdo = get_pdo_connection();
            
            $sql = "SELECT 
                        dh.idDonHang,
                        kh.TenKhachHang, 
                        dh.NgayTao,
                        dh.TongTien,
                        dh.TrangThai,
                        dh.idKhachHang
                    FROM 
                        donhang dh
                    JOIN 
                        khachhang kh ON dh.idKhachHang = kh.idKhachHang";

            $params = [];
            if ($status != 'All') {
                $sql .= " WHERE dh.TrangThai = ?";
                $params[] = $status;
            }

            $sql .= " ORDER BY dh.NgayTao DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Lấy chi tiết một đơn hàng
     */
    public function getOrderDetails($idDonHang) { 
        try {
            $pdo = get_pdo_connection();
            
            // 1. Lấy thông tin đơn hàng và khách hàng
            $sql_header = "SELECT 
                                dh.*,
                                kh.TenKhachHang, 
                                kh.DiaChi, 
                                kh.EmailKH, 
                                kh.Sdt
                            FROM 
                                donhang dh
                            JOIN 
                                khachhang kh ON dh.idKhachHang = kh.idKhachHang
                            WHERE 
                                dh.idDonHang = ?";
            
            $stmt_header = $pdo->prepare($sql_header);
            $stmt_header->execute([$idDonHang]);
            $order_info = $stmt_header->fetch(PDO::FETCH_ASSOC);

            if (!$order_info) {
                return false;
            }

            // 2. Lấy chi tiết sản phẩm trong đơn hàng
            $sql_items = "SELECT 
                                ct.idSanPham,
                                ct.SoLuong,
                                ct.Gia,
                                sp.TenSanPham,
                                sp.image as HinhAnh  -- Lưu ý: Kiểm tra tên cột ảnh trong DB là 'image' hay 'HinhAnh'
                            FROM 
                                chitietdonhang ct
                            JOIN 
                                sanpham sp ON ct.idSanPham = sp.idSanPham
                            WHERE 
                                ct.idDonHang = ?";
                            
            $stmt_items = $pdo->prepare($sql_items);
            $stmt_items->execute([$idDonHang]);
            $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
            
            // Gộp kết quả
            $order_info['items'] = $order_items;
            
            return $order_info;

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus($idDonHang, $newStatus) {
        try {
            $pdo = get_pdo_connection();
            $sql = "UPDATE donhang SET TrangThai = ? WHERE idDonHang = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$newStatus, $idDonHang]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Xử lý đặt hàng
     */
    public function placeOrder($idKhachHang, $TongTien, $DiaChiNhanHang, $items) {
        $pdo = get_pdo_connection();

        try {
            $pdo->beginTransaction();

            $NgayTao = date('Y-m-d H:i:s');
            $TrangThaiMacDinh = 'Chờ xác nhận';

            // 1. Insert đơn hàng
            $sql_dh = "INSERT INTO donhang (idKhachHang, NgayTao, TongTien, TrangThai, DiaChiNhanHang) 
                       VALUES (?, ?, ?, ?, ?)";
            
            $stmt_dh = $pdo->prepare($sql_dh);
            $stmt_dh->execute([$idKhachHang, $NgayTao, $TongTien, $TrangThaiMacDinh, $DiaChiNhanHang]);
            
            $idDonHang = $pdo->lastInsertId();

            // 2. Insert chi tiết
            $sql_ct = "INSERT INTO chitietdonhang (idDonHang, idSanPham, SoLuong, Gia) 
                       VALUES (?, ?, ?, ?)";
            $stmt_ct = $pdo->prepare($sql_ct);

            foreach ($items as $item) {
                $stmt_ct->execute([$idDonHang, $item['idSanPham'], $item['SoLuong'], $item['Gia']]);
            }
            
            $pdo->commit();
            return $idDonHang;

        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function getOrdersByUserId($idKhachHang) {
        try {
            $pdo = get_pdo_connection();
            
            $sql = "SELECT 
                        idDonHang,
                        NgayTao,
                        TongTien,
                        TrangThai
                    FROM 
                        donhang
                    WHERE 
                        idKhachHang = ?
                    ORDER BY 
                        NgayTao DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idKhachHang]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Log lỗi nếu cần
            return [];
        }
    }
}
?>