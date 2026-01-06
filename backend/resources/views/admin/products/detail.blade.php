@extends('layouts.admin')

@section('title', 'Admin - Chi tiết sản phẩm')

@section('page_title')
    Chi tiết sản phẩm: {{ $product->name }}
@endsection

@section('content')

@php
    // ===== STATUS =====
    $statusValue = is_string($product->status)
        ? $product->status
        : ($product->status?->value ?? 'draft');

    $statusText = match ($statusValue) {
        'active' => 'Active',
        'inactive' => 'Inactive',
        default => 'Draft',
    };

    $statusTone = match ($statusValue) {
        'active' => 'success',
        'inactive' => 'danger',
        default => 'warning',
    };

    // ===== STOCK =====
    $stock = $product->variants->sum('stock_qty');

    // ===== IMAGES =====
    $images = $product->images;
    $mainImage = $images->first();
@endphp

<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">
        Thông tin chi tiết và hiệu suất bán hàng của sản phẩm
    </div>
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

<!-- GRID CHÍNH -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <!-- LEFT: IMAGE + INFO -->
    <div class="lg:col-span-2 space-y-6">

        <!-- IMAGE GALLERY -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            {{-- MAIN IMAGE --}}
            <div class="mb-4">
                @if($mainImage)
                    <img
                        src="{{ Storage::url($mainImage->image_url) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-[420px] object-contain bg-slate-50 rounded-xl border"
                        id="main-product-image"
                    />
                @else
                    <div class="h-[420px] bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 flex items-center justify-center">
                        <span class="text-6xl text-slate-400">📸</span>
                    </div>
                @endif
            </div>

            {{-- THUMBNAILS --}}
            @if($images->count() > 1)
                <div class="flex gap-3">
                    @foreach($images as $img)
                        <img
                            src="{{ Storage::url($img->image_url) }}"
                            class="w-24 h-24 object-cover rounded-lg border cursor-pointer hover:ring-2 hover:ring-emerald-500 transition"
                            onclick="document.getElementById('main-product-image').src=this.src"
                        />
                    @endforeach
                </div>
            @endif
        </div>

        <!-- BASIC INFO -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">
                {{ $product->name }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-slate-600">SKU:</span>
                    <span class="font-medium ml-2">{{ $product->sku }}</span>
                </div>

                <div>
                    <span class="text-slate-600">Danh mục:</span>
                    @if($product->category)
                        <a href="/admin/categories/{{ $product->category->id }}"
                           class="font-medium ml-2 text-emerald-600 hover:underline">
                            {{ $product->category->name }}
                        </a>
                    @else
                        <span class="italic ml-2 text-slate-400">—</span>
                    @endif
                </div>

                <div>
                    <span class="text-slate-600">Hãng:</span>
                    @if($product->brand)
                        <a href="/admin/brands/{{ $product->brand->id }}"
                           class="font-medium ml-2 text-emerald-600 hover:underline">
                            {{ $product->brand->name }}
                        </a>
                    @else
                        <span class="italic ml-2 text-slate-400">—</span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-slate-600">Trạng thái:</span>
                    <x-badge :text="$statusText" :tone="$statusTone" />
                </div>
            </div>
        </div>

        <!-- DESCRIPTION -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-lg font-semibold mb-3">Mô tả sản phẩm</h3>
            {!! $product->description
                ? nl2br(e($product->description))
                : '<p class="italic text-slate-500">Chưa có mô tả chi tiết.</p>'
            !!}
        </div>

    </div>

    <!-- RIGHT: PRICE + STOCK -->
    <div class="space-y-6">

        <!-- PRICE -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
            <div class="text-slate-500 text-sm mb-2">Giá bán hiện tại</div>
            <div class="text-4xl font-bold text-emerald-600">
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

        <!-- STOCK -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
            <div class="text-slate-500 text-sm mb-2">Tồn kho hiện tại</div>
            <div class="text-5xl font-bold text-slate-900">
                {{ $stock }}
            </div>
            <div class="text-sm mt-2">
                @if($stock === 0)
                    <span class="text-red-600 font-medium">Hết hàng</span>
                @elseif($stock < 10)
                    <span class="text-orange-600 font-medium">Sắp hết (cảnh báo)</span>
                @else
                    <span class="text-emerald-600">Còn hàng</span>
                @endif
            </div>
        </div>

        <!-- VARIANTS INFO -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="text-slate-500 text-sm mb-4 text-center">
                Các biến thể sản phẩm
            </div>

            @if($product->variants->count())
                <div class="flex flex-wrap gap-3 justify-center">
                    @foreach($product->variants as $variant)
                        @php
                            $attr = is_array($variant->attributes) ? $variant->attributes : [];
                            $size = $attr['size'] ?? null;
                            $color = $attr['color'] ?? null;
                        @endphp

                        <div class="px-4 py-2 rounded-lg border border-slate-300 text-sm bg-slate-50 flex items-center gap-2">
                            @if($size)
                                <span class="font-medium text-slate-700">
                                    Size: {{ $size }}
                                </span>
                            @endif

                            @if($size && $color)
                                <span class="text-slate-400">|</span>
                            @endif

                            @if($color)
                                <span class="font-medium text-slate-700">
                                    Màu: {{ $color }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-sm text-slate-400 italic">
                    Sản phẩm chưa có biến thể
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
