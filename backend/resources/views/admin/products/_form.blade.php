@php
    // FIX: không phụ thuộc $mode
    $isEdit = isset($product) && $product->exists;

    $currentStatus = old(
        'status',
        $isEdit
            ? (is_string($product->status)
                ? $product->status
                : $product->status?->value)
            : 'draft'
    );
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- BASIC --}}
        <div class="bg-white border rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin cơ bản</h3>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tên sản phẩm *</label>
                <input name="name" class="w-full border rounded px-3 py-2"
                       value="{{ old('name', $product->name ?? '') }}">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Giá *</label>
                    <input type="number" name="price" class="w-full border rounded px-3 py-2"
                           value="{{ old('price', $product->price ?? 0) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Giá gốc</label>
                    <input type="number" name="original_price" class="w-full border rounded px-3 py-2"
                           value="{{ old('original_price', $product->original_price ?? 0) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">SKU</label>
                <input id="product-sku" name="sku"
                       class="w-full border rounded px-3 py-2"
                       value="{{ old('sku', $product->sku ?? '') }}">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Mô tả</label>
                <textarea name="description" rows="4"
                          class="w-full border rounded px-3 py-2">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
        </div>

        {{-- VARIANTS --}}
        <div class="bg-white border border-slate-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Biến thể (Variants)</h3>
                <button type="button" id="btn-add-variant"
                        class="px-3 py-2 rounded bg-slate-900 text-white text-sm">
                    + Thêm variant
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border">
                    <thead class="bg-slate-50">
                    <tr>
                        <th class="p-2 border">SKU</th>
                        <th class="p-2 border">Size</th>
                        <th class="p-2 border">Color</th>
                        <th class="p-2 border">Giá</th>
                        <th class="p-2 border">Giá gốc</th>
                        <th class="p-2 border">Tồn</th>
                        <th class="p-2 border">Alert</th>
                        <th class="p-2 border text-right">Xóa</th>
                    </tr>
                    </thead>

                    <tbody id="variants-body">
                    @if($isEdit)
                        @foreach($product->variants as $idx => $v)
                            @php
                                $attr = is_array($v->attributes) ? $v->attributes : [];
                            @endphp

                            <tr class="variant-row">
                                <td class="p-2 border">
                                    <input type="hidden" name="variants[{{ $idx }}][id]" value="{{ $v->id }}">

                                    {{-- SKU giữ nguyên = SKU sản phẩm --}}
                                    <input name="variants[{{ $idx }}][sku]"
                                        class="w-full border rounded px-2 py-1 bg-slate-100 variant-sku"
                                        readonly
                                        value="{{ old("variants.$idx.sku", $v->sku) }}">
                                </td>

                                <td class="p-2 border">
                                    <input class="w-full border rounded px-2 py-1 variant-size"
                                        value="{{ $attr['size'] ?? '' }}"
                                        placeholder="VD: 40">
                                </td>

                                <td class="p-2 border">
                                    <input class="w-full border rounded px-2 py-1 variant-color"
                                        value="{{ $attr['color'] ?? '' }}"
                                        placeholder="VD: Trắng">

                                    {{-- FIELD CŨ – GIỮ NGUYÊN --}}
                                    <input type="hidden"
                                        name="variants[{{ $idx }}][attributes_json]"
                                        class="variant-attributes-json">
                                </td>

                                <td class="p-2 border">
                                    <input type="number" name="variants[{{ $idx }}][price]"
                                        class="w-full border rounded px-2 py-1"
                                        value="{{ old("variants.$idx.price", $v->price) }}">
                                </td>

                                <td class="p-2 border">
                                    <input type="number" name="variants[{{ $idx }}][original_price]"
                                        class="w-full border rounded px-2 py-1"
                                        value="{{ old("variants.$idx.original_price", $v->original_price) }}">
                                </td>

                                <td class="p-2 border">
                                    <input type="number" name="variants[{{ $idx }}][stock_qty]"
                                        class="w-full border rounded px-2 py-1"
                                        value="{{ old("variants.$idx.stock_qty", $v->stock_qty) }}">
                                </td>

                                <td class="p-2 border">
                                    <input type="number" name="variants[{{ $idx }}][stock_alert]"
                                        class="w-full border rounded px-2 py-1"
                                        value="{{ old("variants.$idx.stock_alert", $v->stock_alert) }}">
                                </td>

                                <td class="p-2 border text-right">
                                    <label class="inline-flex items-center gap-2 text-xs text-red-600">
                                        <input type="checkbox"
                                            name="variants[{{ $idx }}][_delete]"
                                            value="1">
                                        Xóa
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- IMAGES --}}
        <div class="bg-white border rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Ảnh sản phẩm</h3>

            @if($isEdit && $product->images->count())
                <div class="grid grid-cols-4 gap-3 mb-4">
                    @foreach($product->images as $i => $img)
                        <div class="border rounded overflow-hidden">
                            <img src="{{ Storage::url($img->image_url) }}"
                                 class="w-full h-24 object-cover">
                            <div class="p-2 text-xs text-center">
                                <label class="text-red-600">
                                    <input type="checkbox"
                                           name="existing_images[{{ $i }}][id]"
                                           value="{{ $img->id }}"> Xóa
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <input type="file" name="images[]" multiple
                   class="w-full border rounded px-3 py-2">
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="bg-white border rounded-lg p-6 space-y-4">

        <div>
            <label class="block text-sm font-medium mb-1">Trạng thái</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                @foreach(\App\Enums\ProductStatus::cases() as $st)
                    <option value="{{ $st->value }}"
                        @selected($currentStatus === $st->value)>
                        {{ ucfirst($st->value) }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Thương hiệu</label>
            <select name="brand_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Chọn thương hiệu --</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}"
                        @selected(old('brand_id', $isEdit ? $product->brand_id : null) == $b->id)>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Danh mục</label>
            <select name="category_id" class="w-full border rounded px-3 py-2">
                @foreach($categories as $c)
                    <option value="{{ $c->id }}"
                        @selected(old('category_id', $product->category_id ?? null) == $c->id)>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

         {{-- THUMBNAIL --}}
        <div>
            <label class="block text-sm font-medium mb-1">Thumbnail</label>

            <input type="file"
                name="thumbnail"
                accept="image/*"
                class="w-full border rounded px-3 py-2">

            @if($isEdit && !empty($product->thumbnail))
                <div class="mt-3">
                    <div class="text-xs text-slate-500 mb-1">Thumbnail hiện tại</div>
                    <img
                        src="{{ Storage::url($product->thumbnail) }}"
                        alt="Thumbnail"
                        class="w-full h-40 object-cover rounded border"
                    >
                </div>
            @endif
        </div>
        
        <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-3 rounded">
            {{ $isEdit ? 'Cập nhật' : 'Tạo sản phẩm' }}
        </button>

        <a href="{{ route('admin.products.index') }}"
           class="block text-center bg-slate-200 py-3 rounded font-bold">
            Hủy
        </a>
    </div>

</div>

{{-- JS --}}
<script>
(function () {
    const tbody = document.getElementById('variants-body');
    const btn = document.getElementById('btn-add-variant');
    const productSkuInput = document.querySelector('input[name="sku"]');

    if (!tbody || !btn || !productSkuInput) return;

    let idx = tbody.querySelectorAll('.variant-row').length;

    // SKU variant = SKU sản phẩm + -V1, -V2, -V3...
    function syncVariantSku() {
        const baseSku = productSkuInput.value || '';

        tbody.querySelectorAll('.variant-row').forEach((tr, index) => {
            const skuInput = tr.querySelector('.variant-sku');
            if (!skuInput) return;

            if (baseSku) {
                skuInput.value = `${baseSku}-V${index + 1}`;
            } else {
                skuInput.value = '';
            }
        });
}

    // build attributes_json từ size + color
    function buildAttributes(tr) {
        const size = tr.querySelector('.variant-size')?.value;
        const color = tr.querySelector('.variant-color')?.value;
        const jsonInput = tr.querySelector('.variant-attributes-json');

        const obj = {};
        if (size !== '') obj.size = Number(size);
        if (color) obj.color = color;

        jsonInput.value = JSON.stringify(obj);
    }

    // init row hiện có
    tbody.querySelectorAll('.variant-row').forEach(tr => {
        tr.querySelectorAll('.variant-size, .variant-color')
            .forEach(el => el.addEventListener('input', () => buildAttributes(tr)));
        buildAttributes(tr);
    });

    btn.addEventListener('click', () => {
        const tr = document.createElement('tr');
        tr.className = 'variant-row';
        tr.innerHTML = `
            <td class="p-2 border">
                <input name="variants[${idx}][sku]"
                       class="w-full border rounded px-2 py-1 bg-slate-100 variant-sku"
                       readonly>
            </td>
            <td class="p-2 border">
                <input class="w-full border rounded px-2 py-1 variant-size" placeholder="VD: 40">
            </td>
            <td class="p-2 border">
                <input class="w-full border rounded px-2 py-1 variant-color" placeholder="VD: Trắng">
                <input type="hidden"
                       name="variants[${idx}][attributes_json]"
                       class="variant-attributes-json">
            </td>
            <td class="p-2 border">
                <input type="number" name="variants[${idx}][price]"
                       class="w-full border rounded px-2 py-1" value="0">
            </td>
            <td class="p-2 border">
                <input type="number" name="variants[${idx}][original_price]"
                       class="w-full border rounded px-2 py-1" value="0">
            </td>
            <td class="p-2 border">
                <input type="number" name="variants[${idx}][stock_qty]"
                       class="w-full border rounded px-2 py-1" value="0">
            </td>
            <td class="p-2 border">
                <input type="number" name="variants[${idx}][stock_alert]"
                       class="w-full border rounded px-2 py-1" value="0">
            </td>
            <td class="p-2 border text-right">
                <button type="button" class="text-xs text-red-600 btn-remove">Xóa</button>
            </td>
        `;
        tr.querySelector('.btn-remove').onclick = () => tr.remove();
        tr.querySelectorAll('.variant-size, .variant-color')
            .forEach(el => el.addEventListener('input', () => buildAttributes(tr)));

        tbody.appendChild(tr);
        idx++;

        syncVariantSku();
        buildAttributes(tr);
    });

    productSkuInput.addEventListener('input', syncVariantSku);
    syncVariantSku();
})();
</script>
