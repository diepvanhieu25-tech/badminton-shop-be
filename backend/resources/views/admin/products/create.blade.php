@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Thêm sản phẩm mới</h1>
            <p class="text-sm text-slate-500">Điền thông tin chi tiết sản phẩm</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium shadow-md">
                Lưu sản phẩm
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Thông tin chung</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tên sản phẩm <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
                        @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Danh mục <span class="text-rose-500">*</span></label>
                            <select name="category_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Thương hiệu</label>
                            <select name="brand_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
                                <option value="">Chọn thương hiệu</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Mô tả chi tiết</label>
                        <textarea name="description" rows="5" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Dữ liệu sản phẩm</h3>
                    <label class="flex items-center cursor-pointer gap-2 select-none">
                        <input type="checkbox" name="has_variants" id="hasVariantsCheck" value="1" {{ old('has_variants') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span class="text-sm font-medium text-slate-700">Sản phẩm có nhiều phiên bản (Size/Màu)</span>
                    </label>
                </div>

                <div id="simpleProductConfig" class="{{ old('has_variants') ? 'hidden' : 'block' }} space-y-4 border-t border-slate-100 pt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Giá bán (VNĐ)</label>
                            <input type="number" name="price" value="{{ old('price') }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Giá gốc (Gạch ngang)</label>
                            <input type="number" name="original_price" value="{{ old('original_price') }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Mã SKU (Mã kho)</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none">
                    </div>
                </div>

                <div id="variantProductConfig" class="{{ old('has_variants') ? 'block' : 'hidden' }} space-y-4 border-t border-slate-100 pt-4">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800 mb-4">
                        💡 Thêm các phiên bản như Size, Màu sắc. Giá và kho sẽ được quản lý theo từng phiên bản.
                    </div>

                    <div id="variantsContainer" class="space-y-3">
                        {{-- Vị trí Javascript sẽ append variants vào đây --}}
                        
                        {{-- Logic để hiển thị lại dữ liệu cũ khi validate fail --}}
                        @if(old('variants'))
                            @foreach(old('variants') as $index => $variant)
                                <div class="variant-item grid grid-cols-12 gap-2 items-end p-4 bg-slate-50 rounded-lg border border-slate-200 relative">
                                    <div class="col-span-3">
                                        <label class="text-xs font-medium text-slate-500">Tên/Size</label>
                                        <input type="text" name="variants[{{$index}}][attributes][size]" value="{{ $variant['attributes']['size'] ?? '' }}" placeholder="VD: Size L" class="w-full text-sm px-3 py-2 border rounded">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="text-xs font-medium text-slate-500">Màu sắc</label>
                                        <input type="text" name="variants[{{$index}}][attributes][color]" value="{{ $variant['attributes']['color'] ?? '' }}" placeholder="VD: Đỏ" class="w-full text-sm px-3 py-2 border rounded">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="text-xs font-medium text-slate-500">Giá bán</label>
                                        <input type="number" name="variants[{{$index}}][price]" value="{{ $variant['price'] ?? 0 }}" class="w-full text-sm px-3 py-2 border rounded">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-xs font-medium text-slate-500">Kho</label>
                                        <input type="number" name="variants[{{$index}}][stock_qty]" value="{{ $variant['stock_qty'] ?? 0 }}" class="w-full text-sm px-3 py-2 border rounded">
                                    </div>
                                    <div class="col-span-1 text-center pb-2">
                                        <button type="button" class="text-rose-500 hover:text-rose-700 remove-variant">✕</button>
                                    </div>
                                    <div class="col-span-12 mt-2">
                                        <input type="text" name="variants[{{$index}}][sku]" value="{{ $variant['sku'] ?? '' }}" placeholder="SKU Biến thể (Bắt buộc)" class="w-full text-sm px-3 py-2 border border-slate-300 rounded bg-white">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" id="addVariantBtn" class="w-full py-2 border-2 border-dashed border-emerald-300 text-emerald-600 rounded-lg hover:bg-emerald-50 font-medium transition">
                        + Thêm phiên bản mới
                    </button>
                    @error('variants') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase tracking-wider">Cài đặt đăng</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Trạng thái</label>
                        <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Công khai</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-600">
                        <span class="text-sm text-slate-700">Sản phẩm nổi bật</span>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase tracking-wider">Ảnh đại diện</h3>
                <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:bg-slate-50 transition relative">
                    <input type="file" name="thumbnail" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this, 'thumbnailPreview')">
                    <div id="thumbnailPreview" class="hidden mb-2">
                        <img src="" class="w-full h-48 object-contain rounded">
                    </div>
                    <div id="thumbnailPlaceholder">
                        <span class="text-4xl">📷</span>
                        <p class="text-sm text-slate-500 mt-2">Kéo thả hoặc click để tải ảnh lên</p>
                    </div>
                </div>
                @error('thumbnail') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-3 uppercase tracking-wider">Album ảnh (Gallery)</h3>
                <input type="file" name="gallery[]" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 mb-2">
                <p class="text-xs text-slate-400">Giữ Ctrl để chọn nhiều ảnh</p>
            </div>
        </div>
    </div>
</form>

{{-- JAVASCRIPT XỬ LÝ LOGIC --}}
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