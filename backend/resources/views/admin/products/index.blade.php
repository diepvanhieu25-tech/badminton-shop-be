@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Danh sách sản phẩm</h1>
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium shadow-sm">
        + Thêm sản phẩm
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-wrap gap-3">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap gap-3 w-full">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc SKU..." 
                class="flex-1 min-w-[200px] px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
            
            <select name="category_id" class="px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-emerald-200">
                <option value="">-- Danh mục --</option>
                {{-- @foreach($categories as $cat) --}}
                    {{-- <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option> --}}
                {{-- @endforeach --}}
                {{-- Demo option --}}
                <option value="1">Vợt cầu lông</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900">Lọc</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-100 text-slate-600 uppercase font-semibold text-xs">
                <tr>
                    <th class="px-6 py-4">Sản phẩm</th>
                    <th class="px-6 py-4">Giá bán</th>
                    <th class="px-6 py-4">Kho</th>
                    <th class="px-6 py-4">Trạng thái</th>
                    <th class="px-6 py-4 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @if($product->thumbnail)
                                <img src="{{ Storage::url($product->thumbnail) }}" class="w-12 h-12 rounded object-cover border border-slate-200">
                            @else
                                <div class="w-12 h-12 rounded bg-slate-100 flex items-center justify-center text-xs text-slate-400">No img</div>
                            @endif
                            <div>
                                <div class="font-medium text-slate-900">{{ $product->name }}</div>
                                <div class="text-xs text-slate-500">SKU: {{ $product->sku ?? '---' }}</div>
                                <div class="text-xs text-slate-500">{{ $product->category->name ?? 'Chưa phân loại' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">
                        @if($product->has_variants)
                            <span class="text-slate-600 text-xs">Từ</span> {{ number_format($product->variants->min('price')) }}₫
                        @else
                            {{ number_format($product->price) }}₫
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->has_variants)
                            {{ $product->variants->sum('stock_qty') }}
                        @else
                            <span class="text-slate-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'active' => 'bg-emerald-100 text-emerald-700',
                                'draft' => 'bg-slate-100 text-slate-700',
                                'inactive' => 'bg-rose-100 text-rose-700',
                            ];
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$product->status->value] ?? '' }}">
                            {{ $product->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded transition">
                            ✏️
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded transition">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">Chưa có sản phẩm nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-slate-200">
        {{ $products->links() }}
    </div>
</div>
@endsection