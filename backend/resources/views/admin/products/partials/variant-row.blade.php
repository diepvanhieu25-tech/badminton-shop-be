@php
    // Lấy thuộc tính từ JSON hoặc mảng
    $attr = is_string($v->attributes) ? json_decode($v->attributes, true) : ($v->attributes ?? []);
@endphp

<tr class="variant-row bg-white hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0">
    
    {{-- 1. CỘT ẢNH MỚI --}}
    <td class="px-4 py-3 align-top text-center">
        <label class="cursor-pointer block w-10 h-10 rounded border border-slate-300 bg-slate-50 hover:bg-slate-100 relative overflow-hidden group mx-auto">
            
            {{-- Hiển thị ảnh nếu đã có (khi Edit) --}}
            @php
                $imgUrl = $v->image ? Storage::url($v->image) : null;
            @endphp

            <img src="{{ $imgUrl }}" 
                 class="w-full h-full object-cover variant-img-preview {{ $imgUrl ? '' : 'hidden' }}">

            {{-- Placeholder khi chưa có ảnh --}}
            <div class="absolute inset-0 flex items-center justify-center text-slate-400 variant-img-placeholder {{ $imgUrl ? 'hidden' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>

            {{-- Input File --}}
            <input type="file" 
                   name="variants[{{ $idx }}][image]" 
                   class="hidden variant-img-input" 
                   accept="image/*">
        </label>
    </td>
    {{-- SKU (Readonly) --}}
    <td class="px-4 py-3 align-top">
        <input type="hidden" name="variants[{{ $idx }}][id]" value="{{ $v->id }}">
        
        <input name="variants[{{ $idx }}][sku]" 
               readonly
               value="{{ $v->sku }}"
               class="w-full bg-slate-100 border-slate-200 rounded px-2 py-1.5 text-xs font-mono variant-sku text-slate-500 cursor-not-allowed focus:ring-0">
    </td>

    {{-- Attributes (Size/Color) --}}
    <td class="px-4 py-3 align-top space-y-2">
        <div class="flex gap-2">
            <input class="w-1/2 border-slate-300 rounded px-2 py-1.5 text-xs variant-size focus:ring-blue-500 focus:border-blue-500"
                   value="{{ $attr['size'] ?? '' }}" 
                   placeholder="Size (40)">
                   
            <input class="w-1/2 border-slate-300 rounded px-2 py-1.5 text-xs variant-color focus:ring-blue-500 focus:border-blue-500"
                   value="{{ $attr['color'] ?? '' }}" 
                   placeholder="Màu (Đen)">
        </div>
        
        {{-- Hidden Input JSON để JS cập nhật --}}
        <input type="hidden" 
               name="variants[{{ $idx }}][attributes_json]" 
               class="variant-attributes-json"
               value="{{ json_encode($attr) }}">
    </td>

    {{-- Prices --}}
    <td class="px-4 py-3 align-top space-y-2">
        <div class="relative">
            <input type="number" 
                   name="variants[{{ $idx }}][price]"
                   value="{{ $v->price }}"
                   class="w-full border-slate-300 rounded px-2 py-1.5 text-xs font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500" 
                   placeholder="Giá bán">
        </div>
        <div class="relative">
             <input type="number" 
                   name="variants[{{ $idx }}][original_price]"
                   value="{{ $v->original_price }}"
                   class="w-full border-slate-300 rounded px-2 py-1.5 text-xs text-slate-500 focus:ring-blue-500 focus:border-blue-500" 
                   placeholder="Giá gốc">
        </div>
    </td>

    {{-- Stock --}}
    <td class="px-4 py-3 align-top space-y-2">
        <input type="number" 
               name="variants[{{ $idx }}][stock_qty]"
               value="{{ $v->stock_qty }}"
               class="w-full border-slate-300 rounded px-2 py-1.5 text-xs font-medium focus:ring-blue-500 focus:border-blue-500" 
               placeholder="SL">
               
        <input type="number" 
               name="variants[{{ $idx }}][stock_alert]"
               value="{{ $v->stock_alert }}"
               class="w-full border-slate-300 rounded px-2 py-1.5 text-xs text-slate-400 focus:ring-blue-500 focus:border-blue-500" 
               placeholder="Min">
    </td>

    {{-- Delete Action --}}
    <td class="px-4 py-3 align-middle text-right">
        <label class="inline-flex items-center cursor-pointer group">
            <input type="checkbox" name="variants[{{ $idx }}][_delete]" value="1" class="peer hidden">
            
            {{-- Icon Thùng rác (Hiện khi chưa check) --}}
            <div class="p-1.5 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 transition peer-checked:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>

            {{-- Badge Đã Xóa (Hiện khi đã check) --}}
            <span class="hidden peer-checked:inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded border border-red-200">
                Sẽ xóa
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </span>
        </label>
    </td>
</tr>