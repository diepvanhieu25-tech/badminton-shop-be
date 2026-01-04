@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
            <a href="{{ route('admin.products.index') }}" class="hover:text-emerald-600">Sản phẩm</a>
            <span>/</span>
            <span>Chi tiết</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">{{ $product->name }}</h1>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-slate-300 bg-white rounded-lg text-slate-700 hover:bg-slate-50 text-sm font-medium transition">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
        <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium transition shadow-sm">
            <i class="fa-regular fa-pen-to-square"></i> Chỉnh sửa
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- CỘT TRÁI: THÔNG TIN CHÍNH --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Card 1: Tổng quan --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700">
                Thông tin chung
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-slate-500 font-semibold uppercase">Danh mục</label>
                    <div class="mt-1 text-slate-900 font-medium">{{ $product->category->name ?? '---' }}</div>
                </div>
                <div>
                    <label class="text-xs text-slate-500 font-semibold uppercase">Thương hiệu</label>
                    <div class="mt-1 text-slate-900 font-medium">{{ $product->brand->name ?? '---' }}</div>
                </div>
                <div>
                    <label class="text-xs text-slate-500 font-semibold uppercase">SKU (Mã gốc)</label>
                    <div class="mt-1 text-slate-900 font-family-mono">{{ $product->sku ?? 'N/A' }}</div>
                </div>
                <div>
                    <label class="text-xs text-slate-500 font-semibold uppercase">Giá bán gốc</label>
                    <div class="mt-1 text-emerald-600 font-bold text-lg">{{ number_format($product->price) }}₫</div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-xs text-slate-500 font-semibold uppercase">Mô tả</label>
                    <div class="mt-2 text-slate-700 text-sm leading-relaxed bg-slate-50 p-4 rounded-lg border border-slate-100">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Danh sách biến thể (NẾU CÓ) --}}
        @if($product->has_variants)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <span class="font-semibold text-slate-700">Danh sách phiên bản ({{ $product->variants->count() }})</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">SKU</th>
                            <th class="px-5 py-3">Size</th>
                            <th class="px-5 py-3">Màu</th>
                            <th class="px-5 py-3">Giá</th>
                            <th class="px-5 py-3 text-center">Tồn kho</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($product->variants as $variant)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 font-mono text-xs text-slate-600">{{ $variant->sku }}</td>
                            <td class="px-5 py-3 font-medium">{{ $variant->attributes['size'] ?? '-' }}</td>
                            <td class="px-5 py-3">{{ $variant->attributes['color'] ?? '-' }}</td>
                            <td class="px-5 py-3 text-emerald-600 font-medium">{{ number_format($variant->price) }}₫</td>
                            <td class="px-5 py-3 text-center">
                                @if($variant->stock_qty > 0)
                                    <span class="text-slate-700 font-bold">{{ $variant->stock_qty }}</span>
                                @else
                                    <span class="text-rose-500 text-xs font-bold bg-rose-50 px-2 py-1 rounded">Hết hàng</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    {{-- CỘT PHẢI: ẢNH & TRẠNG THÁI --}}
    <div class="space-y-6">
        
        {{-- Card 3: Trạng thái --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase mb-4">Trạng thái hiển thị</h3>
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-slate-600">Tình trạng:</span>
                @if($product->status->value == 'active')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                        <i class="fa-solid fa-check"></i> Công khai
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                        <i class="fa-solid fa-eye-slash"></i> Ẩn/Nháp
                    </span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-slate-600">Nổi bật:</span>
                @if($product->is_featured)
                    <span class="text-amber-500 font-bold text-xs"><i class="fa-solid fa-star"></i> Có</span>
                @else
                    <span class="text-slate-400 text-xs">Không</span>
                @endif
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-400 text-center">
                Ngày tạo: {{ $product->created_at->format('d/m/Y H:i') }}
            </div>
        </div>

        {{-- Card 4: Ảnh đại diện --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-3 border-b border-slate-100 font-semibold text-sm text-slate-700">Ảnh đại diện</div>
            <div class="p-4 flex justify-center bg-slate-50">
                @if($product->thumbnail)
                    <img src="{{ Storage::url($product->thumbnail) }}" class="max-h-64 rounded-lg shadow-sm">
                @else
                    <div class="h-40 w-full flex items-center justify-center text-slate-400 bg-slate-100 rounded">No Image</div>
                @endif
            </div>
        </div>

        {{-- Card 5: Gallery --}}
        @if($product->images->count() > 0)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-3 border-b border-slate-100 font-semibold text-sm text-slate-700 flex justify-between">
                <span>Bộ sưu tập ảnh</span>
                <span class="text-xs bg-slate-100 px-2 py-0.5 rounded-full text-slate-500">{{ $product->images->count() }} ảnh</span>
            </div>
            <div class="p-4 grid grid-cols-3 gap-2">
                @foreach($product->images as $img)
                    <a href="{{ Storage::url($img->image_url) }}" target="_blank" class="block aspect-square rounded-lg overflow-hidden border border-slate-200 hover:opacity-90 transition">
                        <img src="{{ Storage::url($img->image_url) }}" class="w-full h-full object-cover">
                    </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection