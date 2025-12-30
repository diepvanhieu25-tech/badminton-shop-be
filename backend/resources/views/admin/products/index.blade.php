@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-slate-800">Sản phẩm</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Thêm mới
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white shadow-sm rounded-lg overflow-hidden border border-slate-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b">
                <tr>
                    <th class="px-6 py-3 font-semibold">Sản phẩm</th>
                    <th class="px-6 py-3 font-semibold">Giá</th>
                    <th class="px-6 py-3 font-semibold">Trạng thái</th>
                    <th class="px-6 py-3 font-semibold text-right">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="bg-white border-b hover:bg-slate-50 transition">
                    <td class="px-6 py-4 flex items-center gap-4">
                        @if($product->thumbnail)
                            <img class="w-12 h-12 rounded object-cover border" src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}">
                        @else
                            <div class="w-12 h-12 rounded bg-slate-100 flex items-center justify-center text-xs text-slate-400">No Img</div>
                        @endif
                        <div>
                            <div class="font-medium text-slate-900">{{ $product->name }}</div>
                            <div class="text-xs text-slate-500">SKU: {{ $product->sku ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-900">
                        {{ number_format($product->price) }} đ
                        @if($product->original_price)
                            <br><span class="text-xs text-slate-400 line-through">{{ number_format($product->original_price) }} đ</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'draft' => 'bg-gray-100 text-gray-800',
                                'inactive' => 'bg-red-100 text-red-800',
                            ];
                            $color = $statusColors[$product->status->value] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="{{ $color }} text-xs font-medium px-2.5 py-0.5 rounded-full border border-opacity-20">
                            {{ ucfirst($product->status->value) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="font-medium text-blue-600 hover:underline">Sửa</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 hover:underline">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                        Chưa có sản phẩm nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t">
        {{ $products->links() }} 
        </div>
</div>
@endsection