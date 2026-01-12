<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-slate-800 mb-5 border-b pb-2">Thông tin cơ bản</h3>

    <div class="space-y-4">
        {{-- Tên sản phẩm --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
            <input name="name" type="text" 
                   class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                   value="{{ old('name', $product->name ?? '') }}" placeholder="Nhập tên sản phẩm...">
            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Giá bán --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Giá bán <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="number" name="price" 
                           class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm pl-3 pr-12"
                           value="{{ old('price', $product->price ?? 0) }}">
                    <span class="absolute right-3 top-2 text-slate-400 text-sm">VND</span>
                </div>
            </div>
            {{-- Giá gốc --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Giá gốc (So sánh)</label>
                <div class="relative">
                    <input type="number" name="original_price" 
                           class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm pl-3 pr-12"
                           value="{{ old('original_price', $product->original_price ?? 0) }}">
                    <span class="absolute right-3 top-2 text-slate-400 text-sm">VND</span>
                </div>
            </div>
        </div>

        {{-- SKU --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Mã sản phẩm (SKU)</label>
            <input id="product-sku" name="sku" type="text"
                   class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm uppercase font-mono"
                   value="{{ old('sku', $product->sku ?? '') }}">
        </div>

        {{-- Mô tả --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Mô tả chi tiết</label>
            <textarea name="description" rows="4" 
                      class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
    </div>
</div>