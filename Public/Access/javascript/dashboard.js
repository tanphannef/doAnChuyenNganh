
    // Lấy context của canvas
    const ctx = document.getElementById('realtimeChart').getContext('2d');

    // Dữ liệu ban đầu
    let chartData = {
        labels: [], // Thời gian thực
        datasets: [{
            label: 'Số lượng sản phẩm bán ra',
            data: [], // Số lượng sản phẩm bán ra
            borderColor: '#4CAF50',
            backgroundColor: 'rgba(76, 175, 80, 0.2)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    };

    // Tạo biểu đồ
    const realtimeChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Thời gian'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Số lượng'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Hàm cập nhật dữ liệu thời gian thực
    function updateChartData() {
        const now = new Date();
        const timeLabel = `${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;
        const randomValue = Math.floor(Math.random() * 50 + 10); // Số lượng ngẫu nhiên

        // Thêm dữ liệu mới
        chartData.labels.push(timeLabel);
        chartData.datasets[0].data.push(randomValue);

        // Giới hạn số điểm trên biểu đồ
        if (chartData.labels.length > 10) {
            chartData.labels.shift(); // Xóa thời gian cũ
            chartData.datasets[0].data.shift(); // Xóa dữ liệu cũ
        }

        // Cập nhật biểu đồ
        realtimeChart.update();
    }

    // Cập nhật biểu đồ mỗi 2 giây
    setInterval(updateChartData, 2000);

