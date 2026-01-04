@php($brand = $brand ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Tên hãng --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Tên hãng <span class="text-rose-500">*</span></label>
        <div class="relative">
             {{-- Icon input --}}
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-tag text-slate-400 text-xs"></i>
            </div>
            <input name="name"
                   value="{{ old('name', $brand?->name) }}"
                   placeholder="VD: Yonex, Lining..."
                   class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 @error('name') border-rose-400 focus:border-rose-400 focus:ring-rose-100 @enderror">
        </div>
        @error('name')<div class="mt-1 text-sm text-rose-600 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
    </div>

    {{-- Upload Logo --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Logo</label>
        
        <input type="file" 
               name="logo"
               class="block w-full text-sm text-slate-500
                      file:mr-4 file:py-2.5 file:px-4
                      file:rounded-xl file:border-0
                      file:text-sm file:font-semibold
                      file:bg-emerald-50 file:text-emerald-700
                      hover:file:bg-emerald-100 file:cursor-pointer file:transition-colors
                      cursor-pointer border border-slate-200 rounded-xl bg-white">
        
        <div class="mt-1 text-xs text-slate-500">Định dạng: jpg, png, jpeg. Tối đa 2MB.</div>
        @error('logo')<div class="mt-1 text-sm text-rose-600 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror

        @if($brand?->logo_url)
            <div class="mt-3 flex items-center gap-3 p-2 border border-slate-200 rounded-xl bg-slate-50 w-fit pr-4">
                <img src="{{ asset('storage/' . $brand->logo_url) }}" 
                     alt="Current Logo" 
                     class="h-12 w-12 object-contain rounded-lg bg-white border border-slate-200 p-0.5">
                <div class="text-xs text-slate-500">
                    <div class="font-semibold text-slate-700">Logo hiện tại</div>
                    <div class="text-[10px] text-slate-400 mt-0.5">Tải ảnh mới để thay thế</div>
                </div>
            </div>
        @endif
    </div>

    {{-- Mô tả --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 placeholder:text-slate-400"
                  placeholder="Nhập mô tả ngắn về hãng sản phẩm...">{{ old('description', $brand?->description) }}</textarea>
    </div>

    {{-- Trạng thái --}}
    <div class="md:col-span-2 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-toggle-on text-xl"></i>
            </div>
            <div>
                <div class="text-sm font-semibold text-slate-700">Trạng thái hoạt động</div>
                <div class="text-xs text-slate-500">Bật tùy chọn này để hiển thị hãng trên website.</div>
            </div>
        </div>

        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                   {{ old('is_active', $brand?->is_active ?? true) ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
        </label>
    </div>
</div>