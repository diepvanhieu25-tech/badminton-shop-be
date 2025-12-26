@extends('layouts.admin')

@section('title', 'Admin - Danh mục')
@section('page_title', 'Danh mục sản phẩm')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Quản lý danh mục sản phẩm cửa hàng cầu lông</div>
    <a href="/admin/category/create" class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-md">
        + Thêm danh mục mới
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <!-- Tìm kiếm & Bộ lọc -->
    <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row gap-3">
        <input type="text" 
               class="flex-1 px-4 py-2.5 rounded-lg border border-slate-300 bg-slate-50 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
               placeholder="Tìm kiếm danh mục..." />

        <select class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500">
            <option value="">Tất cả trạng thái</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <!-- Bảng danh sách danh mục -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6 text-left font-medium">Tên danh mục</th>
                    <th class="py-4 px-6 text-left font-medium">Slug</th>
                    <th class="py-4 px-6 text-left font-medium">Số sản phẩm</th>
                    <th class="py-4 px-6 text-left font-medium">Trạng thái</th>
                    <th class="py-4 px-6 text-right font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="font-semibold">Vợt cầu lông</div>
                        <div class="text-xs text-slate-500 mt-1">Danh mục chính</div>
                    </td>
                    <td class="py-4 px-6 text-slate-600">vot-cau-long</td>
                    <td class="py-4 px-6">42</td>
                    <td class="py-4 px-6">
                        <x-badge text="Active" tone="success" />
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="/admin/categories/1/edit" 
                           class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition inline-block">
                            Sửa
                        </a>
                        <button onclick="confirm('Xóa danh mục này?')" 
                                class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">
                            Xóa
                        </button>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="font-semibold">Giày cầu lông</div>
                    </td>
                    <td class="py-4 px-6 text-slate-600">giay-cau-long</td>
                    <td class="py-4 px-6">28</td>
                    <td class="py-4 px-6">
                        <x-badge text="Active" tone="success" />
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="/admin/categories/2/edit" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition inline-block">Sửa</a>
                        <button class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">Xóa</button>
                    </td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="py-4 px-6">
                        <div class="font-semibold">Phụ kiện</div>
                        <div class="text-xs text-slate-500 mt-1">Quấn cán, dây căng, túi...</div>
                    </td>
                    <td class="py-4 px-6 text-slate-600">phu-kien</td>
                    <td class="py-4 px-6">15</td>
                    <td class="py-4 px-6">
                        <x-badge text="Inactive" tone="danger" />
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        <a href="/admin/categories/3/edit" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition inline-block">Sửa</a>
                        <button class="px-3 py-1.5 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">Xóa</button>
                    </td>
                </tr>

                <!-- Thêm các dòng khác nếu cần -->
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="p-4 border-t border-slate-200 text-sm text-slate-500 flex items-center justify-between">
        <div>Hiển thị 1-10 của 25 danh mục</div>
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