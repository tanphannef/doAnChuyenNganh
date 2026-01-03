<?php

function loginUser($email, $password) {
    try {
        $pdo = get_pdo_connection(); 
        
        
        $stmt = $pdo->prepare("SELECT idUser, matkhau, idRole FROM user WHERE Email = ?"); 
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Email hoặc mật khẩu không đúng.";
        }
        
        // ... (phần kiểm tra mật khẩu) ...
        if (password_verify($password, $user['matkhau'])) {
            
            // ... (thiết lập session) ...
            $_SESSION['user_id'] = $user['idUser'];
            $_SESSION['user_role'] = $user['idRole']; // Dùng idRole thay vì vaiTro
            
            return true;
        } else {
            return "Email hoặc mật khẩu không đúng.";
        }

    } catch (PDOException $e) {
        // Tạm thời hiển thị lỗi DB để bạn kiểm tra, sau đó đổi lại thành thông báo chung
        return "Lỗi DB: " . $e->getMessage(); 
    }
}


function registerUser($email, $password, $name) {
    // ... (kiểm tra đầu vào) ...
    
    try {
        $pdo = get_pdo_connection();
        
        // SỬA TÊN BẢNG TỪ 'users' THÀNH 'nguoidung'
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM user WHERE Email = ?");
        $stmt_check->execute([$email]);
        if ($stmt_check->fetchColumn() > 0) {
            return "Email này đã được sử dụng.";
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // SỬA TÊN BẢNG VÀ CỘT: Dùng tenUser và idRole thay vì hoTen và vaiTro
        $stmt_insert = $pdo->prepare(
            "INSERT INTO user (Email, matkhau, tenUser, idRole) VALUES (?, ?, ?, 2)"
        ); // Giả định vai trò mặc định là 2
        
        $success = $stmt_insert->execute([$email, $hashed_password, $name]);

        if ($success) {
            return true;
        } else {
            return "Không thể tạo tài khoản, vui lòng thử lại.";
        }

    } catch (PDOException $e) {
        // Tạm thời hiển thị lỗi DB
        return "Lỗi DB: " . $e->getMessage();
    }
}


/**
 * Xóa session và đăng xuất người dùng.
 * @return bool Luôn trả về true
 */
function logoutUser() {
    // Kiểm tra trạng thái session để đảm bảo nó đang hoạt động
    if (session_status() === PHP_SESSION_ACTIVE) {
        // Hủy bỏ tất cả các biến session
        $_SESSION = array(); 
        
        // Xóa cookie session (nếu có)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Hủy session
        session_destroy();
    }
    return true;
}
// File: Models/auth.php (Tiếp tục)

/**
 * Tạo token đặt lại mật khẩu và gửi email.
 * @param string $email
 * @return bool|string Trả về true nếu thành công, hoặc thông báo lỗi.
 */
function processPasswordReset($email) {
    if (empty($email)) {
        return "Vui lòng nhập email.";
    }

    try {
        $pdo = get_pdo_connection();

        // 1. Tìm người dùng
        $stmt = $pdo->prepare("SELECT idUser FROM user WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Trả về thông báo chung để tránh tiết lộ thông tin email
            return "Email không tồn tại trong hệ thống.";
        }

        $userId = $user['idUser'];
        
        // 2. Tạo Token và Thời gian hết hạn
        $token = bin2hex(random_bytes(32)); 
        $expires = date("Y-m-d H:i:s", time() + 3600); // Token hết hạn sau 1 giờ

        // 3. Lưu Token vào Database (Giả định bạn có bảng 'password_resets')
        // *****************************************************************
        // LƯU Ý: Bạn cần tạo bảng 'password_resets' với các cột: 
        // idUser (FK), token, expires
        // *****************************************************************
        
        $stmt_insert = $pdo->prepare(
            "INSERT INTO password_resets (idUser, token, expires) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE token = VALUES(token), expires = VALUES(expires)"
        );
        $stmt_insert->execute([$userId, $token, $expires]);

        // 4. Gửi Email (Cần thư viện PHPMailer hoặc hàm mail() của PHP)
        $reset_link = "" . $token;
        
        // *****************************************************************
        // THAY THẾ PHẦN NÀY BẰNG LOGIC GỬI EMAIL THỰC TẾ CỦA BẠN
        // Ví dụ: send_reset_email($email, $reset_link);
        // *****************************************************************
        
        // Giả định gửi email thành công:
        return true; 

    } catch (PDOException $e) {
        // Tạm thời hiển thị lỗi DB
        return "Lỗi hệ thống trong quá trình xử lý yêu cầu: " . $e->getMessage();
    }
}
// ... (các hàm loginUser, registerUser, logoutUser) ...

?>