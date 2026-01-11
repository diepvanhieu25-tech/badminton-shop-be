@php
    $currentStatus = old('status', $isEdit ? ($product->status instanceof \BackedEnum ? $product->status->value : $product->status) : 'draft');
@endphp

<div class="space-y-6">
    {{-- ACTION BUTTONS --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sticky top-4 z-10">
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-md transition-all mb-3">
            {{ $isEdit ? 'Cập nhật sản phẩm' : 'Đăng sản phẩm' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="block w-full text-center text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 font-medium py-2.5 rounded-lg transition-all">
            Hủy bỏ
        </a>
    </div>

    {{-- SETTINGS --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <h4 class="font-semibold text-slate-800 mb-4">Cấu hình</h4>
        
        <div class="space-y-4">
            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Trạng thái</label>
                <select name="status" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white">
                    @foreach(\App\Enums\ProductStatus::cases() as $st)
                        <option value="{{ $st->value }}" @selected($currentStatus === $st->value)>
                            {{ ucfirst($st->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Brand --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Thương hiệu</label>
                <select name="brand_id" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white">
                    <option value="">-- Chọn thương hiệu --</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected(old('brand_id', $product->brand_id ?? null) == $b->id)>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Danh mục</label>
                <select name="category_id" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white">
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id ?? null) == $c->id)>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- THUMBNAIL --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <h4 class="font-semibold text-slate-800 mb-4">Ảnh đại diện</h4>
        
        <div class="w-full aspect-square bg-slate-50 border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center overflow-hidden relative group">
             @if($isEdit && !empty($product->thumbnail))
                <img src="{{ Storage::url($product->thumbnail) }}" class="w-full h-full object-cover">
             @else
                <span class="text-slate-400 text-sm">Chưa có ảnh</span>
             @endif
             
             <input type="file" name="thumbnail" accept="image/*" 
                    class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-20">
             <div class="absolute inset-0 bg-black/40 items-center justify-center hidden group-hover:flex z-10 transition-all">
                 <span class="text-white text-sm font-medium">Thay đổi</span>
             </div>
        </div>
    </div>
</div>