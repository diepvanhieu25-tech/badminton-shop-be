@extends('layouts.admin')

 @section('title', 'Admin - Dashboard')
@section('page_title', 'Tổng quan')

@section('content')
<!-- Thống kê nhanh - Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Doanh thu hôm nay -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="text-slate-500 text-sm">Doanh thu hôm nay</div>
            <div class="text-2xl">💰</div>
        </div>
        <div class="text-3xl font-bold text-slate-900">12,450,000₫</div>
        <div class="text-sm text-emerald-600 mt-2">+18% so với hôm qua</div>
    </div>

    <!-- Đơn hàng mới -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="text-slate-500 text-sm">Đơn hàng mới</div>
            <div class="text-2xl">🧾</div>
        </div>
        <div class="text-3xl font-bold text-slate-900">24</div>
        <div class="text-sm text-emerald-600 mt-2">+5 đơn so với hôm qua</div>
    </div>

    <!-- Khách hàng mới -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="text-slate-500 text-sm">Khách hàng mới</div>
            <div class="text-2xl">👤</div>
        </div>
        <div class="text-3xl font-bold text-slate-900">18</div>
        <div class="text-sm text-emerald-600 mt-2">+12% tuần này</div>
    </div>

    <!-- Sản phẩm bán chạy -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="text-slate-500 text-sm">Sản phẩm đã bán (tháng)</div>
            <div class="text-2xl">🏸</div>
        </div>
        <div class="text-3xl font-bold text-slate-900">342</div>
        <div class="text-sm text-slate-600 mt-2">Yonex Astrox 99 Pro dẫn đầu</div>
    </div>
</div>

<!-- Biểu đồ & Thống kê chi tiết -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Biểu đồ doanh thu 7 ngày gần nhất -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Doanh thu 7 ngày gần nhất</h3>
        <div class="h-80">
            <!-- Placeholder cho biểu đồ Line Chart -->
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Biểu đồ trạng thái đơn hàng -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Trạng thái đơn hàng hiện tại</h3>
        <div class="h-80">
            <!-- Placeholder cho biểu đồ Doughnut/Pie -->
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Sản phẩm bán chạy & Đơn hàng gần đây -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top 5 sản phẩm bán chạy -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Top 5 sản phẩm bán chạy (tháng này)</h3>
        </div>
        <div class="divide-y divide-slate-100">
            <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-200 rounded-lg border border-slate-300"></div>
                    <div>
                        <div class="font-medium">Yonex Astrox 99 Pro</div>
                        <div class="text-sm text-slate-500">Vợt cầu lông</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">68 bán</div>
                    <div class="text-sm text-emerald-600">+24%</div>
                </div>
            </div>

            <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-200 rounded-lg border border-slate-300"></div>
                    <div>
                        <div class="font-medium">Giày Yonex Power Cushion 65Z</div>
                        <div class="text-sm text-slate-500">Giày cầu lông</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">52 bán</div>
                    <div class="text-sm text-emerald-600">+15%</div>
                </div>
            </div>

            <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-200 rounded-lg border border-slate-300"></div>
                    <div>
                        <div class="font-medium">Victor Thruster K Falcon</div>
                        <div class="text-sm text-slate-500">Vợt cầu lông</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">41 bán</div>
                </div>
            </div>

            <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-200 rounded-lg border border-slate-300"></div>
                    <div>
                        <div class="font-medium">Áo cầu lông Li-Ning 2024</div>
                        <div class="text-sm text-slate-500">Áo thi đấu</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">38 bán</div>
                </div>
            </div>

            <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-200 rounded-lg border border-slate-300"></div>
                    <div>
                        <div class="font-medium">Dây căng BG66 Ultimax</div>
                        <div class="text-sm text-slate-500">Phụ kiện</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">35 bán</div>
                    <div class="text-sm text-red-600">-8%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Đơn hàng gần đây</h3>
            <a href="/admin/orders" class="text-sm text-emerald-600 hover:underline">Xem tất cả →</a>
        </div>
        <div class="divide-y divide-slate-100">
            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251228-001</div>
                    <div class="text-sm text-slate-600">Nguyễn Văn A • 2 sản phẩm</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">2,850,000₫</div>
                    <x-badge text="Mới" tone="info" class="mt-1" />
                </div>
            </div>

            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251227-015</div>
                    <div class="text-sm text-slate-600">Trần Thị B • 1 sản phẩm</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">1,290,000₫</div>
                    <x-badge text="Đang giao" tone="primary" class="mt-1" />
                </div>
            </div>

            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251227-008</div>
                    <div class="text-sm text-slate-600">Lê Hoàng C • 3 sản phẩm</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">4,120,000₫</div>
                    <x-badge text="Hoàn thành" tone="success" class="mt-1" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm Chart.js để hiển thị biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ doanh thu 7 ngày
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['22/12', '23/12', '24/12', '25/12', '26/12', '27/12', '28/12'],
            datasets: [{
                label: 'Doanh thu (triệu ₫)',
                data: [8.2, 10.5, 15.8, 18.3, 10.5, 9.8, 12.45],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Biểu đồ trạng thái đơn hàng
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Mới', 'Đã xác nhận', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
            datasets: [{
                data: [24, 18, 32, 145, 8],
                backgroundColor: [
                    '#3b82f6', // info
                    '#8b5cf6', // primary
                    '#f59e0b', // warning
                    '#10b981', // success
                    '#ef4444'  // danger
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
 @endsection