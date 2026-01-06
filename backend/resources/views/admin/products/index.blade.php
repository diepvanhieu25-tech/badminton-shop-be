@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')

{{-- HEADER --}}
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-slate-800">Sản phẩm</h1>

    <a href="{{ route('admin.products.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                  d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                  clip-rule="evenodd" />
        </svg>
        Thêm mới
    </a>
</div>

{{-- FLASH MESSAGE --}}
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="GET"
      class="bg-white rounded-lg border border-slate-200 p-4 mb-5
             flex flex-wrap gap-3 items-center">

    {{-- SEARCH --}}
    <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Tìm tên sản phẩm, SKU..."
        class="px-4 py-2.5 rounded-lg border border-slate-300
               focus:border-blue-500 focus:ring-2 focus:ring-blue-200
               transition w-64"
    />

    {{-- STATUS --}}
    <select name="status"
            class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white"
            onchange="this.form.submit()">
        <option value="">Tất cả trạng thái</option>
        <option value="active" @selected(request('status')==='active')>Active</option>
        <option value="draft" @selected(request('status')==='draft')>Draft</option>
        <option value="inactive" @selected(request('status')==='inactive')>Inactive</option>
    </select>

    {{-- CATEGORY --}}
    <select name="category_id"
            class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white"
            onchange="this.form.submit()">
        <option value="">Tất cả danh mục</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                @selected(request('category_id') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    {{-- RESET --}}
    @if(request()->hasAny(['q','status','category_id']))
        <a href="{{ route('admin.products.index') }}"
           class="px-4 py-2.5 rounded-lg border border-slate-300
                  bg-white text-slate-600 hover:bg-slate-100 transition">
            Reset
        </a>
    @endif
</form>

{{-- TABLE --}}
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
                @php
                    $thumb = $product->thumbnail
                        ? Storage::url($product->thumbnail)
                        : ($product->images->first()
                            ? asset($product->images->first()->image_url)
                            : null);

                    $displayPrice = $product->variants->count()
                        ? $product->variants->min('price')
                        : $product->price;

                    $status = $product->status instanceof \BackedEnum
                        ? $product->status->value
                        : ($product->status ?? 'draft');

                    $statusColors = [
                        'active'   => 'bg-green-100 text-green-800',
                        'draft'    => 'bg-gray-100 text-gray-800',
                        'inactive' => 'bg-red-100 text-red-800',
                    ];
                @endphp

                <tr class="border-b hover:bg-slate-50 transition">
                    {{-- PRODUCT --}}
                    <td class="px-6 py-4 flex items-center gap-4">
                        @if($thumb)
                            <img src="{{ $thumb }}" class="w-12 h-12 rounded object-cover border">
                        @else
                            <div class="w-12 h-12 rounded bg-slate-100 flex items-center justify-center text-xs text-slate-400">
                                No Img
                            </div>
                        @endif

                        <div>
                            <div class="font-medium text-slate-900">
                                <a href="{{ route('admin.products.detail', $product) }}" class="hover:underline">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <div class="text-xs text-slate-500">
                                SKU: {{ $product->sku ?? '—' }}
                            </div>
                        </div>
                    </td>

                    {{-- PRICE --}}
                    <td class="px-6 py-4 font-medium text-slate-900">
                        {{ number_format($displayPrice) }} đ
                        @if($product->original_price && $product->original_price > $displayPrice)
                            <div class="text-xs text-slate-400 line-through">
                                {{ number_format($product->original_price) }} đ
                            </div>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4">
                        <span class="{{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}
                                     text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ ucfirst($status) }}
                        </span>
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="text-blue-600 hover:underline">
                            Sửa
                        </a>

                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Xóa
                            </button>
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
        {{ $products->withQueryString()->links() }}
    </div>
</div>

@endsection
