@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')
@section('page_title', 'Danh sách đơn hàng')

@section('content')

    {{-- HEADER & ACTIONS --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Đơn hàng</h1>
            <p class="text-sm text-slate-500 mt-1">Quản lý và theo dõi vận đơn.</p>
        </div>

    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
        <form method="GET" class="p-4 flex flex-col md:flex-row gap-4">

            {{-- Search Input with Icon --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}"
                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition"
                    placeholder="Tìm theo mã đơn, tên khách, SĐT...">
            </div>

            {{-- Select Filters --}}
            <div class="flex gap-4">
                {{-- Status Filter --}}
                <div class="relative min-w-[180px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-filter text-slate-400"></i>
                    </div>
                    <select name="status" onchange="this.form.submit()"
                        class="block w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm appearance-none bg-white cursor-pointer">
                        <option value="">Tất cả trạng thái</option>
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </div>
                </div>

                {{-- Sort Filter --}}
                <div class="relative min-w-40">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-arrow-down-wide-short text-slate-400"></i>
                    </div>
                    <select name="sort" onchange="this.form.submit()"
                        class="block w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm appearance-none bg-white cursor-pointer">
                        <option value="date_desc" @selected(request('sort') === 'date_desc')>Mới nhất</option>
                        <option value="date_asc" @selected(request('sort') === 'date_asc')>Cũ nhất</option>
                        <option value="total_desc" @selected(request('sort') === 'total_desc')>Giá trị cao</option>
                        <option value="total_asc" @selected(request('sort') === 'total_asc')>Giá trị thấp</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </div>
                </div>

                {{-- Reset Button --}}
                @if (request()->hasAny(['q', 'status', 'sort']))
                    <a href="{{ route('admin.orders.index') }}"
                        class="px-4 py-2.5 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition flex items-center justify-center gap-2"
                        title="Xóa bộ lọc">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-slate-50 text-slate-500 font-semibold uppercase text-xs tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-6 w-60">Mã đơn</th>
                        <th class="py-4 px-6">Khách hàng</th>
                        <th class="py-4 px-6">Ngày đặt</th>
                        <th class="py-4 px-6 text-center">SL</th>
                        <th class="py-4 px-6">Tổng tiền</th>
                        <th class="py-4 px-6 text-center">Trạng thái</th>
                        <th class="py-4 px-6 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 transition group">
                            {{-- Mã đơn --}}
                            <td class="py-4 px-6 align-top">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                                    {{ $order->code }}
                                </a>
                            </td>

                            {{-- Khách hàng --}}
                            <td class="py-4 px-6 align-top">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0">
                                        {{ substr($order->receiver_name ?? 'K', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $order->receiver_name ?? 'Khách lẻ' }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                            <i class="fa-solid fa-phone text-[10px]"></i> {{ $order->receiver_phone }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Ngày đặt --}}
                            <td class="py-4 px-6 align-top text-slate-600">
                                <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->format('H:i') }}</div>
                            </td>

                            {{-- Số lượng --}}
                            <td class="py-4 px-6 align-top text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                    {{ $order->items->sum('quantity') }}
                                </span>
                            </td>

                            {{-- Tổng tiền --}}
                            <td class="py-4 px-6 align-top font-semibold text-slate-900">
                                {{ number_format($order->total) }}₫
                            </td>

                            {{-- Trạng thái --}}
                            <td class="py-4 px-6 align-top text-center">
                                <x-badge :text="$order->status->label()" :tone="$order->status->color()" />
                            </td>

                            {{-- Hành động --}}
                            <td class="py-4 px-6 align-top text-right">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition"
                                    title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-box-open text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-base font-medium text-slate-500">Không tìm thấy đơn hàng nào</p>
                                    <p class="text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm từ khóa khác.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($orders->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between text-sm">
                <span class="text-slate-500">
                    Hiển thị <b>{{ $orders->firstItem() }}–{{ $orders->lastItem() }}</b> trong
                    <b>{{ $orders->total() }}</b> kết quả
                </span>
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
