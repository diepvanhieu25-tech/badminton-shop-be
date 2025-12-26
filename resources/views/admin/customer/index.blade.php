@extends('layouts.admin')

@section('title', 'Admin - Khách hàng')
@section('page_title', 'Quản lý khách hàng')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Danh sách khách hàng đã đăng ký và mua hàng</div>
    <div class="flex gap-3">
        <input type="text"
               class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
               placeholder="Tìm khách hàng..." />
        <a href="/admin/customer/export" class="px-4 py-2.5 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:opacity-90 transition">
            Xuất Excel
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <!-- Bộ lọc nhanh -->
    <div class="p-4 border-b border-slate-200 flex flex-wrap gap-3">
        <select class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500">
            <option>Tất cả khách hàng</option>
            <option>Đã mua hàng</option>
            <option>Chưa mua hàng</option>
            <option>Khách VIP</option>
        </select>

        <select class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500">
            <option>Ngày đăng ký: Mới nhất</option>
            <option>Ngày đăng ký: Cũ nhất</option>
            <option>Tổng chi tiêu: Cao → Thấp</option>
            <option>Tổng chi tiêu: Thấp → Cao</option>
        </select>
    </div>

    <!-- Bảng danh sách khách hàng -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6 text-left font-medium">Khách hàng</th>
                    <th class="py-4 px-6 text-left font-medium">Email</th>
                    <th class="py-4 px-6 text-left font-medium">Số điện thoại</th>
                    <th class="py-4 px-6 text-left font-medium">Ngày đăng ký</th>
                    <th class="py-4 px-6 text-left font-medium">Tổng đơn hàng</th>
                    <th class="py-4 px-6 text-left font-medium">Tổng chi tiêu</th>
                    <th class="py-4 px-6 text-left font-medium">Trạng thái</th>
                    <th class="py-4 px-6 text-right font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-semibold">
                                NT
                            </div>
                            <div>
                                <div class="font-semibold">Nguyễn Văn A</div>
                                <div class="text-xs text-slate-500">ID: #1001</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">nguyenvana@email.com</td>
                    <td class="py-4 px-6">0901234567</td>
                    <td class="py-4 px-6">15/10/2024</td>
                    <td class="py-4 px-6">12 đơn</td>
                    <td class="py-4 px-6 font-semibold">18,450,000₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="VIP" tone="success" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/customers/1" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">
                                TL
                            </div>
                            <div>
                                <div class="font-semibold">Trần Thị B</div>
                                <div class="text-xs text-slate-500">ID: #1005</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">tranthib@email.com</td>
                    <td class="py-4 px-6">0912345678</td>
                    <td class="py-4 px-6">03/11/2024</td>
                    <td class="py-4 px-6">5 đơn</td>
                    <td class="py-4 px-6 font-semibold">7,890,000₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="Active" tone="success" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/customers/5" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-700 flex items-center justify-center font-semibold">
                                LH
                            </div>
                            <div>
                                <div class="font-semibold">Lê Hoàng C</div>
                                <div class="text-xs text-slate-500">ID: #1012</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">lehoangc@email.com</td>
                    <td class="py-4 px-6">-</td>
                    <td class="py-4 px-6">20/12/2024</td>
                    <td class="py-4 px-6">0 đơn</td>
                    <td class="py-4 px-6">0₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="Mới" tone="info" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/customers/12" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-700 flex items-center justify-center font-semibold">
                                PK
                            </div>
                            <div>
                                <div class="font-semibold">Phạm Văn D</div>
                                <div class="text-xs text-slate-500">ID: #1008</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">phamvand@email.com</td>
                    <td class="py-4 px-6">0923456789</td>
                    <td class="py-4 px-6">01/09/2024</td>
                    <td class="py-4 px-6">3 đơn</td>
                    <td class="py-4 px-6 font-semibold">4,200,000₫</td>
                    <td class="py-4 px-6">
                        <x-badge text="Inactive" tone="danger" />
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/admin/customers/8" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="p-4 border-t border-slate-200 text-sm text-slate-500 flex items-center justify-between">
        <div>Hiển thị 1-20 của 156 khách hàng</div>
        <div class="flex gap-2">
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">Trước</button>
            <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white">1</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">2</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">3</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">...</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">8</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">Sau</button>
        </div>
    </div>
</div>
@endsection