<?php
// Đảm bảo session đã được khởi động
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Hệ thống</title>
    <style>
        /* Reset và Font chữ chuẩn */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            /* Màu nền Gradient hiện đại */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
        }

        /* Khung Đăng Nhập */
        .login-card { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); 
            width: 100%; 
            max-width: 400px; 
            text-align: center;
        }

        .login-card h2 { 
            margin-bottom: 10px; 
            color: #333; 
            font-size: 24px; 
            text-transform: uppercase;
        }
        
        .login-card p {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        /* Style cho Form */
        .form-group { margin-bottom: 20px; text-align: left; }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: #444; 
            font-size: 14px;
        }
        
        input[type="email"], 
        input[type="password"] { 
            width: 100%; 
            padding: 12px 15px; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            font-size: 15px; 
            transition: 0.3s; 
            background-color: #f9f9f9;
        }

        input:focus { 
            border-color: #667eea; 
            outline: none; 
            background-color: #fff; 
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Nút Đăng Nhập */
        button { 
            width: 100%; 
            padding: 12px; 
            background: linear-gradient(to right, #667eea, #764ba2); 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold; 
            transition: 0.3s; 
            margin-top: 10px;
        }
        
        button:hover { 
            opacity: 0.9; 
            transform: translateY(-2px); 
        }

        /* Thông báo lỗi/thành công */
        .alert { 
            padding: 10px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            font-size: 14px; 
            text-align: left;
        }
        .alert-error { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .alert-success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }

        /* Links bên dưới */
        .footer-links { margin-top: 25px; font-size: 14px; border-top: 1px solid #eee; padding-top: 20px;}
        .footer-links a { text-decoration: none; color: #667eea; font-weight: 600; transition: 0.2s; }
        .footer-links a:hover { text-decoration: underline; color: #764ba2; }
        .divider { margin: 0 5px; color: #ccc; }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="font-size: 40px; margin-bottom: 10px;">🐶</div>
        
        <h2>Đăng Nhập</h2>
        <p>Chào mừng bạn quay trở lại!</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                ⚠️ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                ✅ <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="../Process/login_process.php">
            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email" required placeholder="nhapemail@example.com">
            </div>

            <div class="form-group">
                <label for="matkhau">Mật khẩu</label>
                <input type="password" id="matkhau" name="matkhau" required placeholder="Nhập mật khẩu của bạn">
            </div>

            <button type="submit">Đăng nhập ngay</button>
        </form>

        <div class="footer-links">
            <a href="register.php">Đăng ký tài khoản</a>
            <span class="divider">|</span>
            <a href="forgot_password.php">Quên mật khẩu?</a>
            <br><br>
            <a href="../index.php" style="color: #888; font-weight: normal;">&larr; Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html>