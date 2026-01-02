@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('page_title', 'Chi tiết đơn hàng')

@section('content')

{{-- HEADER --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="text-lg font-bold text-slate-900">
            Đơn hàng {{ $order->code }}
        </div>
        <div class="text-sm text-slate-500">
            Tạo lúc {{ $order->created_at->format('H:i d/m/Y') }}
        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}"
       class="px-4 py-2 rounded-lg border border-slate-300 text-sm hover:bg-slate-50">
        ← Quay lại danh sách
    </a>
</div>

{{-- ORDER + CUSTOMER --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- THÔNG TIN KHÁCH HÀNG --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold mb-4">Thông tin người nhận</h3>

        <div class="space-y-2 text-sm">
            <div><b>Tên:</b> {{ $order->receiver_name ?? 'Khách lẻ' }}</div>
            <div><b>SĐT:</b> {{ $order->receiver_phone }}</div>
            <div><b>Địa chỉ:</b> {{ $order->shipping_address }}</div>
            @if($order->note)
                <div><b>Ghi chú:</b> {{ $order->note }}</div>
            @endif
        </div>
    </div>

    {{-- TRẠNG THÁI --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold mb-4">Trạng thái</h3>

        <div class="space-y-3 text-sm">
            <div>
                <b>Thanh toán:</b>
                <span class="ml-1">
                    {{ __('' . $order->status) }}
                </span>
            </div>
        </div>

    </div>

    {{-- TỔNG TIỀN --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold mb-4">Thanh toán</h3>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Tạm tính</span>
                <span>{{ number_format($order->subtotal) }}₫</span>
            </div>

            <div class="flex justify-between">
                <span>Phí ship</span>
                <span>{{ number_format($order->shipping_fee) }}₫</span>
            </div>

            <div class="border-t pt-2 flex justify-between font-bold text-base">
                <span>Tổng cộng</span>
                <span>{{ number_format($order->total) }}₫</span>
            </div>
        </div>
    </div>

</div>

{{-- DANH SÁCH SẢN PHẨM --}}
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-5 border-b font-semibold">
        Sản phẩm trong đơn
    </div>

    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
            <tr>
                <th class="py-3 px-5 text-left">Sản phẩm</th>
                <th class="py-3 px-5 text-left">Biến thể</th>
                <th class="py-3 px-5 text-center">SL</th>
                <th class="py-3 px-5 text-right">Đơn giá</th>
                <th class="py-3 px-5 text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($order->items as $item)
                <tr>
                    <td class="py-3 px-5">
                        {{ $item->product_name }}
                    </td>
                    <td class="py-3 px-5">
                        {{ $item->variant_name }}
                    </td>
                    <td class="py-3 px-5 text-center">
                        {{ $item->quantity }}
                    </td>
                    <td class="py-3 px-5 text-right">
                        {{ number_format($item->unit_price) }}₫
                    </td>
                    <td class="py-3 px-5 text-right font-semibold">
                        {{ number_format($item->total_price) }}₫
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
