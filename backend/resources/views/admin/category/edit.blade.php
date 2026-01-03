@extends('layouts.admin')

@section('title', 'Admin - Cập nhật danh mục')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="text-lg font-bold text-slate-900">Cập nhật danh mục</div>
        <div class="text-sm text-slate-500">Chỉnh sửa: <span class="font-semibold text-slate-700">{{ $category->name }}</span></div>
    </div>
    <a href="{{ route('admin.category.index') }}" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
        ← Quay lại
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    {{-- QUAN TRỌNG: enctype và method PUT --}}
    <form method="POST" action="{{ route('admin.category.update', $category) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                <input type="text" name="name" 
                       value="{{ old('name', $category->name) }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 @error('name') border-rose-500 @enderror">
                @error('name')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hình ảnh mới</label>
                    <input type="file" name="image_url" accept="image/*"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400">
                    <div class="mt-1 text-xs text-slate-500">Để trống nếu không muốn thay đổi ảnh.</div>
                </div>

                @if($category->image_url)
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Ảnh hiện tại</label>
                    <div class="relative w-24 h-24 rounded-lg border border-slate-200 overflow-hidden group">
                        <img src="{{ Storage::url($category->image_url) }}" class="w-full h-full object-cover">
                    </div>
                </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Trạng thái</label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $category->is_active) == 1 ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        <span>Active</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $category->is_active) == 0 ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        <span>Inactive</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition shadow-md">
                💾 Lưu thay đổi
            </button>
        </div>
    </form>

    <div class="p-6 border-t border-slate-200">
        <form method="POST" action="{{ route('admin.category.destroy', $category) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-50 text-rose-600 font-semibold hover:bg-rose-100 transition">
                🗑️ Xóa danh mục
            </button>
        </form>
    </div>
</div>
@endsection