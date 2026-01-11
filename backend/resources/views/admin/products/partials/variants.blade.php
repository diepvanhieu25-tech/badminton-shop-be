<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-5 border-b pb-2">
        <h3 class="text-lg font-semibold text-slate-800">Biến thể (Variants)</h3>
        <button type="button" id="btn-add-variant"
                class="inline-flex items-center gap-2 px-3 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Thêm biến thể
        </button>
    </div>

    <div class="overflow-x-auto rounded-lg border border-slate-200">
        <table class="w-full text-sm text-left text-slate-600">
            <thead class="bg-slate-50 text-xs uppercase text-slate-700 font-semibold">
            <tr>
                <th class="px-4 py-3 w-20 text-center">Ảnh</th>
                <th class="px-4 py-3 min-w-[120px]">SKU</th>
                <th class="px-4 py-3 min-w-[100px]">Thuộc tính (Size/Color)</th>
                <th class="px-4 py-3 min-w-[120px]">Giá</th>
                <th class="px-4 py-3 min-w-20">Tồn kho</th>
                <th class="px-4 py-3 w-[50px]"></th>
            </tr>
            </thead>
            <tbody id="variants-body" class="divide-y divide-slate-100">
            @if($isEdit)
                @foreach($product->variants as $idx => $v)
                    @include('admin.products.partials.variant-row', ['idx' => $idx, 'v' => $v])
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <p class="text-xs text-slate-400 mt-2 italic">* SKU biến thể sẽ tự động cập nhật theo SKU chính.</p>
</div>

{{-- 
    TEMPLATE CHO JAVASCRIPT: 
    Để đoạn này ẩn đi, JS sẽ clone nội dung này khi thêm dòng mới.
    Giúp tránh hardcode HTML string trong file JS.
--}}
<template id="variant-row-template">
    <tr class="variant-row bg-white hover:bg-slate-50 transition-colors">
        <td class="px-4 py-2 align-top text-center">
             <label class="cursor-pointer block w-10 h-10 rounded border border-slate-300 bg-slate-50 hover:bg-slate-100 relative overflow-hidden group">
                <img src="" class="w-full h-full object-cover hidden variant-img-preview">
                <div class="absolute inset-0 flex items-center justify-center text-slate-400 variant-img-placeholder">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <input type="file" name="variants[INDEX][image]" class="hidden variant-img-input" accept="image/*">
            </label>
        </td>
        <td class="px-4 py-2 align-top">
            <input name="variants[INDEX][sku]" readonly
                   class="w-full bg-slate-100 border-slate-200 rounded px-2 py-1 text-xs font-mono variant-sku text-slate-500">
        </td>
        <td class="px-4 py-2 align-top space-y-2">
            <div class="flex gap-2">
                <input class="w-1/2 border-slate-300 rounded px-2 py-1 text-xs variant-size" placeholder="Size (VD: 40)">
                <input class="w-1/2 border-slate-300 rounded px-2 py-1 text-xs variant-color" placeholder="Màu (VD: Đen)">
            </div>
            <input type="hidden" name="variants[INDEX][attributes_json]" class="variant-attributes-json">
        </td>
        <td class="px-4 py-2 align-top space-y-1">
            <input type="number" name="variants[INDEX][price]" class="w-full border-slate-300 rounded px-2 py-1 text-xs" placeholder="Giá bán" value="0">
            <input type="number" name="variants[INDEX][original_price]" class="w-full border-slate-300 rounded px-2 py-1 text-xs" placeholder="Giá gốc" value="0">
        </td>
        <td class="px-4 py-2 align-top space-y-1">
            <input type="number" name="variants[INDEX][stock_qty]" class="w-full border-slate-300 rounded px-2 py-1 text-xs" placeholder="SL" value="0">
            <input type="number" name="variants[INDEX][stock_alert]" class="w-full border-slate-300 rounded px-2 py-1 text-xs" placeholder="Cảnh báo" value="5">
        </td>
        <td class="px-4 py-2 align-middle text-right">
            <button type="button" class="text-red-500 hover:text-red-700 btn-remove p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </td>
    </tr>
</template>