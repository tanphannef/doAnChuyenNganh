<style>
    /* CSS RIÊNG CHO TRANG LIÊN HỆ */
    .contact-wrapper {
        background-color: #fcf9f5; /* Màu nền nhẹ */
        padding: 50px 0;
        font-family: 'Segoe UI', sans-serif;
    }
    
    .contact-section {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-header h2 {
        color: #333;
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .contact-container {
        display: flex;
        flex-wrap: wrap;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* Cột bên trái */
    .contact-info {
        flex: 1;
        background: #ff6b6b; /* Màu chủ đạo */
        color: #fff;
        padding: 40px;
        min-width: 300px;
    }

    .contact-info h3 {
        margin-top: 0;
        font-size: 1.5rem;
    }

    .info-line {
        margin-bottom: 25px;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding-bottom: 15px;
    }

    .info-line strong {
        display: block;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    /* Cột bên phải (Form) */
    .contact-form {
        flex: 1.5;
        padding: 40px;
        min-width: 300px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: 600;
    }

    .form-group input, 
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        box-sizing: border-box; /* Giúp không bị vỡ khung */
    }

    .btn-send {
        background-color: #ff6b6b;
        color: white;
        border: none;
        padding: 12px 30px;
        font-size: 1rem;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-send:hover {
        background-color: #e04f4f;
    }
</style>

<div class="contact-wrapper">
    <section class="contact-section">
        <div class="section-header">
            <h2>Liên Hệ Với Chúng Tôi</h2>
            <p>Giải đáp thắc mắc và chăm sóc khách hàng 24/7</p>
        </div>

        <div class="contact-container">
            <div class="contact-info">
                <h3>Thông Tin</h3>
                <div class="info-line">
                    <strong>📍 Địa chỉ:</strong>
                    123 Đường Thú Cưng, Quận 1, TP.HCM
                </div>
                <div class="info-line">
                    <strong>📞 Hotline:</strong>
                    090 123 4567
                </div>
                <div class="info-line">
                    <strong>📧 Email:</strong>
                    cskh@webthucung.com
                </div>
            </div>

            <div class="contact-form">
                <form action="" method="post">
                    <div class="form-group">
                        <label>Họ tên:</label>
                        <input type="text" name="hoten" placeholder="Nhập tên của bạn...">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" placeholder="Nhập email...">
                    </div>
                    <div class="form-group">
                        <label>Nội dung:</label>
                        <textarea name="noidung" rows="4" placeholder="Bạn cần hỗ trợ gì?"></textarea>
                    </div>
                    <button type="submit" class="btn-send">Gửi Tin Nhắn</button>
                </form>
            </div>
        </div>
    </section>
</div>