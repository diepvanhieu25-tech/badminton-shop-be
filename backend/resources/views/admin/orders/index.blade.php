@extends('layouts.admin')

@section('title', 'Admin - Đơn hàng')
@section('page_title', 'Quản lý đơn hàng')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="text-xl font-bold text-slate-900">Danh sách đơn hàng</div>
    </div>
    <div class="text-sm text-slate-500">Theo dõi và xử lý tất cả đơn hàng từ cửa hàng cầu lông</div>
    @if (Route::has('admin.orders.export'))
        <a href="{{ route('admin.orders.export') }}"
        class="px-4 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:opacity-90 transition">
            Xuất Excel
        </a>
    @endif
</div>

<form method="GET" class="bg-white rounded-xl border border-slate-200 p-4 mb-5 flex flex-wrap gap-3">

    <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
        placeholder="Tìm mã đơn, khách hàng..."
    />

    <select
        name="status"
        class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500"
        onchange="this.form.submit()"
    >
        <option value="">Tất cả trạng thái</option>
        <option value="confirmed" @selected(request('status')==='confirmed')>Đã xác nhận</option>
        <option value="completed" @selected(request('status')==='completed')>Hoàn thành</option>
        <option value="cancelled" @selected(request('status')==='cancelled')>Đã hủy</option>
    </select>

    <select name="sort"
        class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500"
        onchange="this.form.submit()"
    >
        <option value="date_desc" @selected($filters['sort'] === 'date_desc')>Mới nhất</option>
        <option value="date_asc" @selected($filters['sort'] === 'date_asc')>Cũ nhất</option>
        <option value="total_desc" @selected($filters['sort'] === 'total_desc')>Tổng tiền ↓</option>
        <option value="total_asc" @selected($filters['sort'] === 'total_asc')>Tổng tiền ↑</option>
    </select>

    <a href="{{ route('admin.orders.index') }}"
         class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white
        text-slate-600 hover:bg-slate-100 hover:text-slate-900
        transition flex items-center gap-2">
        <i class="fa-solid fa-rotate-left text-sm"></i>
        Reset
    </a>

</form>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6 text-left font-medium">Mã đơn</th>
                    <th class="py-4 px-6 text-left font-medium">Khách hàng</th>
                    <th class="py-4 px-6 text-left font-medium">Ngày đặt</th>
                    <th class="py-4 px-6 text-left font-medium">SL SP</th>
                    <th class="py-4 px-6 text-left font-medium">Tổng tiền</th>
                    <th class="py-4 px-6 text-left font-medium">Trạng thái</th>
                    <th class="py-4 px-6 text-right font-medium">Hành động</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
            @forelse($orders as $order)
                <tr class="hover:bg-slate-50 transition cursor-pointer"
                    onclick="window.location='{{ route('admin.orders.show', $order) }}'">

                    <td class="py-4 px-6">
                        <div class="font-semibold text-emerald-700">
                            {{ $order->code }}
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ $order->created_at->format('H:i, d/m/Y') }}
                        </div>
                    </td>

                    <td class="py-4 px-6">
                        <div class="font-medium">
                            {{ $order->receiver_name ?? 'Khách lẻ' }}
                        </div>
                        <div class="text-xs text-slate-500">
                            {{ $order->receiver_phone }}
                        </div>
                    </td>

                    <td class="py-4 px-6">
                        {{ $order->created_at->format('d/m/Y') }}
                    </td>

                    <td class="py-4 px-6">
                        {{ $order->items->sum('quantity') }} sản phẩm
                    </td>

                    <td class="py-4 px-6 font-semibold text-lg">
                        {{ number_format($order->total) }}₫
                    </td>

                    <td class="py-4 px-6">
                        <x-badge
                            :text="__($order->status->value)"
                            :tone="match($order->status->value) {
                                'pending' => 'info',
                                'confirmed', 'shipping' => 'primary',
                                'completed' => 'success',
                                'cancelled', 'returned' => 'danger',
                                default => 'secondary'
                            }"
                        />
                    </td>

                    <td class="py-4 px-6 text-right">
                       <a href="{{ route('admin.orders.show', $order) }}"
                          class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-slate-500">
                        Không có đơn hàng nào
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-slate-200 flex items-center justify-between text-sm">
        <div class="text-slate-500">
            Hiển thị
            {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
            của {{ $orders->total() }} đơn hàng
        </div>

        {{ $orders->withQueryString()->links() }}
    </div>

</div>
@endsection