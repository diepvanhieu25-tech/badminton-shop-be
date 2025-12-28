@extends('layouts.admin')

@section('title', 'Admin - Báo cáo')
@section('page_title', 'Báo cáo & Thống kê')

@section('content')
<!-- Bộ lọc thời gian -->
<div class="mb-8 bg-white rounded-xl border border-slate-200 p-6">
    <h3 class="text-lg font-semibold text-slate-900 mb-4">Chọn khoảng thời gian báo cáo</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select id="report-range" class="px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition">
            <option value="today">Hôm nay</option>
            <option value="yesterday">Hôm qua</option>
            <option value="7days" selected>7 ngày gần nhất</option>
            <option value="30days">30 ngày gần nhất</option>
            <option value="this_month">Tháng này</option>
            <option value="last_month">Tháng trước</option>
            <option value="this_year">Năm nay</option>
            <option value="custom">Tùy chỉnh</option>
        </select>

        <input type="date" id="start-date" class="px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500">
        <input type="date" id="end-date" class="px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500">

        <button class="px-6 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition shadow-md">
            Xem báo cáo
        </button>
    </div>
</div>

<!-- Các card thống kê chính -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="text-slate-500 text-sm mb-2">Tổng doanh thu</div>
        <div class="text-3xl font-bold text-slate-900">98,450,000₫</div>
        <div class="text-sm text-emerald-600 mt-2">+22.5% so với kỳ trước</div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="text-slate-500 text-sm mb-2">Tổng đơn hàng</div>
        <div class="text-3xl font-bold text-slate-900">348</div>
        <div class="text-sm text-emerald-600 mt-2">+15 đơn</div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="text-slate-500 text-sm mb-2">Giá trị trung bình đơn hàng</div>
        <div class="text-3xl font-bold text-slate-900">283,000₫</div>
        <div class="text-sm text-red-600 mt-2">-5.2%</div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="text-slate-500 text-sm mb-2">Sản phẩm đã bán</div>
        <div class="text-3xl font-bold text-slate-900">1,248</div>
        <div class="text-sm text-emerald-600 mt-2">+18%</div>
    </div>
</div>

<!-- Biểu đồ doanh thu theo thời gian -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900">Doanh thu theo ngày</h3>
            <a href="/admin/reports/export-revenue" class="text-sm text-emerald-600 hover:underline">Xuất Excel →</a>
        </div>
        <div class="h-96">
            <canvas id="revenueTimeChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Top 5 hãng bán chạy</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-slate-200 border"></div>
                    <div>Yonex</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">42,800,000₫</div>
                    <div class="text-xs text-emerald-600">43.5%</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-slate-200 border"></div>
                    <div>Victor</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">28,500,000₫</div>
                    <div class="text-xs text-emerald-600">29%</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-slate-200 border"></div>
                    <div>Li-Ning</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">15,200,000₫</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-slate-200 border"></div>
                    <div>Apacs</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">8,900,000₫</div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-slate-200 border"></div>
                    <div>Mizuno</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">3,050,000₫</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top sản phẩm & Danh mục bán chạy -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Top 10 sản phẩm bán chạy</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="py-3 px-6 text-left">Sản phẩm</th>
                    <th class="py-3 px-6 text-center">Đã bán</th>
                    <th class="py-3 px-6 text-right">Doanh thu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-6">Yonex Astrox 99 Pro</td>
                    <td class="py-3 px-6 text-center">68</td>
                    <td class="py-3 px-6 text-right font-medium">27,200,000₫</td>
                </tr>
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-6">Giày Yonex Power Cushion 65Z</td>
                    <td class="py-3 px-6 text-center">52</td>
                    <td class="py-3 px-6 text-right font-medium">18,720,000₫</td>
                </tr>
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-6">Victor Thruster K Falcon</td>
                    <td class="py-3 px-6 text-center">41</td>
                    <td class="py-3 px-6 text-right font-medium">16,400,000₫</td>
                </tr>
                <!-- Thêm các dòng khác tương tự -->
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Doanh thu theo danh mục</h3>
        <div class="h-80">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ doanh thu theo ngày
    new Chart(document.getElementById('revenueTimeChart'), {
        type: 'bar',
        data: {
            labels: ['22/12', '23/12', '24/12', '25/12', '26/12', '27/12', '28/12'],
            datasets: [{
                label: 'Doanh thu (triệu ₫)',
                data: [8.2, 10.5, 15.8, 18.3, 10.5, 22.8, 12.45],
                backgroundColor: '#10b981',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Biểu đồ doanh thu theo danh mục
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: ['Vợt cầu lông', 'Giày cầu lông', 'Áo quần', 'Phụ kiện', 'Túi đựng'],
            datasets: [{
                data: [48, 28, 12, 8, 4],
                backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'right' } }
        }
    });
</script>
@endsection