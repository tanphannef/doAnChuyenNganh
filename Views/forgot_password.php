<?php
// File: Views/forgot_password.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật khẩu</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #DEAD6F; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 350px;}
        .container h2 { margin-bottom: 20px; text-align: center; }
        .container label { display: block; margin-bottom: 5px; font-weight: bold; }
        .container input[type="email"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .container button { width: 100%; padding: 10px; background-color: #f0ad4e; /* Màu vàng cam cho nút quên MK */ color: white; border: none; border-radius: 4px; cursor: pointer; }
        .container button:hover { background-color: #ec971f; }
        .message-box { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; }
        .error { background-color: #fdd; color: red; border: 1px solid #f99; }
        .success { background-color: #dff; color: green; border: 1px solid #9c9; }
        .links { margin-top: 15px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Quên Mật khẩu</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="message-box error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message-box success">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="../Process/forgot_password_process.php">
            <p>Vui lòng nhập địa chỉ email bạn đã đăng ký. Chúng tôi sẽ gửi một liên kết đặt lại mật khẩu qua email này.</p>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Gửi yêu cầu đặt lại</button>
        </form>

        <div class="links">
            <a href="../Views/login.php">Quay lại Đăng nhập</a>
        </div>
    </div>
</body>
</html>