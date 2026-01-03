@extends('layouts.admin')

@section('title', 'Cập nhật sản phẩm')

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    {{-- Header giống create --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Cập nhật: {{ $product->name }}</h1>
        <div class="flex gap-3">
             <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium shadow-md">Cập nhật</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Block 1: Thông tin (Giống Create, value dùng old('name', $product->name)) --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                 <h3 class="text-lg font-semibold text-slate-800 mb-4">Thông tin chung</h3>
                 <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tên sản phẩm</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none">
                    </div>
                    {{-- Category, Brand, Description tương tự... --}}
                 </div>
            </div>

            {{-- Block 2: Giá & Biến thể --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Dữ liệu sản phẩm</h3>
                    <label class="flex items-center cursor-pointer gap-2 select-none">
                        <input type="checkbox" name="has_variants" id="hasVariantsCheck" value="1" 
                            {{ old('has_variants', $product->has_variants) ? 'checked' : '' }} 
                            class="w-4 h-4 text-emerald-600 rounded border-slate-300">
                        <span class="text-sm font-medium text-slate-700">Có biến thể</span>
                    </label>
                </div>

                {{-- Simple Product --}}
                <div id="simpleProductConfig" class="{{ old('has_variants', $product->has_variants) ? 'hidden' : 'block' }} space-y-4">
                     <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Giá bán</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Giá gốc</label>
                            <input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>

                {{-- Variant Product --}}
                <div id="variantProductConfig" class="{{ old('has_variants', $product->has_variants) ? 'block' : 'hidden' }}">
                     <div id="variantsContainer" class="space-y-3">
                        @foreach($product->variants as $index => $variant)
                            <div class="variant-item grid grid-cols-12 gap-2 items-end p-4 bg-slate-50 rounded-lg border border-slate-200 relative">
                                <input type="hidden" name="variants[{{$index}}][id]" value="{{ $variant->id }}"> <div class="col-span-3">
                                    <label class="text-xs font-medium text-slate-500">Size</label>
                                    <input type="text" name="variants[{{$index}}][attributes][size]" value="{{ $variant->attributes['size'] ?? '' }}" class="w-full text-sm px-3 py-2 border rounded">
                                </div>
                                <div class="col-span-3">
                                    <label class="text-xs font-medium text-slate-500">Màu</label>
                                    <input type="text" name="variants[{{$index}}][attributes][color]" value="{{ $variant->attributes['color'] ?? '' }}" class="w-full text-sm px-3 py-2 border rounded">
                                </div>
                                <div class="col-span-3">
                                    <label class="text-xs font-medium text-slate-500">Giá</label>
                                    <input type="number" name="variants[{{$index}}][price]" value="{{ $variant->price }}" class="w-full text-sm px-3 py-2 border rounded">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs font-medium text-slate-500">Kho</label>
                                    <input type="number" name="variants[{{$index}}][stock_qty]" value="{{ $variant->stock_qty }}" class="w-full text-sm px-3 py-2 border rounded">
                                </div>
                                <div class="col-span-1 text-center pb-2">
                                     {{-- Nút xóa này chỉ ẩn UI, thực tế bạn có thể cần logic xóa variant trong DB nếu user muốn --}}
                                    <button type="button" class="text-rose-500 hover:text-rose-700 remove-variant">✕</button>
                                </div>
                                <div class="col-span-12 mt-2">
                                    <input type="text" name="variants[{{$index}}][sku]" value="{{ $variant->sku }}" class="w-full text-sm px-3 py-2 border rounded">
                                </div>
                            </div>
                        @endforeach
                     </div>
                     <button type="button" id="addVariantBtn" class="mt-4 w-full py-2 border-2 border-dashed border-emerald-300 text-emerald-600 rounded-lg">+ Thêm phiên bản</button>
                </div>
            </div>
        </div>

        <div class="space-y-6">
             {{-- Status --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <label class="block text-sm font-medium text-slate-700 mb-1">Trạng thái</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg">
                    <option value="draft" {{ $product->status->value == 'draft' ? 'selected' : '' }}>Nháp</option>
                    <option value="active" {{ $product->status->value == 'active' ? 'selected' : '' }}>Công khai</option>
                    <option value="inactive" {{ $product->status->value == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                </select>
            </div>

            {{-- Thumbnail --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                 <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">Ảnh đại diện</h3>
                 @if($product->thumbnail)
                    <img src="{{ Storage::url($product->thumbnail) }}" class="w-full rounded mb-3">
                 @endif
                 <input type="file" name="thumbnail" class="w-full text-sm">
            </div>
            
            {{-- Gallery --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">Album ảnh</h3>
                <div class="grid grid-cols-3 gap-2 mb-3">
                    @foreach($product->images as $img)
                        <img src="{{ Storage::url($img->image_url) }}" class="w-full h-16 object-cover rounded border">
                    @endforeach
                </div>
                <input type="file" name="gallery[]" multiple class="w-full text-sm">
            </div>
        </div>
    </div>
</form>

<script>
    // 1. Preview Ảnh
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById('thumbnailPlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-auto rounded">`;
                preview.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 2. Toggle Variants vs Simple
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

    // 3. Dynamic Add Variants
    const addVariantBtn = document.getElementById('addVariantBtn');
    const variantsContainer = document.getElementById('variantsContainer');
    let variantCount = {{ count(old('variants') ?? []) }}; // Đếm số variant hiện tại

    addVariantBtn.addEventListener('click', function() {
        const html = `
            <div class="variant-item grid grid-cols-12 gap-2 items-end p-4 bg-slate-50 rounded-lg border border-slate-200 relative animate-fade-in-down">
                <div class="col-span-3">
                    <label class="text-xs font-medium text-slate-500">Tên/Size</label>
                    <input type="text" name="variants[${variantCount}][attributes][size]" placeholder="Size L" class="w-full text-sm px-3 py-2 border rounded focus:border-emerald-500 outline-none">
                </div>
                <div class="col-span-3">
                    <label class="text-xs font-medium text-slate-500">Màu sắc</label>
                    <input type="text" name="variants[${variantCount}][attributes][color]" placeholder="Đỏ" class="w-full text-sm px-3 py-2 border rounded focus:border-emerald-500 outline-none">
                </div>
                <div class="col-span-3">
                    <label class="text-xs font-medium text-slate-500">Giá bán</label>
                    <input type="number" name="variants[${variantCount}][price]" value="0" class="w-full text-sm px-3 py-2 border rounded focus:border-emerald-500 outline-none">
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-medium text-slate-500">Kho</label>
                    <input type="number" name="variants[${variantCount}][stock_qty]" value="0" class="w-full text-sm px-3 py-2 border rounded focus:border-emerald-500 outline-none">
                </div>
                <div class="col-span-1 text-center pb-2">
                    <button type="button" class="text-rose-500 hover:text-rose-700 font-bold remove-variant" onclick="this.closest('.variant-item').remove()">✕</button>
                </div>
                <div class="col-span-12 mt-2">
                    <input type="text" name="variants[${variantCount}][sku]" placeholder="SKU Biến thể (Bắt buộc & Duy nhất)" class="w-full text-sm px-3 py-2 border border-slate-300 rounded bg-white focus:border-emerald-500 outline-none">
                </div>
            </div>
        `;
        variantsContainer.insertAdjacentHTML('beforeend', html);
        variantCount++;
    });

    // Delegate event remove (cho các item có sẵn nếu cần)
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-variant')) {
            e.target.closest('.variant-item').remove();
        }
    });
</script>
@endsection