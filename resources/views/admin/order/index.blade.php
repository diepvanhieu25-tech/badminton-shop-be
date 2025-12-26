@extends('layouts.admin')

@section('title', 'Admin - Đơn hàng')
@section('page_title', 'Quản lý đơn hàng')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Theo dõi và xử lý tất cả đơn hàng từ cửa hàng cầu lông</div>
    <div class="flex gap-3">
        <input type="text"
               class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
               placeholder="Tìm mã đơn, khách hàng..." />
        <a href="/admin/orders/export" class="px-4 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:opacity-90 transition">
            Xuất Excel
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <!-- Bộ lọc nhanh -->
    <div class="p-4 border-b border-slate-200 flex flex-wrap gap-3">
        <select class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500">
            <option>Tất cả trạng thái</option>
            <option>Mới</option>
            <option>Đã xác nhận</option>
            <option>Đang giao</option>
            <option>Hoàn thành</option>
            <option>Đã hủy</option>
            <option>Trả hàng</option>
        </select>

        <select class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500">
            <option>Ngày đặt: Mới nhất</option>
            <option>Ngày đặt: Cũ nhất</option>
            <option>Tổng tiền: Cao → Thấp</option>
            <option>Tổng tiền: Thấp → Cao</option>
        </select>
    </div>

    <!-- Bảng danh sách đơn hàng -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6 text-left font-medium">Mã đơn hàng</th>
                    <th class="py-4 px-6 text-left font-medium">Khách hàng</th>
                    <th class="py-4 px-6 text-left font-medium">Ngày đặt</th>
                    <th class="py-4 px-6 text-left font-medium">Số lượng SP</th>
                    <th class="py-4 px-6 text-left font-medium">Tổng tiền</th>
                    <th class="py-4 px-6 text-left font-medium">Trạng thái thanh toán</th>
                    <th class="py-4 px-6 text-left font-medium">Trạng thái đơn</th>
                    <th class="py-4 px-6 text-right font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="font-semibold text-emerald-700">#ORD-20241226-001</div>
                        <div class="text-xs text-slate-500">15:42, 26/12/2025</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium">Nguyễn Văn A</div>
                        <div class="text-xs text-slate-500">0901234567</div>
                    </td>
                    <td class="py-4 px-6">26/12/2025</td>
                    <td class="py-4 px-6">3 sản phẩm</td>
                    <td class="py-4 px-6 font-semibold text-lg">2,850,000₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="Đã thanh toán" tone="success" />
                    </td>
                    <td class="py-4 px-6">
                        <x-badge text="Mới" tone="info" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/orders/1" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="font-semibold text-emerald-700">#ORD-20241225-015</div>
                        <div class="text-xs text-slate-500">10:15, 25/12/2025</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium">Trần Thị B</div>
                        <div class="text-xs text-slate-500">tranthib@email.com</div>
                    </td>
                    <td class="py-4 px-6">25/12/2025</td>
                    <td class="py-4 px-6">1 sản phẩm</td>
                    <td class="py-4 px-6 font-semibold text-lg">1,290,000₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="Chưa thanh toán" tone="warning" />
                    </td>
                    <td class="py-4 px-6">
                        <x-badge text="Đã xác nhận" tone="primary" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/orders/15" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="font-semibold text-emerald-700">#ORD-20241224-008</div>
                        <div class="text-xs text-slate-500">18:30, 24/12/2025</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium">Lê Hoàng C</div>
                    </td>
                    <td class="py-4 px-6">24/12/2025</td>
                    <td class="py-4 px-6">5 sản phẩm</td>
                    <td class="py-4 px-6 font-semibold text-lg">5,450,000₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="Đã thanh toán" tone="success" />
                    </td>
                    <td class="py-4 px-6">
                        <x-badge text="Đang giao" tone="primary" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/orders/8" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="font-semibold text-emerald-700">#ORD-20241220-003</div>
                        <div class="text-xs text-slate-500">09:05, 20/12/2025</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium">Phạm Văn D</div>
                    </td>
                    <td class="py-4 px-6">20/12/2025</td>
                    <td class="py-4 px-6">2 sản phẩm</td>
                    <td class="py-4 px-6 font-semibold text-lg">1,800,000₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="Đã thanh toán" tone="success" />
                    </td>
                    <td class="py-4 px-6">
                        <x-badge text="Hoàn thành" tone="success" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/orders/3" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="font-semibold text-red-700">#ORD-20241218-012</div>
                        <div class="text-xs text-slate-500">14:20, 18/12/2025</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium">Khách lẻ (Guest)</div>
                    </td>
                    <td class="py-4 px-6">18/12/2025</td>
                    <td class="py-4 px-6">1 sản phẩm</td>
                    <td class="py-4 px-6 font-semibold text-lg">890,000₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="Đã hủy" tone="danger" />
                    </td>
                    <td class="py-4 px-6">
                        <x-badge text="Đã hủy" tone="danger" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/orders/12" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="p-4 border-t border-slate-200 text-sm text-slate-500 flex items-center justify-between">
        <div>Hiển thị 1-20 của 248 đơn hàng</div>
        <div class="flex gap-2">
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">Trước</button>
            <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white">1</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">2</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">3</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">...</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">13</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">Sau</button>
        </div>
    </div>
</div>
@endsection