@extends('layouts.admin')

@section('title', 'Admin - Chi tiết sản phẩm')
@section('page_title', 'Chi tiết sản phẩm: {{ $product->name }}')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Thông tin chi tiết và hiệu suất bán hàng của sản phẩm</div>
    <div class="flex gap-3">
        <a href="/admin/products/{{ $product->id }}/edit" 
           class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-md">
            Sửa sản phẩm
        </a>
        <a href="/admin/products" 
           class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            ← Quay lại danh sách
        </a>
    </div>
</div>

<!-- Thông tin chính sản phẩm -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card hình ảnh & thông tin cơ bản -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Gallery ảnh sản phẩm -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="grid grid-cols-3 gap-3 mb-4">
                @foreach($product->images as $image)
                <img src="{{ asset('storage/' . $image->path) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-32 object-cover rounded-lg border border-slate-200 shadow-sm" />
                @endforeach
                @if($product->images->count() == 0)
                <div class="col-span-3 h-64 bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 flex items-center justify-center">
                    <span class="text-5xl text-slate-400">📸</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Thông tin cơ bản -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">{{ $product->name }}</h2>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Mã SKU:</span>
                    <span class="font-medium">{{ $product->sku }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Danh mục:</span>
                    <a href="/admin/categories/{{ $product->category->id }}" class="font-medium text-emerald-600 hover:underline">
                        {{ $product->category->name }}
                    </a>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Hãng:</span>
                    <a href="/admin/brands/{{ $product->brand->id }}" class="font-medium text-emerald-600 hover:underline">
                        {{ $product->brand->name }}
                    </a>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Trạng thái:</span>
                    <x-badge text="{{ $product->status == 'active' ? 'Active' : ($product->status == 'inactive' ? 'Inactive' : 'Draft') }}" 
                             tone="{{ $product->status == 'active' ? 'success' : ($product->status == 'inactive' ? 'danger' : 'warning') }}" />
                </div>
            </div>
        </div>
    </div>

    <!-- Giá cả & Tồn kho & Thống kê bán hàng -->
    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Giá bán -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
            <div class="text-slate-500 text-sm mb-2">Giá bán hiện tại</div>
            <div class="text-3xl font-bold text-emerald-600">
                {{ number_format($product->price) }}₫
            </div>
            @if($product->original_price > $product->price)
            <div class="text-sm text-slate-500 line-through mt-1">
                {{ number_format($product->original_price) }}₫
            </div>
            <div class="text-sm text-red-600 font-medium">
                -{{ round(100 - ($product->price / $product->original_price * 100)) }}%
            </div>
            @endif
        </div>

        <!-- Tồn kho -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
            <div class="text-slate-500 text-sm mb-2">Tồn kho hiện tại</div>
            <div class="text-4xl font-bold text-slate-900">{{ $product->stock }}</div>
            <div class="text-sm mt-2">
                @if($product->stock == 0)
                    <span class="text-red-600 font-medium">Hết hàng</span>
                @elseif($product->stock < 10)
                    <span class="text-orange-600 font-medium">Sắp hết (cảnh báo)</span>
                @else
                    <span class="text-emerald-600">Còn hàng</span>
                @endif
            </div>
        </div>

        <!-- Đã bán -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
            <div class="text-slate-500 text-sm mb-2">Đã bán (tháng này)</div>
            <div class="text-4xl font-bold text-slate-900">{{ $product->sold_this_month ?? 0 }}</div>
            <div class="text-sm text-emerald-600 mt-2">+{{ $product->sold_growth ?? 0 }}% so với tháng trước</div>
        </div>
    </div>
</div>

<!-- Mô tả sản phẩm & Thông số kỹ thuật -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Mô tả ngắn & chi tiết -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Mô tả sản phẩm</h3>
        <div class="space-y-4">
            @if($product->short_description)
            <div>
                <p class="font-medium text-slate-700">Mô tả ngắn:</p>
                <p class="text-slate-600 mt-1">{{ $product->short_description }}</p>
            </div>
            @endif

            <div>
                <p class="font-medium text-slate-700">Mô tả chi tiết:</p>
                <div class="text-slate-600 mt-2 prose max-w-none">
                    {!! $product->description ? nl2br(e($product->description)) : '<p class="italic text-slate-500">Chưa có mô tả chi tiết.</p>' !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây chứa sản phẩm này -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Đơn hàng gần đây có sản phẩm này</h3>
            <a href="/admin/orders?product={{ $product->id }}" class="text-sm text-emerald-600 hover:underline">
                Xem tất cả →
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251226-001</div>
                    <div class="text-sm text-slate-600">Khách: Nguyễn Văn A • 26/12/2025</div>
                </div>
                <div class="text-right">
                    <x-badge text="Hoàn thành" tone="success" />
                </div>
            </div>

            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251220-015</div>
                    <div class="text-sm text-slate-600">Khách: Trần Thị B • 20/12/2025</div>
                </div>
                <div class="text-right">
                    <x-badge text="Đang giao" tone="primary" />
                </div>
            </div>

            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251215-008</div>
                    <div class="text-sm text-slate-600">Khách: Lê Hoàng C • 15/12/2025</div>
                </div>
                <div class="text-right">
                    <x-badge text="Hoàn thành" tone="success" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection