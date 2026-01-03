// JavaScript để quản lý hiển thị các mục
document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.sidebar ul li a[data-section]');
    const sections = document.querySelectorAll('.content-section');

    // Xử lý sự kiện nhấn vào link
    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault(); // Ngăn chặn hành động mặc định

            const sectionId = link.getAttribute('data-section');

            // Ẩn tất cả các phần
            sections.forEach(section => {
                section.classList.remove('active');
            });

            // Hiển thị phần được chọn
            document.getElementById(sectionId).classList.add('active');
        });
    });
});
