# HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG HỆ THỐNG

Dưới đây là các bước chi tiết để cài đặt cơ sở dữ liệu và thông tin truy cập vào hệ thống.

---

## 1. Hướng dẫn cài đặt Database (phpMyAdmin)

Để thiết lập cơ sở dữ liệu cho dự án, bạn vui lòng thực hiện theo các bước sau:

1.  **Khởi động môi trường:** Mở bảng điều khiển XAMPP/WampServer và đảm bảo **Apache** và **MySQL** đang ở trạng thái `Running`.
2.  **Truy cập quản trị:** Mở trình duyệt web và đi tới địa chỉ: `http://localhost/phpmyadmin/`.
3.  **Tạo cơ sở dữ liệu:**
    * Nhấn vào mục **New** ở danh sách bên trái.
    * Nhập tên Database qlpetshop.
    * Nhấn nút **Create**.
4.  **Nhập dữ liệu (Import):**
    * Chọn Database vừa tạo ở cột bên trái.
    * Chọn tab **Import** ở thanh menu phía trên.
    * Nhấn nút **Choose File** (Chọn tệp) và tìm đến file có đuôi `.sql` trong thư mục Database.
    * Cuộn xuống dưới cùng và nhấn nút **Import** (hoặc **Go**) để hoàn tất.

---

## 2. Hướng dẫn Đăng nhập Admin

Tài khoản quản trị viên có toàn quyền điều phối các chức năng trong hệ thống.

* **Tài khoản:** `admin@gmail.com`
* **Mật khẩu:** `123`

---

## 3. Hướng dẫn Đăng nhập User (Khách hàng)

Đối với người dùng thông thường, hệ thống hỗ trợ hai phương thức truy cập:

### A. Sử dụng tài khoản mẫu
Nếu bạn muốn kiểm tra nhanh các tính năng dành cho khách hàng, hãy sử dụng thông tin sau:
* **Tài khoản:** `khachhang@gmail.com`
* **Mật khẩu:** `123`

### B. Đăng ký tài khoản mới
Bạn hoàn toàn có thể tự tạo tài khoản cá nhân bằng cách:
1.  Truy cập vào mục **Đăng ký** trên trang chủ.
2.  Điền thông tin theo yêu cầu của hệ thống.
3.  Sau khi hoàn tất, hệ thống sẽ tự động lưu thông tin và bạn có thể đăng nhập bằng tài khoản vừa tạo.

---

*Lưu ý: Nếu gặp lỗi về hình ảnh sản phẩm xin vui lòng vào tài khoản admin, vào phần quản lý sản phẩm, vào phần sản phẩm, nhấn vào sửa để thêm lại hình ảnh.*
