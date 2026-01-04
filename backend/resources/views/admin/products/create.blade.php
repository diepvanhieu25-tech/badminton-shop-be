@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Thêm sản phẩm mới</h1>
            <p class="text-sm text-slate-500">Tạo sản phẩm và các phiên bản (nếu có).</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left"></i> Hủy
            </a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium shadow-md transition">
                <i class="fa-solid fa-floppy-disk"></i> Lưu sản phẩm
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- CỘT TRÁI: Thông tin chính --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- 1. Thông tin chung --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-emerald-600"></i> Thông tin chung
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tên sản phẩm <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="VD: Vợt cầu lông Yonex Astrox 77" 
                               class="w-full px-4 py-2 border  rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none transition @error('name') border-rose-500 @enderror">
                        @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Danh mục <span class="text-rose-500">*</span></label>
                            <select name="category_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Thương hiệu</label>
                            <select name="brand_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả chi tiết</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. Giá & Biến thể --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-tags text-emerald-600"></i> Giá & Kho hàng
                    </h3>
                    <label class="flex items-center gap-2 cursor-pointer select-none bg-slate-100 px-3 py-1.5 rounded-lg hover:bg-slate-200 transition">
                        <input type="checkbox" name="has_variants" id="hasVariantsCheck" value="1" {{ old('has_variants') ? 'checked' : '' }} 
                               class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 border-gray-300">
                        <span class="text-sm font-medium text-slate-700">Sản phẩm có biến thể (Size/Màu)</span>
                    </label>
                </div>

                {{-- FORM SẢN PHẨM ĐƠN GIẢN --}}
                <div id="simpleProductConfig" class="{{ old('has_variants') ? 'hidden' : 'block' }} space-y-4 border-t border-slate-100 pt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Giá bán <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="price" value="{{ old('price') }}" class="w-full pl-4 pr-12 py-2 border border-slate-300 rounded-lg outline-none focus:border-emerald-500">
                                <span class="absolute right-3 top-2 text-slate-400 text-sm">đ</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Giá gốc (Gạch ngang)</label>
                            <div class="relative">
                                <input type="number" name="original_price" value="{{ old('original_price') }}" class="w-full pl-4 pr-12 py-2 border border-slate-300 rounded-lg outline-none focus:border-emerald-500">
                                <span class="absolute right-3 top-2 text-slate-400 text-sm">đ</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Mã SKU (Mã kho)</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg outline-none focus:border-emerald-500">
                    </div>
                </div>

                {{-- FORM SẢN PHẨM BIẾN THỂ --}}
                <div id="variantProductConfig" class="{{ old('has_variants') ? 'block' : 'hidden' }} space-y-4 border-t border-slate-100 pt-4">
                    <div class="bg-blue-50 text-blue-700 p-3 rounded-lg text-sm flex items-start gap-2">
                        <i class="fa-solid fa-circle-info mt-0.5"></i>
                        <span>Thêm các phiên bản như Size, Màu sắc. Hệ thống sẽ quản lý kho và giá riêng cho từng phiên bản.</span>
                    </div>

                    <div id="variantsContainer" class="space-y-3">
                        {{-- JS sẽ render variants vào đây --}}
                    </div>

                    <button type="button" id="addVariantBtn" class="w-full py-2.5 border-2 border-dashed border-emerald-300 text-emerald-600 rounded-xl hover:bg-emerald-50 font-semibold transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus"></i> Thêm phiên bản
                    </button>
                    @error('variants') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: Cài đặt & Ảnh --}}
        <div class="space-y-6">
            {{-- 3. Trạng thái --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">Cài đặt hiển thị</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Trạng thái</label>
                        <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none">
                            <option value="active">Công khai</option>
                            <option value="draft">Bản nháp</option>
                            <option value="inactive">Ngừng bán</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-slate-700 font-medium">Sản phẩm nổi bật</span>
                    </label>
                </div>
            </div>

            {{-- 4. Thumbnail --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">Ảnh đại diện</h3>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center hover:bg-slate-50 transition relative group cursor-pointer">
                    <input type="file" name="thumbnail" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this, 'thumbnailPreview')">
                    
                    <div id="thumbnailPreview" class="hidden relative z-0">
                        <img src="" class="w-full h-48 object-contain rounded-lg">
                    </div>
                    
                    <div id="thumbnailPlaceholder" class="py-4">
                        <i class="fa-regular fa-image text-4xl text-slate-300 mb-2"></i>
                        <p class="text-xs text-slate-500">Click để tải ảnh lên</p>
                    </div>
                </div>
            </div>

            {{-- 5. Gallery --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase">Album ảnh</h3>
                <input type="file" name="gallery[]" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-slate-400 mt-2">Giữ Ctrl để chọn nhiều ảnh.</p>
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
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-auto rounded-lg">`;
                preview.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 2. Toggle Variants
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

    // 3. Dynamic Variants (Logic chuẩn)
    const variantsContainer = document.getElementById('variantsContainer');
    const addVariantBtn = document.getElementById('addVariantBtn');
    let variantIndex = {{ count(old('variants') ?? []) }}; // Đếm index

    addVariantBtn.addEventListener('click', function() {
        const html = `
            <div class="variant-item grid grid-cols-12 gap-3 items-start p-4 bg-slate-50 rounded-xl border border-slate-200 relative animate-fade-in">
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Size</label>
                    <input type="text" name="variants[${variantIndex}][attributes][size]" placeholder="Size..." class="w-full text-sm px-3 py-2 border rounded-lg focus:border-emerald-500 outline-none">
                </div>
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Màu sắc</label>
                    <input type="text" name="variants[${variantIndex}][attributes][color]" placeholder="Màu..." class="w-full text-sm px-3 py-2 border rounded-lg focus:border-emerald-500 outline-none">
                </div>
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Giá bán</label>
                    <input type="number" name="variants[${variantIndex}][price]" placeholder="VNĐ" class="w-full text-sm px-3 py-2 border rounded-lg focus:border-emerald-500 outline-none">
                </div>
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">Kho</label>
                    <input type="number" name="variants[${variantIndex}][stock_qty]" value="0" class="w-full text-sm px-3 py-2 border rounded-lg focus:border-emerald-500 outline-none">
                </div>
                <div class="col-span-12 mt-2">
                    <label class="text-xs font-semibold text-slate-500 mb-1 block">SKU (Mã phiên bản - Bắt buộc)</label>
                    <input type="text" name="variants[${variantIndex}][sku]" placeholder="VD: YONEX-77-RED-L" class="w-full text-sm px-3 py-2 border border-slate-300 rounded-lg bg-white focus:border-emerald-500 outline-none">
                </div>
                
                <button type="button" class="absolute top-2 right-2 text-slate-400 hover:text-rose-500 transition remove-variant">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        `;
        variantsContainer.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    // Xóa variant
    variantsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-variant')) {
            e.target.closest('.variant-item').remove();
        }
    });
</script>
@endsection