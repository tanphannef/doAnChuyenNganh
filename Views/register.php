<?php
// File: Views/register.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký Tài khoản</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #DEAD6F; }
        .register-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 350px;}
        .register-container h2 { margin-bottom: 20px; text-align: center; }
        .register-container label { display: block; margin-bottom: 5px; font-weight: bold; }
        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .register-container button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .register-container button:hover { background-color: #0056b3; }
        .message-box { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; }
        .error { background-color: #fdd; color: red; border: 1px solid #f99; }
        .success { background-color: #dff; color: green; border: 1px solid #9c9; }
        .links { margin-top: 15px; text-align: center; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Đăng ký Tài khoản</h2>

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
        
        <form method="POST" action="../Process/register_process.php">
            <label for="name">Họ và Tên:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Đăng ký</button>
        </form>

        <div class="links">
            <a href="../Views/login.php">Đã có tài khoản? Đăng nhập</a>
        </div>
    </div>
</body>
</html>