{{-- resources/views/admin/dashboard/partials/scripts.blade.php --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Setup Chart Defaults
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // 2. Revenue Chart (Line with Gradient)
        const revenueEl = document.getElementById('revenueChart');
        if (revenueEl) {
            const revenueCtx = revenueEl.getContext('2d');
            
            // Tạo gradient màu xanh
            let gradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueLabels ?? []),
                    datasets: [{
                        label: 'Doanh thu',
                        data: @json($revenueData ?? []),
                        borderColor: '#10b981',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10b981',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4, // Đường cong mềm mại
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 13 },
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4], color: '#f1f5f9' },
                            ticks: {
                                callback: function(value) {
                                    // Rút gọn số liệu trục Y (VD: 1M)
                                    if(value >= 1000000) return value / 1000000 + 'M';
                                    if(value >= 1000) return value / 1000 + 'k';
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 3. Order Status Chart (Doughnut)
        const statusEl = document.getElementById('orderStatusChart');
        if (statusEl) {
            const statusCtx = statusEl.getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Chờ xử lý', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
                    datasets: [{
                        data: [
                            {{ $orderStatusData['pending'] ?? 0 }},
                            {{ $orderStatusData['processing'] ?? 0 }},
                            {{ $orderStatusData['shipping'] ?? 0 }},
                            {{ $orderStatusData['completed'] ?? 0 }},
                            {{ $orderStatusData['cancelled'] ?? 0 }}
                        ],
                        // Màu tương ứng: Blue, Purple, Yellow, Green, Red
                        backgroundColor: ['#3b82f6', '#a855f7', '#f59e0b', '#10b981', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%', // Làm vòng tròn mỏng hơn
                    plugins: {
                        legend: { display: false } // Đã dùng custom legend HTML
                    }
                }
            });
        }
    });
</script>