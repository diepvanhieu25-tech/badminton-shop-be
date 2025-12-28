@extends('layouts.admin')

@section('title', 'Admin - Chi tiết danh mục')
@section('page_title', 'Chi tiết danh mục: {{ $category->name }}')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Thông tin chi tiết và thống kê của danh mục</div>
    <div class="flex gap-3">
        <a href="/admin/categories/{{ $category->id }}/edit" 
           class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-md">
            Sửa danh mục
        </a>
        <a href="/admin/categories" 
           class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            ← Quay lại danh sách
        </a>
    </div>
</div>

<!-- Thông tin chính danh mục -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card thông tin danh mục -->
    <div class="lg:col-span-1 bg-white rounded-xl border border-slate-200 p-6">
        <div class="text-center">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" 
                     alt="{{ $category->name }}" 
                     class="w-48 h-48 mx-auto object-cover rounded-xl border border-slate-200 shadow-md mb-6" />
            @else
                <div class="w-48 h-48 mx-auto bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 flex items-center justify-center mb-6">
                    <span class="text-5xl text-slate-400">📁</span>
                </div>
            @endif

            <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ $category->name }}</h2>
            <p class="text-sm text-slate-500 mb-4">{{ $category->slug }}</p>

            <div class="space-y-3 text-sm">
                @if($category->parent)
                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Danh mục cha:</span>
                    <span class="font-medium">{{ $category->parent->name }}</span>
                </div>
                @else
                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Loại:</span>
                    <span class="font-medium">Danh mục gốc</span>
                </div>
                @endif

                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Trạng thái:</span>
                    <x-badge text="{{ $category->status == 'active' ? 'Active' : 'Inactive' }}" 
                             tone="{{ $category->status == 'active' ? 'success' : 'danger' }}" />
                </div>

                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Sản phẩm:</span>
                    <span class="font-semibold text-lg">{{ $category->products_count ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê nhanh -->
    <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-slate-900">{{ $category->products_count ?? 0 }}</div>
            <div class="text-sm text-slate-500 mt-1">Tổng sản phẩm</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">
                {{ number_format($category->total_revenue ?? 0) }}₫
            </div>
            <div class="text-sm text-slate-500 mt-1">Doanh thu tháng này</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-slate-900">{{ $category->total_sold ?? 0 }}</div>
            <div class="text-sm text-slate-500 mt-1">Số lượng đã bán</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">
                {{ $category->revenue_percentage ?? '0' }}%
            </div>
            <div class="text-sm text-slate-500 mt-1">Tỷ trọng doanh thu</div>
        </div>
    </div>
</div>

<!-- Mô tả danh mục & Sản phẩm nổi bật -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Mô tả danh mục -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Mô tả danh mục</h3>
        <div class="text-slate-700 leading-relaxed prose max-w-none">
            {!! $category->description ? nl2br(e($category->description)) : '<p class="text-slate-500 italic">Chưa có mô tả cho danh mục này.</p>' !!}
        </div>
    </div>

    <!-- Sản phẩm nổi bật của danh mục -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Sản phẩm nổi bật</h3>
            <a href="/admin/products?category={{ $category->id }}" class="text-sm text-emerald-600 hover:underline">
                Xem tất cả ({{ $category->products_count ?? 0 }}) →
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            <div class="p-4 flex items-center gap-4 hover:bg-slate-50">
                <img src="https://via.placeholder.com/80x80" alt="Sản phẩm" 
                     class="w-16 h-16 object-cover rounded-lg border" />
                <div class="flex-1">
                    <div class="font-medium">Yonex Astrox 99 Pro</div>
                    <div class="text-sm text-slate-500">Hãng: Yonex</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">4,000,000₫</div>
                    <div class="text-xs text-emerald-600">Đã bán: 68</div>
                </div>
            </div>

            <div class="p-4 flex items-center gap-4 hover:bg-slate-50">
                <img src="https://via.placeholder.com/80x80" alt="Sản phẩm" 
                     class="w-16 h-16 object-cover rounded-lg border" />
                <div class="flex-1">
                    <div class="font-medium">Victor Thruster K Falcon</div>
                    <div class="text-sm text-slate-500">Hãng: Victor</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">3,800,000₫</div>
                    <div class="text-xs text-emerald-600">Đã bán: 41</div>
                </div>
            </div>

            <div class="p-4 flex items-center gap-4 hover:bg-slate-50">
                <img src="https://via.placeholder.com/80x80" alt="Sản phẩm" 
                     class="w-16 h-16 object-cover rounded-lg border" />
                <div class="flex-1">
                    <div class="font-medium">Li-Ning Aeronaut 9000</div>
                    <div class="text-sm text-slate-500">Hãng: Li-Ning</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">3,500,000₫</div>
                    <div class="text-xs text-emerald-600">Đã bán: 35</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection