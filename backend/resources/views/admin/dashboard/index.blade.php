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
        <div class="text-3xl font-bold text-slate-900">{{ number_format($todayRevenue, 0, ',', '.') }}₫</div>
        <div class="text-sm {{ $revenueGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-2">
            {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% so với hôm qua
        </div>
    </div>

    <!-- Đơn hàng mới -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="text-slate-500 text-sm">Đơn hàng hôm nay</div>
            <div class="text-2xl">🧾</div>
        </div>
        <div class="text-3xl font-bold text-slate-900">{{ $todayOrders }}</div>
        <div class="text-sm {{ $ordersGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-2">
            {{ $ordersGrowth >= 0 ? '+' : '' }}{{ abs($ordersGrowth) }} đơn so với hôm qua
        </div>
    </div>

    <!-- Khách hàng mới -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="text-slate-500 text-sm">Khách hàng tuần này</div>
            <div class="text-2xl">👤</div>
        </div>
        <div class="text-3xl font-bold text-slate-900">{{ $weekCustomers }}</div>
        <div class="text-sm {{ $customersGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-2">
            {{ $customersGrowth >= 0 ? '+' : '' }}{{ $customersGrowth }}% so với tuần trước
        </div>
     </div>

    
</div>

<!-- Biểu đồ & Thống kê chi tiết -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Biểu đồ doanh thu 7 ngày gần nhất -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Doanh thu 7 ngày gần nhất</h3>
         <div class="h-80">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Biểu đồ trạng thái đơn hàng -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Trạng thái đơn hàng hiện tại</h3>
         <div class="h-80">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Sản phẩm bán chạy & Đơn hàng gần đây -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top 5 sản phẩm bán chạy -->
    
   <!-- Đơn hàng gần đây -->
    <div class="divide-y divide-slate-100">
        @forelse($recentOrders as $order)
            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">{{ $order->code }}</div>
                    <div class="text-sm text-slate-600">
                        {{ $order->user?->name ?? $order->receiver_name }} • 
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">{{ number_format($order->total, 0, ',', '.') }}₫</div>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold mt-1
                        @if($order->status == 'pending') bg-blue-50 text-blue-700 border border-blue-200
                        @elseif($order->status == 'processing') bg-purple-50 text-purple-700 border border-purple-200
                        @elseif($order->status == 'shipping') bg-yellow-50 text-yellow-700 border border-yellow-200
                        @elseif($order->status == 'completed') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($order->status == 'cancelled') bg-red-50 text-red-700 border border-red-200
                        @else bg-slate-50 text-slate-700 border border-slate-200
                        @endif">
                        @if($order->status == 'pending') Chờ xử lý 
                        @elseif($order->status == 'shipping') Đang giao
                        @elseif($order->status == 'completed') Hoàn thành
                        @elseif($order->status == 'cancelled') Đã hủy
                        @else {{ $order->status }}
                        @endif
                    </span>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-slate-500">
                Chưa có đơn hàng nào
            </div>
        @endforelse
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ doanh thu 7 ngày
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Doanh thu (triệu ₫)',
                data: @json($revenueData),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
            labels: ['Chờ xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
            datasets: [{
                data: [
                    {{ $orderStatusData['pending'] }},
                    {{ $orderStatusData['processing'] }},
                    {{ $orderStatusData['shipping'] }},
                    {{ $orderStatusData['completed'] }},
                    {{ $orderStatusData['cancelled'] }}
                ],
                backgroundColor: ['#3b82f6', '#8b5cf6', '#f59e0b', '#10b981', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection