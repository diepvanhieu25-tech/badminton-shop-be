@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')

    {{-- HEADER & ACTIONS --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Sản phẩm</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý danh sách sản phẩm và kho hàng.</p>
        </div>

        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm focus:ring-4 focus:ring-blue-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Thêm sản phẩm</span>
        </a>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50" role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="font-medium">Thành công!</span>&nbsp;{{ session('success') }}
        </div>
    @endif

    {{-- FILTERS --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- SEARCH --}}
            <div class="relative col-span-1 lg:col-span-2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}"
                       class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="Tìm kiếm theo tên, SKU...">
            </div>

            {{-- STATUS --}}
            <div>
                <select name="status" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-pointer">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" @selected(request('status')==='active')>Đang bán (Active)</option>
                    <option value="draft" @selected(request('status')==='draft')>Nháp (Draft)</option>
                    <option value="inactive" @selected(request('status')==='inactive')>Ngừng bán (Inactive)</option>
                </select>
            </div>

            {{-- CATEGORY --}}
            <div class="flex gap-2">
                <select name="category_id" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-pointer">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                {{-- RESET BUTTON (Only show if filtering) --}}
                @if(request()->hasAny(['q','status','category_id']))
                    <a href="{{ route('admin.products.index') }}"
                       class="flex items-center justify-center px-3 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 focus:z-10 focus:ring-2 focus:ring-gray-200 transition"
                       title="Xóa bộ lọc">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-xl bg-white border border-gray-100">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
            <tr>
                <th scope="col" class="px-6 py-4 font-semibold">Thông tin sản phẩm</th>
                <th scope="col" class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                <th scope="col" class="px-6 py-4 font-semibold text-right">Giá bán</th>
                <th scope="col" class="px-6 py-4 font-semibold text-right">Hành động</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                @php
                    // Logic xử lý ảnh (Nên đưa vào Model Accessor: getThumbnailUrlAttribute)
                    $thumb = $product->thumbnail ? Storage::url($product->thumbnail) : ($product->images->first()?->image_url);
                    
                    // Logic xử lý giá
                    $displayPrice = $product->variants->isNotEmpty() ? $product->variants->min('price') : $product->price;
                    
                    // Logic xử lý trạng thái
                    $statusValue = $product->status instanceof \BackedEnum ? $product->status->value : ($product->status ?? 'draft');
                @endphp

                <tr class="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150">
                    {{-- PRODUCT INFO --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0 w-12 h-12 relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                @if($thumb)
                                    <img class="w-full h-full object-cover" src="{{ $thumb }}" alt="{{ $product->name }}">
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="max-w-xs">
                                <a href="{{ route('admin.products.detail', $product) }}" class="text-base font-semibold text-gray-900 hover:text-blue-600 transition-colors line-clamp-1">
                                    {{ $product->name }}
                                </a>
                                <div class="text-xs text-gray-500 mt-0.5 font-mono">
                                    SKU: {{ $product->sku ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4 text-center">
                        <span @class([
                            'px-2.5 py-0.5 rounded-full text-xs font-medium border',
                            'bg-green-100 text-green-800 border-green-200' => $statusValue === 'active',
                            'bg-gray-100 text-gray-800 border-gray-200' => $statusValue === 'draft',
                            'bg-red-100 text-red-800 border-red-200' => $statusValue === 'inactive',
                        ])>
                            {{ ucfirst($statusValue) }}
                        </span>
                    </td>

                    {{-- PRICE --}}
                    <td class="px-6 py-4 text-right">
                        <div class="flex flex-col items-end">
                            <span class="text-sm font-bold text-gray-900">{{ number_format($displayPrice) }} đ</span>
                            @if($product->original_price && $product->original_price > $displayPrice)
                                <span class="text-xs text-gray-400 line-through">{{ number_format($product->original_price) }} đ</span>
                            @endif
                        </div>
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-1.5 rounded-md transition" title="Sửa">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>

                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-800 hover:bg-red-50 p-1.5 rounded-md transition" title="Xóa">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-base font-medium">Chưa có sản phẩm nào</p>
                            <p class="text-sm mt-1">Hãy thử tìm kiếm từ khóa khác hoặc thêm sản phẩm mới.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>

@endsection