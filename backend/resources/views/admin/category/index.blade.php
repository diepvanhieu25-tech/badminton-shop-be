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
       <form action="{{ route('admin.category.index') }}" method="GET" class="flex gap-3">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                placeholder="Tìm danh mục..." />
            
            <button type="submit"
                    class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-medium transition">
                🔍 Tìm
            </button>
            
            @if(request('search'))
                <a href="{{ route('admin.category.index') }}"
                class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium transition">
                    ✕ Xóa
                </a>
            @endif
        </form>

    </div>

    <!-- Bảng danh sách danh mục -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6 text-left font-medium">ID</th>
                    <th class="py-4 px-6 text-left font-medium">Tên</th>
                    <th class="py-4 px-6 text-left font-medium">Ảnh mô tả</th>
                    <th class="py-4 px-6 text-left font-medium">Trạng thái</th>
                    <th class="py-4 px-6 text-right font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($category as $b)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 text-slate-500">{{ $b->id }}</td>
                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $b->name }}</td>
                        <td class="px-5 py-3">
                            @if($b->image_url)
                                <img src="{{ $b->image_url }}" alt="{{ $b->name }}" class="h-8 w-8 rounded-lg object-cover border border-slate-200">
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($b->is_active)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">● Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">● Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.category.edit', $b) }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                ✏️ Sửa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-5 py-10 text-center text-slate-500" colspan="5">Không có dữ liệu.</td>
                    </tr>
                @endforelse



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