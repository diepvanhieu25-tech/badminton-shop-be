@php($brand = $brand ?? null)

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Tên hãng</label>
        <input name="name"
               value="{{ old('name', $brand?->name) }}"
               placeholder="VD: Yonex"
               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400">
        @error('name')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1">Logo URL</label>
        <input name="logo_url"
               value="{{ old('logo_url', $brand?->logo_url) }}"
               placeholder="https://..."
               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400">
        <div class="mt-1 text-xs text-slate-500">Nếu chưa có, có thể để trống.</div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả</label>
        <textarea name="description" rows="4"
                  class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400"
                  placeholder="Mô tả ngắn về hãng...">{{ old('description', $brand?->description) }}</textarea>
    </div>

    <div class="md:col-span-2 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
        <div>
            <div class="text-sm font-semibold text-slate-700">Trạng thái</div>
            <div class="text-xs text-slate-500">Bật/tắt hiển thị hãng trong hệ thống.</div>
        </div>

        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $brand?->is_active ?? true) ? 'checked' : '' }}
                   class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-200">
            <span class="text-sm font-medium text-slate-700">Active</span>
        </label>
    </div>
</div>
