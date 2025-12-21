@extends('layouts.admin')

@section('title', 'Admin - Tổng quan')
@section('page_title', 'Tổng quan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="text-sm text-slate-500">Doanh thu (tạm)</div>
        <div class="mt-2 text-2xl font-bold">12,500,000₫</div>
        <div class="mt-1 text-xs text-slate-500">+12% so với tuần trước</div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="text-sm text-slate-500">Đơn hàng (tạm)</div>
        <div class="mt-2 text-2xl font-bold">128</div>
        <div class="mt-1 text-xs text-slate-500">Hôm nay: 14</div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="text-sm text-slate-500">Sắp hết hàng (tạm)</div>
        <div class="mt-2 text-2xl font-bold">7</div>
        <div class="mt-1 text-xs text-slate-500">Cần nhập thêm</div>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="font-semibold">Đơn hàng gần đây (tạm)</div>
        <div class="mt-3 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-slate-500">
                    <tr class="border-b">
                        <th class="py-2 text-left font-medium">Mã</th>
                        <th class="py-2 text-left font-medium">Khách</th>
                        <th class="py-2 text-left font-medium">Tổng</th>
                        <th class="py-2 text-left font-medium">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr>
                        <td class="py-2">OD001</td>
                        <td class="py-2">Huan</td>
                        <td class="py-2">450,000₫</td>
                        <td class="py-2"><x-badge text="Đang xử lý" tone="info"/></td>
                    </tr>
                    <tr>
                        <td class="py-2">OD002</td>
                        <td class="py-2">Lan</td>
                        <td class="py-2">1,250,000₫</td>
                        <td class="py-2"><x-badge text="Đã thanh toán" tone="success"/></td>
                    </tr>
                    <tr>
                        <td class="py-2">OD003</td>
                        <td class="py-2">Minh</td>
                        <td class="py-2">320,000₫</td>
                        <td class="py-2"><x-badge text="Chờ thanh toán" tone="warning"/></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="font-semibold">Ghi chú hệ thống (tạm)</div>
        <ul class="mt-3 space-y-2 text-sm text-slate-600">
            <li>• Tồn kho thấp: “Vợt A - Size 3U”</li>
            <li>• Có 2 chat hỗ trợ đang mở</li>
            <li>• 1 đơn COD cần xác nhận</li>
        </ul>
    </div>
</div>
@endsection
