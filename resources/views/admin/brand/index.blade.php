@extends('layouts.admin')

@section('title', 'Admin - Hãng sản xuất')
@section('page_title', 'Quản lý hãng/thương hiệu')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Quản lý các hãng/thương hiệu sản phẩm cầu lông</div>
    <a href="/admin/brand/create" class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-md">
        + Thêm hãng mới
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <!-- Tìm kiếm -->
    <div class="p-4 border-b border-slate-200">
        <input type="text"
               class="w-full md:w-96 px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
               placeholder="Tìm kiếm hãng/thương hiệu..." />
    </div>

    <!-- Bảng danh sách hãng -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6 text-left font-medium">Logo</th>
                    <th class="py-4 px-6 text-left font-medium">Tên hãng</th>
                    <th class="py-4 px-6 text-left font-medium">Slug</th>
                    <th class="py-4 px-6 text-left font-medium">Quốc gia</th>
                    <th class="py-4 px-6 text-left font-medium">Số sản phẩm</th>
                    <th class="py-4 px-6 text-left font-medium">Trạng thái</th>
                    <th class="py-4 px-6 text-right font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <img src="https://via.placeholder.com/80x40?text=Yonex" alt="Yonex"
                             class="h-10 object-contain rounded border border-slate-200" />
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-semibold">Yonex</div>
                        <div class="text-xs text-slate-500">Hãng vợt hàng đầu thế giới</div>
                    </td>
                    <td class="py-4 px-6 text-slate-600">yonex</td>
                    <td class="py-4 px-6">Nhật Bản 🇯🇵</td>
                    <td class="py-4 px-6">68</td>
                    <td class="py-4 px-6">
                        <x-badge text="Active" tone="success" />
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="/admin/brands/1/edit"
                           class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition inline-block">
                            Sửa
                        </a>
                        <button onclick="confirm('Xóa hãng này?')"
                                class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">
                            Xóa
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <img src="https://via.placeholder.com/80x40?text=Victor" alt="Victor"
                             class="h-10 object-contain rounded border border-slate-200" />
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-semibold">Victor</div>
                    </td>
                    <td class="py-4 px-6 text-slate-600">victor</td>
                    <td class="py-4 px-6">Đài Loan 🇹🇼</td>
                    <td class="py-4 px-6">45</td>
                    <td class="py-4 px-6">
                        <x-badge text="Active" tone="success" />
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="/admin/brands/2/edit" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition inline-block">Sửa</a>
                        <button class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">Xóa</button>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <img src="https://via.placeholder.com/80x40?text=Li-Ning" alt="Li-Ning"
                             class="h-10 object-contain rounded border border-slate-200" />
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-semibold">Li-Ning</div>
                        <div class="text-xs text-slate-500">Thương hiệu Trung Quốc nổi tiếng</div>
                    </td>
                    <td class="py-4 px-6 text-slate-600">li-ning</td>
                    <td class="py-4 px-6">Trung Quốc 🇨🇳</td>
                    <td class="py-4 px-6">32</td>
                    <td class="py-4 px-6">
                        <x-badge text="Active" tone="success" />
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="/admin/brands/3/edit" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition inline-block">Sửa</a>
                        <button class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">Xóa</button>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <img src="https://via.placeholder.com/80x40?text=Apacs" alt="Apacs"
                             class="h-10 object-contain rounded border border-slate-200" />
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-semibold">Apacs</div>
                    </td>
                    <td class="py-4 px-6 text-slate-600">apacs</td>
                    <td class="py-4 px-6">Malaysia 🇲🇾</td>
                    <td class="py-4 px-6">12</td>
                    <td class="py-4 px-6">
                        <x-badge text="Inactive" tone="danger" />
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="/admin/brands/4/edit" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition inline-block">Sửa</a>
                        <button class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">Xóa</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="p-4 border-t border-slate-200 text-sm text-slate-500 flex items-center justify-between">
        <div>Hiển thị 1-10 của 28 hãng</div>
        <div class="flex gap-2">
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">Trước</button>
            <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white">1</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">2</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">3</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">Sau</button>
        </div>
    </div>
</div>
@endsection