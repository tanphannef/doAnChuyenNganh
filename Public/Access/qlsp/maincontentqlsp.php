
<!-- Nội dung chính -->
<div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="dashboard.php">Trang Chủ</a></li>
                <li><a href="quanlysanpham.php?act=danhmucsanpham">Danh mục sản phẩm</a></li>
                <li><a href="quanlysanpham.php?act=loaisanpham">Loại sản phẩm</a></li>
                <li><a href="quanlysanpham.php?act=sanpham">Sản phẩm</a></li>
            </ul>
        </aside>
    </div>

<?php

$sanPhamList = [];
$errorMessage = '';

try {
    $pdo = get_pdo_connection();
    
    $sql = "SELECT sp.*, dm.TenDanhMuc, lsp.TenLoai, ncc.TenNhaCungCap 
            FROM sanpham sp
            JOIN danhmuc dm ON sp.idDanhMuc = dm.idDanhMuc
            JOIN loaisanpham lsp ON sp.idLoaiSP = lsp.idLoaiSP
            JOIN nhacungcap ncc ON sp.idNhaCungCap = ncc.idNhaCungCap
            ORDER BY sp.idSanPham DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sanPhamList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errorMessage = "Lỗi khi tải danh sách sản phẩm: " . $e->getMessage();
}
?>

<style>
    .product-list-container {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        clear: both; 
    }
    .page-title {
        color: #DEAD6F;
        font-size: 24px;
        margin-bottom: 15px;
    }
    .product-table {
        width: 100%;
        border-collapse: collapse;
    }
    .product-table th {
        background-color: #f0f0f0;
        color: #333;
        font-weight: bold;
        text-transform: uppercase;
        padding: 12px;
        text-align: left;
    }
    .product-table td {
        border: 1px solid #ddd;
        padding: 10px;
        vertical-align: middle;
        font-size: 14px;
    }
    .product-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .product-table tr:hover {
        background-color: #f1f1f1;
    }
    .price-col {
        font-weight: bold;
        color: #E9573F;
        text-align: center;
    }
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>

<div class="product-list-container">
    <h1 class="page-title">Danh sách Sản phẩm</h1>
    
    <?php 
    if (!empty($errorMessage)) {
        echo '<p style="color: red;">' . $errorMessage . '</p>';
    }
    ?>
    
    <?php if (empty($sanPhamList)): ?>
        <p>Không có sản phẩm nào được tìm thấy.</p>
    <?php else: ?>
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Sản phẩm</th>
                    <th>DM</th>
                    <th>Loại</th>
                    <th>Giá</th>
                    <th>SL</th>
                    <th>NCC</th>
                    <th>Hình ảnh</th>
                    </tr>
            </thead>
            <tbody>
                <?php foreach ($sanPhamList as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['idSanPham']) ?></td>
                    <td><?= htmlspecialchars($row['TenSanPham']) ?></td>
                    <td><?= htmlspecialchars($row['TenDanhMuc']) ?></td>
                    <td><?= htmlspecialchars($row['TenLoai']) ?></td>
                    <td class="price-col"><?= number_format($row['Gia']) ?> VNĐ</td>
                    <td><?= htmlspecialchars($row['SoLuong']) ?></td>
                    <td><?= htmlspecialchars($row['TenNhaCungCap']) ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" class="product-image" alt="SP">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>