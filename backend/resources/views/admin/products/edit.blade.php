@extends('layouts.admin')

@section('title', 'Cập nhật sản phẩm')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-slate-800">Cập nhật: {{ $product->name }}</h1>
    <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">Quay lại danh sách</a>
</div>

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT') <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6 border border-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Thông tin cơ bản</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tên sản phẩm</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Giá bán</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Giá gốc</label>
                        <input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mô tả chi tiết</label>
                    <textarea name="description" rows="5" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6 border border-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Tổ chức</h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Trạng thái</label>
                    <select name="status" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ $product->status === $status ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Danh mục</label>
                    <select name="category_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                 <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Thương hiệu</label>
                    <select name="brand_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                        <option value="">-- Chọn thương hiệu --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 border border-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Hình ảnh</h3>
                @if($product->thumbnail)
                    <div class="mb-3">
                        <p class="text-xs text-slate-500 mb-2">Ảnh hiện tại:</p>
                        <img src="{{ Storage::url($product->thumbnail) }}" class="w-full h-auto rounded border">
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Thay ảnh mới</label>
                    <input type="file" name="thumbnail" class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                    "/>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-6 border border-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Tùy chọn</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input type="hidden" name="is_featured" value="0">
                            <input id="is_featured" name="is_featured" value="1" type="checkbox" {{ $product->is_featured ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_featured" class="font-medium text-slate-700">Sản phẩm nổi bật</label>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input type="hidden" name="has_variants" value="0">
                            <input id="has_variants" name="has_variants" value="1" type="checkbox" {{ $product->has_variants ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="has_variants" class="font-medium text-slate-700">Có biến thể</label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow transition">
                Cập nhật sản phẩm
            </button>
        </div>
    </div>
</form>
@endsection