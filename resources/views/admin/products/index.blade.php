@extends('layouts.admin')

@section('title', 'Admin - Sản phẩm')
@section('page_title', 'Sản phẩm')

@section('content')
<div class="flex items-center justify-between">
    <div class="text-sm text-slate-500">Quản lý sản phẩm </div>
    <a href="/admin/products/create" class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:opacity-90">
        + Thêm sản phẩm
    </a>
</div>

<div class="mt-4 bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-4 border-b border-slate-200 flex gap-2">
        <input class="w-full md:w-96 px-3 py-2 rounded-lg border border-slate-200 bg-slate-50"
               placeholder="Tìm sản phẩm (tạm)" />
        <select class="px-3 py-2 rounded-lg border border-slate-200 bg-white">
            <option>Tất cả trạng thái</option>
            <option>Active</option>
            <option>Draft</option>
            <option>Inactive</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-slate-500">
                <tr class="border-b">
                    <th class="py-3 px-4 text-left font-medium">Sản phẩm</th>
                    <th class="py-3 px-4 text-left font-medium">SKU</th>
                    <th class="py-3 px-4 text-left font-medium">Giá</th>
                    <th class="py-3 px-4 text-left font-medium">Tồn</th>
                    <th class="py-3 px-4 text-left font-medium">Trạng thái</th>
                    <th class="py-3 px-4 text-right font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr >
                    <td class="py-3 px-4">
                        <div class="font-semibold">Vợt cầu lông A</div>
                        <div class="text-xs text-slate-500">Danh mục: Vợt</div>
                    </td>
                    <td class="py-3 px-4">SKU-A-001</td>
                    <td class="py-3 px-4">1,200,000₫</td>
                    <td class="py-3 px-4">24</td>
                    <td class="py-3 px-4"><x-badge text="Active" tone="success"/></td>
                    <td class="py-3 px-4 text-right">
                        <a href="/admin/products/1/edit" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">Sửa</a>
                    </td>
                </tr>

                <tr>
                    <td class="py-3 px-4">
                        <div class="font-semibold">Giày B</div>
                        <div class="text-xs text-slate-500">Danh mục: Giày</div>
                    </td>
                    <td class="py-3 px-4">SKU-B-003</td>
                    <td class="py-3 px-4">890,000₫</td>
                    <td class="py-3 px-4">3</td>
                    <td class="py-3 px-4"><x-badge text="Low stock" tone="warning"/></td>
                    <td class="py-3 px-4 text-right">
                        <a href="/admin/products/2/edit" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">Sửa</a>
                    </td>
                </tr>

                <tr>
                    <td class="py-3 px-4">
                        <div class="font-semibold">Áo C</div>
                        <div class="text-xs text-slate-500">Danh mục: Áo</div>
                    </td>
                    <td class="py-3 px-4">SKU-C-010</td>
                    <td class="py-3 px-4">250,000₫</td>
                    <td class="py-3 px-4">0</td>
                    <td class="py-3 px-4"><x-badge text="Inactive" tone="danger"/></td>
                    <td class="py-3 px-4 text-right">
                        <a href="/admin/products/3/edit" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">Sửa</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-slate-200 text-sm text-slate-500">
        Pagination (tạm)
    </div>
</div>
@endsection
