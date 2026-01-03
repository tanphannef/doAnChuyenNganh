<div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="dashboard.php">Trang Chủ</a></li>
                <li><a href="dashboard.php?act=quanlysanpham">Quản lý sản phẩm</a></li>
                <li><a href="dashboard.php?act=quanlynguoncung">Quản lý nguồn cung cấp</a></li>
                <li><a href="dashboard.php?act=quanlydonhang">Quản lý đơn hàng</a></li>
                <li><a href="../index.php">Cửa Hàng</a></li>
                <li><a href="../Process/logout_process.php">Đăng Xuất</a></li>
            </ul>
        </aside>        

        <!-- Main Content -->
        
        <div class="dashboard">
            <section class="statistics">
                <div class="stat-item">
                    <h2>Số lượng sản phẩm</h2>
                    <p>0</p>
                </div>
                <div class="stat-item">
                    <h2>Số lượng đơn hàng</h2>
                    <p>0</p>
                </div>
            </section>

            <section class="real-time-chart">
                <h2>Tổng sản phẩm bán ra theo thời gian thực</h2>
                <canvas id="realtimeChart"></canvas>
            </section>
            
            <section class="chart">
                <h2>Thống kê loại sản phẩm</h2>
                <div class="chart-container">
                    <div class="pie-chart">
                        <svg viewBox="0 0 32 32">
                            <circle r="16" cx="16" cy="16" class="slice slice-vatdung"></circle>
                            <circle r="16" cx="16" cy="16" class="slice slice-thucan"></circle>
                            <circle r="16" cx="16" cy="16" class="slice slice-phukien"></circle>
                            <circle r="16" cx="16" cy="16" class="slice slice-thucung"></circle>
                        </svg>
                    </div>
                    <ul class="legend">
                        <li><span class="legend-box vatdung"></span>Vật dụng</li>
                        <li><span class="legend-box thucan"></span>Thức ăn</li>
                        <li><span class="legend-box phukien"></span>Phụ kiện</li>
                        <li><span class="legend-box thucung"></span>Thú cưng</li>
                    </ul>
                </div>
            </section>
        </div>
    </div>