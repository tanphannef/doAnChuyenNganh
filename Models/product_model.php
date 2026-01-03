<?php
// Models/product_model.php
if (file_exists('./Models/db.php')) include_once './Models/db.php';
elseif (file_exists('../Models/db.php')) include_once '../Models/db.php';

class ProductModel { 

    public function getProductDetailById($idSanPham) {
    try {
        $pdo = get_pdo_connection();
        
        // Cần đảm bảo tên bảng và tên cột khớp với CSDL của bạn
        $sql = "
            SELECT 
                sp.idSanPham, sp.TenSanPham, sp.Gia, sp.MoTa, sp.SoLuong, sp.Image, 
                dm.TenDanhMuc, -- Tên Danh Mục
                ncc.TenNhaCungCap -- Tên Nhà Cung Cấp
            FROM 
                sanpham sp
            LEFT JOIN 
                danhmuc dm ON sp.idDanhMuc = dm.idDanhMuc
            LEFT JOIN 
                nhacungcap ncc ON sp.idNhaCungCap = ncc.idNhaCungCap
            WHERE 
                sp.idSanPham = ?
            LIMIT 1
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idSanPham]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Ghi log lỗi và trả về false
        error_log("Lỗi SQL khi lấy chi tiết sản phẩm: " . $e->getMessage());
        return false;
    }
}
    
    // Hàm lấy danh sách tất cả Danh Mục (để hiển thị vào ô chọn)
    public function getAllCategories() {
        try {
            $pdo = get_pdo_connection();
            $stmt = $pdo->prepare("SELECT * FROM danhmuc ORDER BY idDanhMuc DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return []; }
    }


        public function insertProduct($ten, $gia, $soluong, $mota, $hinh, $iddm) {
            try {
                $pdo = get_pdo_connection();
                $sql = "INSERT INTO sanpham (TenSanPham, Gia, SoLuong, MoTa, image, idDanhMuc) 
                VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$ten, $gia, $soluong, $mota, $hinh, $iddm]);
                return true;
            } catch (PDOException $e) {
                echo "Lỗi SQL: " . $e->getMessage();
                return false;
            }
        }
    }
?>