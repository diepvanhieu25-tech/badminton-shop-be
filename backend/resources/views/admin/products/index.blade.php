@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Danh sách sản phẩm</h1>
        <p class="text-sm text-slate-500">Quản lý kho hàng và giá cả.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-medium shadow-md shadow-emerald-100">
        <i class="fa-solid fa-plus"></i> Thêm sản phẩm
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    {{-- FILTER FORM --}}
    <div class="p-5 border-b border-slate-200 bg-slate-50">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold text-slate-500 mb-1 block">Tìm kiếm</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên sản phẩm, SKU..." 
                        class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none text-sm">
                </div>
            </div>
            
            <div class="w-48">
                <label class="text-xs font-semibold text-slate-500 mb-1 block">Danh mục</label>
                <select name="category_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none text-sm focus:ring-2 focus:ring-emerald-200">
                    <option value="">Tất cả</option>
                    {{-- Giả sử Controller truyền $categories sang --}}
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 text-sm font-medium transition">
                <i class="fa-solid fa-filter"></i> Lọc
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 text-sm font-medium transition">
                <i class="fa-solid fa-rotate-right"></i> Reset
            </a>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-xs tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 w-16">ID</th>
                    <th class="px-6 py-4">Sản phẩm</th>
                    <th class="px-6 py-4">Giá bán</th>
                    <th class="px-6 py-4 text-center">Tồn kho</th>
                    <th class="px-6 py-4">Trạng thái</th>
                    <th class="px-6 py-4 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50 transition duration-150">
                    <td class="px-6 py-4 text-slate-500">#{{ $product->id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-start gap-3">
                            {{-- Ảnh đại diện --}}
                            <div class="shrink-0 h-12 w-12 rounded-lg border border-slate-200 overflow-hidden bg-white">
                                @if($product->thumbnail)
                                    <img src="{{ Storage::url($product->thumbnail) }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-slate-50 text-slate-400">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Tên & SKU --}}
                            <div>
                                <a href="{{ route('admin.products.show', $product) }}" class="font-semibold text-slate-900 hover:text-emerald-600 line-clamp-1">
                                    {{ $product->name }}
                                </a>
                                <div class="text-xs text-slate-500 mt-0.5">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-400">{{ $product->category->name ?? '---' }}</div>
                            </div>
                        </div>
                    </td>
                    
                    {{-- Giá bán (Xử lý khoảng giá nếu có biến thể) --}}
                    <td class="px-6 py-4 font-medium text-slate-900">
                        @if($product->has_variants && $product->variants_count > 0)
                            <span class="text-xs text-slate-500">Từ</span> 
                            {{ number_format($product->variants->min('price')) }}₫
                        @else
                            {{ number_format($product->price) }}₫
                            @if($product->original_price > $product->price)
                                <div class="text-xs text-slate-400 line-through">{{ number_format($product->original_price) }}₫</div>
                            @endif
                        @endif
                    </td>

                    {{-- Tồn kho (Tổng các biến thể) --}}
                    <td class="px-6 py-4 text-center">
                        @php
                            $stock = $product->has_variants ? $product->variants->sum('stock_qty') : 'N/A';
                        @endphp
                        @if(is_numeric($stock) && $stock == 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-700">Hết hàng</span>
                        @else
                            <span class="text-slate-600 font-semibold">{{ $stock }}</span>
                        @endif
                    </td>

                    {{-- Trạng thái --}}
                    <td class="px-6 py-4">
                        @php
                            $statusMap = [
                                'active' => ['color' => 'bg-emerald-100 text-emerald-700', 'icon' => 'fa-circle-check', 'label' => 'Công khai'],
                                'draft' => ['color' => 'bg-slate-100 text-slate-700', 'icon' => 'fa-file-pen', 'label' => 'Bản nháp'],
                                'inactive' => ['color' => 'bg-rose-100 text-rose-700', 'icon' => 'fa-circle-xmark', 'label' => 'Ngừng bán'],
                            ];
                            $st = $statusMap[$product->status->value] ?? $statusMap['draft'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border border-transparent {{ $st['color'] }}">
                            <i class="fa-solid {{ $st['icon'] }}"></i> {{ $st['label'] }}
                        </span>
                    </td>

                    {{-- Hành động --}}
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-emerald-600 hover:border-emerald-200 transition" title="Sửa">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này? hành động này không thể hoàn tác!')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-rose-600 hover:border-rose-200 transition" title="Xóa">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-3"></i>
                            <p>Chưa có sản phẩm nào được tìm thấy.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- PAGINATION --}}
    <div class="p-4 border-t border-slate-200 bg-slate-50">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection