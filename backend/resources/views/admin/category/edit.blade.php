@extends('layouts.admin')

@section('title', 'Cập nhật danh mục')

@section('content')
<div class="rounded-2xl bg-white border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between p-5 border-b border-slate-200">
        <div>
            <div class="text-lg font-bold text-slate-900">Cập nhật hãng</div>
            <div class="text-sm text-slate-500">Chỉnh sửa thông tin: <span class="font-semibold text-slate-700">{{ $category->name }}</span></div>
        </div>
        <a href="{{ route('admin.category.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            ← Quay lại
        </a>
    </div>

    <form method="POST" action="{{ route('admin.category.update', $category) }}" class="p-5 space-y-5">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <!-- Tên danh mục & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required 
                    value="{{ old('name', $category?->name) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition @error('name') border-red-500 @enderror"
                           placeholder="Ví dụ: Vợt cầu lông" />
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                
            </div>

           

            <!-- Icon hoặc Hình ảnh đại diện (tùy chọn) -->
             <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Image URL</label>
                <input name="image_url"
                    value="{{ old('image_url', $category?->image_url) }}"
                    placeholder="https://..."
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400">
                <div class="mt-1 text-xs text-slate-500">Nếu chưa có, có thể để trống.</div>
            </div>
 

            <!-- Trạng thái -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Trạng thái</label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="is_active" value="1" 
                            {{ old('is_active', $category->is_active ?? 1) == 1 ? 'checked' : '' }} 
                            class="text-emerald-600 focus:ring-emerald-500" />
                        <span>Active (Hiển thị trên website)</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="is_active" value="0" 
                            {{ old('is_active', $category->is_active ?? 1) == 0 ? 'checked' : '' }} 
                            class="text-emerald-600 focus:ring-emerald-500" />
                        <span>Inactive (Ẩn)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
            <a href="{{ route('admin.category.index') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Hủy</a>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 shadow-sm">💾 Lưu thay đổi</button>
        </div>
    </form>

    <div class="p-5 pt-0 border-t border-slate-200">
        <form method="POST" action="{{ route('admin.category.destroy', $category) }}"
              onsubmit="return confirm('Xóa hãng này? (soft delete)')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-700 shadow-sm">
                🗑️ Xóa hãng
            </button>
        </form>
    </div>
</div>
@endsection
