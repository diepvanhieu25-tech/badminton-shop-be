@extends('layouts.admin')

@section('title', 'Admin - Chi tiết hãng')
@section('page_title', 'Chi tiết hãng: Yonex')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Thông tin chi tiết và thống kê của hãng</div>
    <div class="flex gap-3">
        <a href="/admin/brands/{{ $brand->id }}/edit" 
           class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-md">
            Sửa thông tin hãng
        </a>
        <a href="/admin/brands" 
           class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            ← Quay lại danh sách
        </a>
    </div>
</div>

<!-- Thông tin chính hãng -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card thông tin hãng -->
    <div class="lg:col-span-1 bg-white rounded-xl border border-slate-200 p-6">
        <div class="text-center">
            <img src="{{ asset('storage/' . $brand->logo) }}" 
                 alt="{{ $brand->name }}" 
                 class="w-48 h-48 mx-auto object-contain rounded-xl border border-slate-200 shadow-md mb-6" />

            <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ $brand->name }}</h2>
            <p class="text-sm text-slate-500 mb-4">{{ $brand->slug }}</p>

            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Quốc gia:</span>
                    <span class="font-medium">Nhật Bản 🇯🇵</span>
                </div>
                @if($brand->website)
                <div>
                    <a href="{{ $brand->website }}" target="_blank" 
                       class="text-emerald-600 hover:underline flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Website chính thức
                    </a>
                </div>
                @endif
                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Trạng thái:</span>
                    <x-badge text="{{ $brand->status == 'active' ? 'Active' : 'Inactive' }}" 
                             tone="{{ $brand->status == 'active' ? 'success' : 'danger' }}" />
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê nhanh -->
    <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-slate-900">68</div>
            <div class="text-sm text-slate-500 mt-1">Sản phẩm</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">42,800,000₫</div>
            <div class="text-sm text-slate-500 mt-1">Doanh thu tháng này</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-slate-900">248</div>
            <div class="text-sm text-slate-500 mt-1">Số lượng đã bán</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">43.5%</div>
            <div class="text-sm text-slate-500 mt-1">Tỷ trọng doanh thu</div>
        </div>
    </div>
</div>

<!-- Mô tả hãng & Sản phẩm nổi bật -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Mô tả hãng -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Giới thiệu về hãng</h3>
        <div class="text-slate-700 leading-relaxed">
            {!! nl2br(e($brand->description)) !!}
        </div>
        @if(empty($brand->description))
            <p class="text-slate-500 italic">Chưa có mô tả về hãng này.</p>
        @endif
    </div>

    <!-- Sản phẩm nổi bật của hãng -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Sản phẩm nổi bật</h3>
            <a href="/admin/products?brand={{ $brand->id }}" class="text-sm text-emerald-600 hover:underline">
                Xem tất cả (68) →
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            <div class="p-4 flex items-center gap-4 hover:bg-slate-50">
                <img src="https://via.placeholder.com/80x80" alt="Sản phẩm" class="w-16 h-16 object-cover rounded-lg border" />
                <div class="flex-1">
                    <div class="font-medium">Yonex Astrox 99 Pro</div>
                    <div class="text-sm text-slate-500">Vợt cầu lông tấn công</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">4,000,000₫</div>
                    <div class="text-xs text-emerald-600">Đã bán: 68</div>
                </div>
            </div>

            <div class="p-4 flex items-center gap-4 hover:bg-slate-50">
                <img src="https://via.placeholder.com/80x80" alt="Sản phẩm" class="w-16 h-16 object-cover rounded-lg border" />
                <div class="flex-1">
                    <div class="font-medium">Yonex Power Cushion 65Z</div>
                    <div class="text-sm text-slate-500">Giày cầu lông</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">3,600,000₫</div>
                    <div class="text-xs text-emerald-600">Đã bán: 52</div>
                </div>
            </div>

            <div class="p-4 flex items-center gap-4 hover:bg-slate-50">
                <img src="https://via.placeholder.com/80x80" alt="Sản phẩm" class="w-16 h-16 object-cover rounded-lg border" />
                <div class="flex-1">
                    <div class="font-medium">Yonex Nanoray 10F</div>
                    <div class="text-sm text-slate-500">Vợt phòng thủ</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">2,200,000₫</div>
                    <div class="text-xs text-emerald-600">Đã bán: 38</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection