<?php
// File: Models/khachhang_model.php
require_once 'db.php'; 

function checkKhachHangExists($idKhachHang) {
    // Kiểm tra xem ID này đã có bản ghi trong bảng khachhang chưa
    $pdo = get_pdo_connection();
    $sql = "SELECT COUNT(*) FROM khachhang WHERE idKhachHang = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idKhachHang]);
    return $stmt->fetchColumn() > 0;
}

function insertKhachHangInfo($idKhachHang, $tenKH, $diaChi, $emailKH, $sdt, $idRole) {
    // Nếu chưa tồn tại, thêm thông tin mới vào bảng khachhang
    $pdo = get_pdo_connection();
    // Giả sử idRole được truyền vào (nên là 2 cho Khách hàng)
    $sql = "INSERT INTO khachhang (idKhachHang, TenKhachHang, DiaChi, EmailKH, Sdt, idRole) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$idKhachHang, $tenKH, $diaChi, $emailKH, $sdt, $idRole]);
}

// Hàm lấy thông tin (Nếu cần điền trước vào FORM)
function getKhachHangInfo($idKhachHang) {
    $pdo = get_pdo_connection();
    $sql = "SELECT * FROM khachhang WHERE idKhachHang = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idKhachHang]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}