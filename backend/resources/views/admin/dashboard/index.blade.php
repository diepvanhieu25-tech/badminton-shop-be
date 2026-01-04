@extends('layouts.admin')

@section('title', 'Admin - Dashboard Demo')
@section('page_title', 'Tổng quan hệ thống')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Xin chào, Admin! 👋</h2>
            <p class="text-slate-500">Giao diện Demo - Đã cập nhật ảnh sản phẩm mẫu.</p>
        </div>
        <div class="flex gap-2">
            <span class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-600 shadow-sm flex items-center gap-2">
                📅 {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute right-0 top-0 h-full w-16 bg-linear-to-l from-emerald-50 to-transparent opacity-50"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Doanh thu hôm nay</p>
                    <h3 class="text-2xl font-bold text-slate-800">18.500.000₫</h3>
                </div>
                <div class="p-3 bg-emerald-100 rounded-xl text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md font-medium mr-2 flex items-center gap-1">↑ 12%</span>
                <span class="text-slate-400">so với hôm qua</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute right-0 top-0 h-full w-16 bg-linear-to-l from-blue-50 to-transparent opacity-50"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Đơn hàng mới</p>
                    <h3 class="text-2xl font-bold text-slate-800">45</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-xl text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-red-600 bg-red-50 px-2 py-0.5 rounded-md font-medium mr-2 flex items-center gap-1">- 2</span>
                <span class="text-slate-400">đơn so với hôm qua</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute right-0 top-0 h-full w-16 bg-linear-to-l from-purple-50 to-transparent opacity-50"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Khách hàng mới</p>
                    <h3 class="text-2xl font-bold text-slate-800">128</h3>
                </div>
                <div class="p-3 bg-purple-100 rounded-xl text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md font-medium mr-2 flex items-center gap-1">↑ 8%</span>
                <span class="text-slate-400">so với tuần trước</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute right-0 top-0 h-full w-16 bg-linear-to-l from-orange-50 to-transparent opacity-50"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <p class="text-slate-500 text-sm font-medium mb-1">Sắp hết hàng</p>
                    <h3 class="text-2xl font-bold text-slate-800">5</h3>
                </div>
                <div class="p-3 bg-orange-100 rounded-xl text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="text-orange-600 bg-orange-50 px-2 py-0.5 rounded-md font-medium mr-2">Cần nhập</span>
                <span class="text-slate-400">sản phẩm</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Doanh thu 7 ngày gần nhất</h3>
                <button class="text-sm text-slate-500 hover:text-slate-700 font-medium">Xem chi tiết &rarr;</button>
            </div>
            <div class="h-80 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Trạng thái đơn hàng</h3>
            <div class="h-64 flex justify-center items-center">
                <canvas id="orderStatusChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Chờ xử lý</span>
                    <span class="font-semibold text-slate-700">15</span>
                </div>
                 <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hoàn thành</span>
                    <span class="font-semibold text-slate-700">65</span>
                </div>
                 <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Đã hủy</span>
                    <span class="font-semibold text-slate-700">5</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">🔥 Top sản phẩm bán chạy</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Sản phẩm</th>
                            <th class="px-6 py-4 text-center">Đã bán</th>
                            <th class="px-6 py-4 text-right">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-lg bg-slate-100 shrink-0 border border-slate-200 overflow-hidden">
                                        <img src="https://placehold.co/100x100/f1f5f9/475569?text=Ao+Thun" alt="Product" class="h-full w-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">Áo Thun Premium</div>
                                        <div class="text-xs text-slate-500">250.000₫</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">120</span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700">30.000.000₫</td>
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-lg bg-slate-100 shrink-0 border border-slate-200 overflow-hidden">
                                        <img src="https://placehold.co/100x100/f1f5f9/475569?text=Jeans" alt="Product" class="h-full w-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">Quần Jeans Slimfit</div>
                                        <div class="text-xs text-slate-500">450.000₫</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">85</span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700">38.250.000₫</td>
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-lg bg-slate-100 shrink-0 border border-slate-200 overflow-hidden">
                                        <img src="https://placehold.co/100x100/f1f5f9/475569?text=Sneaker" alt="Product" class="h-full w-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">Giày Sneaker Basic</div>
                                        <div class="text-xs text-slate-500">800.000₫</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">40</span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700">32.000.000₫</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">📦 Đơn hàng mới nhất</h3>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Xem tất cả</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Mã ĐH</th>
                            <th class="px-6 py-4">Khách hàng</th>
                            <th class="px-6 py-4">Trạng thái</th>
                            <th class="px-6 py-4 text-right">Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-emerald-600">#ORD-2024</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">Nguyễn Văn A</div>
                                <div class="text-xs text-slate-400">Vừa xong</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-blue-50 text-blue-700 border-blue-100">Chờ xử lý</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800">550.000₫</td>
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-emerald-600">#ORD-2023</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">Trần Thị B</div>
                                <div class="text-xs text-slate-400">15 phút trước</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-yellow-50 text-yellow-700 border-yellow-100">Đang giao</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800">1.200.000₫</td>
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-emerald-600">#ORD-2022</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">Lê Văn C</div>
                                <div class="text-xs text-slate-400">1 giờ trước</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-emerald-50 text-emerald-700 border-emerald-100">Hoàn thành</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800">850.000₫</td>
                        </tr>
                         <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-emerald-600">#ORD-2021</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">Phạm Thị D</div>
                                <div class="text-xs text-slate-400">2 giờ trước</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-red-50 text-red-700 border-red-100">Đã hủy</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800">200.000₫</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';
        
        // 1. Biểu đồ doanh thu
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        let gradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'],
                datasets: [{
                    label: 'Doanh thu',
                    data: [12500000, 15000000, 11000000, 18000000, 14500000, 22000000, 18500000],
                    borderColor: '#10b981',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
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
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f1f5f9', drawBorder: false },
                        ticks: { callback: function(value) { return value / 1000000 + 'M'; } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });

        // 2. Biểu đồ trạng thái
        const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Chờ xử lý', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
                datasets: [{
                    data: [15, 20, 12, 65, 5],
                    backgroundColor: ['#3b82f6', '#a855f7', '#f59e0b', '#10b981', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                },
                cutout: '75%'
            }
        });
    </script>
@endsection