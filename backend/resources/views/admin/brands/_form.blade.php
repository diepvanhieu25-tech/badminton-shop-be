@php($brand = $brand ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Tên hãng --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Tên hãng <span class="text-rose-500">*</span></label>
        <input name="name"
               value="{{ old('name', $brand?->name) }}"
               placeholder="VD: Yonex"
               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 @error('name') border-rose-400 focus:border-rose-400 focus:ring-rose-100 @enderror">
        @error('name')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
    </div>

    {{-- Upload Logo (Đã sửa) --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Logo</label>
        
        {{-- Input File --}}
        <input type="file" 
               name="logo"
               class="block w-full text-sm text-slate-500
                      file:mr-4 file:py-2.5 file:px-4
                      file:rounded-xl file:border-0
                      file:text-sm file:font-semibold
                      file:bg-emerald-50 file:text-emerald-700
                      hover:file:bg-emerald-100
                      cursor-pointer border border-slate-200 rounded-xl bg-white">
        
        <div class="mt-1 text-xs text-slate-500">Định dạng: jpg, png, jpeg. Tối đa 2MB.</div>
        @error('logo')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror

        {{-- Hiển thị ảnh cũ nếu đang Edit --}}
        @if($brand?->logo_url)
            <div class="mt-3 flex items-center gap-3 p-2 border border-slate-200 rounded-xl bg-slate-50 w-fit">
                <img src="{{ asset('storage/' . $brand->logo_url) }}" 
                     alt="Current Logo" 
                     class="h-12 w-12 object-cover rounded-lg bg-white border border-slate-200">
                <div class="text-xs text-slate-500 pr-2">
                    <div>Logo hiện tại</div>
                    <div class="text-[10px] text-slate-400">Tải ảnh mới để thay thế</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Mô tả --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400"
                  placeholder="Mô tả ngắn về hãng...">{{ old('description', $brand?->description) }}</textarea>
    </div>

    {{-- Trạng thái --}}
    <div class="md:col-span-2 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
        <div>
            <div class="text-sm font-semibold text-slate-700">Trạng thái</div>
            <div class="text-xs text-slate-500">Bật/tắt hiển thị hãng trong hệ thống.</div>
        </div>

        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            {{-- Checkbox logic: Backend xử lý checkbox, nếu tick gửi value=1 --}}
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $brand?->is_active ?? true) ? 'checked' : '' }}
                   class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-200">
            <span class="text-sm font-medium text-slate-700">Active</span>
        </label>
    </div>
</div>