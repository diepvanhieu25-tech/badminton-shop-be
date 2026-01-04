@extends('layouts.admin')

@section('title', 'Cập nhật sản phẩm')

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Cập nhật: {{ $product->name }}</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left"></i> Hủy
            </a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium shadow-md transition">
                <i class="fa-solid fa-check"></i> Cập nhật
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- CỘT TRÁI --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Thông tin cơ bản (Giống Create, value có old và $product) --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Thông tin chung</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tên sản phẩm</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none">
                    </div>
                    {{-- Category & Brand (Bạn tự điền tương tự Create) --}}
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Danh mục</label>
                            <select name="category_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Thương hiệu</label>
                            <select name="brand_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                     <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- LOGIC BIẾN THỂ (PHỨC TẠP) --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Dữ liệu sản phẩm</h3>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="has_variants" id="hasVariantsCheck" value="1" 
                               {{ old('has_variants', $product->has_variants) ? 'checked' : '' }} 
                               class="w-4 h-4 text-emerald-600 rounded border-slate-300">
                        <span class="text-sm font-medium text-slate-700">Có biến thể</span>
                    </label>
                </div>

                {{-- Simple Product --}}
                <div id="simpleProductConfig" class="{{ old('has_variants', $product->has_variants) ? 'hidden' : 'block' }} space-y-4 border-t pt-4">
                     <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Giá bán</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>
                </div>

                {{-- Variant Product --}}
                <div id="variantProductConfig" class="{{ old('has_variants', $product->has_variants) ? 'block' : 'hidden' }} space-y-4 border-t pt-4">
                    <div id="variantsContainer" class="space-y-3">
                        @foreach($product->variants as $index => $variant)
                            <div class="variant-item grid grid-cols-12 gap-3 items-start p-4 bg-slate-50 rounded-xl border border-slate-200 relative">
                                {{-- Input Hidden ID để Update --}}
                                <input type="hidden" name="variants[{{$index}}][id]" value="{{ $variant->id }}">
                                
                                <div class="col-span-12 md:col-span-3">
                                    <label class="text-xs font-medium text-slate-500">Size</label>
                                    <input type="text" name="variants[{{$index}}][attributes][size]" value="{{ $variant->attributes['size'] ?? '' }}" class="w-full text-sm px-3 py-2 border rounded-lg">
                                </div>
                                <div class="col-span-12 md:col-span-3">
                                    <label class="text-xs font-medium text-slate-500">Màu</label>
                                    <input type="text" name="variants[{{$index}}][attributes][color]" value="{{ $variant->attributes['color'] ?? '' }}" class="w-full text-sm px-3 py-2 border rounded-lg">
                                </div>
                                <div class="col-span-12 md:col-span-3">
                                    <label class="text-xs font-medium text-slate-500">Giá</label>
                                    <input type="number" name="variants[{{$index}}][price]" value="{{ $variant->price }}" class="w-full text-sm px-3 py-2 border rounded-lg">
                                </div>
                                <div class="col-span-12 md:col-span-3">
                                    <label class="text-xs font-medium text-slate-500">Kho</label>
                                    <input type="number" name="variants[{{$index}}][stock_qty]" value="{{ $variant->stock_qty }}" class="w-full text-sm px-3 py-2 border rounded-lg">
                                </div>
                                <div class="col-span-12 mt-2">
                                    <label class="text-xs font-medium text-slate-500">SKU</label>
                                    <input type="text" name="variants[{{$index}}][sku]" value="{{ $variant->sku }}" class="w-full text-sm px-3 py-2 border rounded-lg">
                                </div>
                                
                                {{-- Nút xóa: Logic JS sẽ ẩn item này đi và có thể gửi tín hiệu xóa lên server nếu cần thiết --}}
                                <button type="button" class="absolute top-2 right-2 text-slate-400 hover:text-rose-500 remove-variant">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="addVariantBtn" class="w-full py-2 border-2 border-dashed border-emerald-300 text-emerald-600 rounded-lg hover:bg-emerald-50">+ Thêm phiên bản</button>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI --}}
        <div class="space-y-6">
            {{-- Trạng thái --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">Cài đặt</h3>
                <div class="space-y-3">
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                         <option value="active" {{ $product->status->value == 'active' ? 'selected' : '' }}>Công khai</option>
                         <option value="draft" {{ $product->status->value == 'draft' ? 'selected' : '' }}>Nháp</option>
                         <option value="inactive" {{ $product->status->value == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                    </select>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                        <span class="text-sm text-slate-700">Nổi bật</span>
                    </label>
                </div>
            </div>

            {{-- Thumbnail --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">Ảnh đại diện</h3>
                @if($product->thumbnail)
                    <div class="mb-3 relative group">
                        <img src="{{ Storage::url($product->thumbnail) }}" class="w-full rounded-lg border border-slate-200">
                        <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center text-white text-xs rounded-lg">
                             Ảnh hiện tại
                        </div>
                    </div>
                @endif
                <input type="file" name="thumbnail" class="w-full text-sm">
            </div>

            {{-- GALLERY (LOGIC XÓA ẢNH) --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">Album ảnh</h3>
                
                {{-- Khu vực chứa input ẩn để báo xóa ảnh --}}
                <div id="deletedImagesContainer"></div>

                <div class="grid grid-cols-3 gap-2 mb-4">
                    @foreach($product->images as $img)
                        <div class="relative group" id="gallery-item-{{ $img->id }}">
                            <img src="{{ Storage::url($img->image_url) }}" class="w-full h-20 object-cover rounded-lg border border-slate-200">
                            {{-- Nút xóa --}}
                            <button type="button" onclick="removeGalleryImage({{ $img->id }})" 
                                    class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-5 h-5 flex items-center justify-center shadow-sm hover:bg-rose-600 transition">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <label class="block text-sm font-medium text-slate-700 mb-1">Thêm ảnh mới</label>
                <input type="file" name="gallery[]" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
        </div>
    </div>
</form>

<script>
    // 1. Logic xóa ảnh Gallery
    function removeGalleryImage(imageId) {
        // Ẩn ảnh khỏi giao diện
        document.getElementById(`gallery-item-${imageId}`).remove();
        
        // Thêm input hidden để báo Backend xóa ảnh này
        const container = document.getElementById('deletedImagesContainer');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deleted_image_ids[]';
        input.value = imageId;
        container.appendChild(input);
    }

    // 2. Toggle Variants (Giống Create)
    const hasVariantsCheck = document.getElementById('hasVariantsCheck');
    const simpleConfig = document.getElementById('simpleProductConfig');
    const variantConfig = document.getElementById('variantProductConfig');

    hasVariantsCheck.addEventListener('change', function() {
        if(this.checked) {
            simpleConfig.classList.add('hidden');
            variantConfig.classList.remove('hidden');
        } else {
            simpleConfig.classList.remove('hidden');
            variantConfig.classList.add('hidden');
        }
    });

    // 3. Dynamic Variants (Index tiếp nối)
    const variantsContainer = document.getElementById('variantsContainer');
    const addVariantBtn = document.getElementById('addVariantBtn');
    // Bắt đầu index từ số lượng hiện có để không trùng
    let variantIndex = {{ count($product->variants) + 100 }}; 

    addVariantBtn.addEventListener('click', function() {
        const html = `
            <div class="variant-item grid grid-cols-12 gap-3 items-start p-4 bg-slate-50 rounded-xl border border-slate-200 relative animate-fade-in mt-3">
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs font-semibold text-slate-500 mb-1">Size</label>
                    <input type="text" name="variants[${variantIndex}][attributes][size]" class="w-full text-sm px-3 py-2 border rounded-lg">
                </div>
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs font-semibold text-slate-500 mb-1">Màu</label>
                    <input type="text" name="variants[${variantIndex}][attributes][color]" class="w-full text-sm px-3 py-2 border rounded-lg">
                </div>
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs font-semibold text-slate-500 mb-1">Giá</label>
                    <input type="number" name="variants[${variantIndex}][price]" class="w-full text-sm px-3 py-2 border rounded-lg">
                </div>
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs font-semibold text-slate-500 mb-1">Kho</label>
                    <input type="number" name="variants[${variantIndex}][stock_qty]" value="0" class="w-full text-sm px-3 py-2 border rounded-lg">
                </div>
                <div class="col-span-12 mt-2">
                    <label class="text-xs font-semibold text-slate-500 mb-1">SKU</label>
                    <input type="text" name="variants[${variantIndex}][sku]" class="w-full text-sm px-3 py-2 border rounded-lg">
                </div>
                <button type="button" class="absolute top-2 right-2 text-slate-400 hover:text-rose-500 remove-variant">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
        variantsContainer.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    // Remove new variants
    variantsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-variant')) {
            // Nếu là variant cũ (có ID hidden), bạn có thể cần logic đánh dấu xóa
            // Ở đây mình chỉ remove khỏi DOM cho đơn giản
            e.target.closest('.variant-item').remove();
        }
    });
</script>
@endsection