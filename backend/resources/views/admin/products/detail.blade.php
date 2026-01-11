@extends('layouts.admin')

@section('title', 'Chi tiết: ' . $product->name)

@section('content')

@php
    // Xử lý status gọn gàng
    $status = $product->status instanceof \BackedEnum ? $product->status->value : ($product->status ?? 'draft');
    $statusColors = [
        'active' => 'bg-green-100 text-green-800 border-green-200',
        'inactive' => 'bg-red-100 text-red-800 border-red-200',
        'draft' => 'bg-gray-100 text-gray-800 border-gray-200',
    ];
    $statusClass = $statusColors[$status] ?? $statusColors['draft'];
    
    // Tính tổng tồn kho
    $totalStock = $product->variants->isNotEmpty() 
        ? $product->variants->sum('stock_qty') 
        : 0; // Hoặc $product->stock nếu không dùng variants
        
    $mainImage = $product->thumbnail 
        ? Storage::url($product->thumbnail) 
        : ($product->images->first()?->image_url ? asset($product->images->first()->image_url) : null);
@endphp

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-slate-900">{{ $product->name }}</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClass }}">
                {{ ucfirst($status) }}
            </span>
        </div>
        <div class="flex items-center gap-4 mt-2 text-sm text-slate-500">
            <span>SKU: <span class="font-mono font-semibold text-slate-700">{{ $product->sku }}</span></span>
            <span>•</span>
            <span>Đã tạo: {{ $product->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.products.edit', $product) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Chỉnh sửa
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-lg text-sm font-medium transition">
            Quay lại
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- CỘT TRÁI: ẢNH & BIẾN THỂ --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- SECTION: GALLERY --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex flex-col md:flex-row gap-6">
                {{-- Ảnh chính --}}
                <div class="w-full md:w-2/3">
                    <div class="aspect-square w-full rounded-lg border border-slate-100 bg-slate-50 overflow-hidden flex items-center justify-center relative">
                        @if($mainImage)
                            <img id="main-display-image" src="{{ $mainImage }}" class="w-full h-full object-contain">
                        @else
                            <div class="text-slate-400 flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>No Image</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- List ảnh nhỏ --}}
                <div class="w-full md:w-1/3 grid grid-cols-4 md:grid-cols-2 gap-2 h-fit">
                    @if($product->thumbnail)
                        <div class="aspect-square rounded border cursor-pointer hover:ring-2 ring-blue-500 overflow-hidden" 
                             onclick="changeImage('{{ Storage::url($product->thumbnail) }}')">
                             <img src="{{ Storage::url($product->thumbnail) }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    @foreach($product->images as $img)
                        <div class="aspect-square rounded border cursor-pointer hover:ring-2 ring-blue-500 overflow-hidden"
                             onclick="changeImage('{{ Storage::url($img->image_url) }}')">
                            <img src="{{ Storage::url($img->image_url) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- SECTION: VARIANTS TABLE (Quan trọng) --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Danh sách biến thể</h3>
                <span class="text-xs font-medium px-2 py-1 bg-slate-100 rounded-full text-slate-600">
                    Tổng tồn kho: {{ $totalStock }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-600 font-medium border-b">
                        <tr>
                            <th class="px-6 py-3">SKU Variant</th>
                            <th class="px-6 py-3">Kích thước / Màu sắc</th>
                            <th class="px-6 py-3 text-right">Giá bán</th>
                            <th class="px-6 py-3 text-center">Tồn kho</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($product->variants as $variant)
                        @php
                            $attr = $variant->attributes ?? [];
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-mono text-slate-600">{{ $variant->sku }}</td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2">
                                    @if(isset($attr['size'])) 
                                        <span class="px-2 py-1 bg-slate-100 rounded text-xs">Size: <b>{{ $attr['size'] }}</b></span>
                                    @endif
                                    @if(isset($attr['color']))
                                        <span class="px-2 py-1 bg-white border border-slate-200 rounded text-xs">Màu: <b>{{ $attr['color'] }}</b></span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right font-medium text-slate-900">
                                {{ number_format($variant->price) }} ₫
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if($variant->stock_qty <= $variant->stock_alert)
                                    <span class="text-red-600 font-bold">{{ $variant->stock_qty }}</span>
                                @else
                                    <span class="text-slate-700">{{ $variant->stock_qty }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-slate-500 italic">
                                Sản phẩm này không có biến thể (Sản phẩm đơn).
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SECTION: DESCRIPTION --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 mb-4">Mô tả chi tiết</h3>
            <div class="prose prose-sm max-w-none text-slate-600">
                {!! $product->description ? nl2br(e($product->description)) : '<em class="text-slate-400">Chưa có mô tả</em>' !!}
            </div>
        </div>
    </div>

    {{-- CỘT PHẢI: THÔNG TIN CHUNG --}}
    <div class="space-y-6">
        
        {{-- BOX GIÁ & INFO --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-4 pb-2 border-b">Thông tin chung</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 text-sm">Giá niêm yết</span>
                    <span class="text-lg font-bold text-blue-600">{{ number_format($product->price) }} ₫</span>
                </div>
                @if($product->original_price)
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 text-sm">Giá gốc</span>
                    <span class="text-sm text-slate-400 line-through">{{ number_format($product->original_price) }} ₫</span>
                </div>
                @endif
                
                <div class="border-t border-dashed my-2"></div>

                <div class="flex justify-between">
                    <span class="text-slate-500 text-sm">Danh mục</span>
                    <span class="font-medium text-slate-700">{{ $product->category->name ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 text-sm">Thương hiệu</span>
                    <span class="font-medium text-slate-700">{{ $product->brand->name ?? '—' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function changeImage(src) {
        document.getElementById('main-display-image').src = src;
    }
</script>

@endsection