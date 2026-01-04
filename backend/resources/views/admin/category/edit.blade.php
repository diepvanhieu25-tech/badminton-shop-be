@extends('layouts.admin')

@section('title', 'Admin - Cập nhật danh mục')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="text-xl font-bold text-slate-900">Cập nhật danh mục</div>
        <div class="text-sm text-slate-500">Chỉnh sửa thông tin: <span class="font-semibold text-emerald-700">{{ $category->name }}</span></div>
    </div>
    <a href="{{ route('admin.category.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition text-sm font-medium shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <form method="POST" action="{{ route('admin.category.update', $category) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tên danh mục <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-layer-group text-slate-400 text-xs"></i>
                    </div>
                    <input type="text" name="name" 
                           value="{{ old('name', $category->name) }}"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 transition @error('name') border-rose-500 @enderror">
                </div>
                @error('name')<div class="mt-1 text-sm text-rose-600 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Hình ảnh mới</label>
                    <input type="file" name="image_url" accept="image/*"
                           class="block w-full text-sm text-slate-500
                                  file:mr-4 file:py-2.5 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-emerald-50 file:text-emerald-700
                                  hover:file:bg-emerald-100
                                  cursor-pointer border border-slate-200 rounded-xl bg-white">
                    <div class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                        <i class="fa-solid fa-circle-info"></i> Để trống nếu không muốn thay đổi ảnh.
                    </div>
                </div>

                @if($category->image_url)
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ảnh hiện tại</label>
                    <div class="flex items-center gap-3 p-2 border border-slate-200 rounded-xl bg-slate-50 w-fit">
                        <img src="{{ Storage::url($category->image_url) }}" class="h-16 w-16 object-cover rounded-lg bg-white border border-slate-200">
                        <div class="text-xs text-slate-500 pr-2">
                            <div class="font-medium text-slate-700">Logo cũ</div>
                            <div>Sẽ bị thay thế nếu upload mới</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Trạng thái</label>
                <div class="flex gap-6 p-4 rounded-xl bg-slate-50 border border-slate-200 w-fit">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $category->is_active) == 1 ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm font-medium text-slate-700">Active</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $category->is_active) == 0 ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm font-medium text-slate-700">Inactive</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition shadow-md shadow-emerald-100">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
        </div>
    </form>
    
    <div class="p-6 pt-0 bg-slate-50">
        <div class="border-t border-slate-200 pt-6 flex justify-between items-center">
             <div>
                <div class="text-sm font-bold text-rose-600">Khu vực nguy hiểm</div>
                <div class="text-xs text-slate-500">Xóa danh mục này khỏi hệ thống.</div>
            </div>
            <form method="POST" action="{{ route('admin.category.destroy', $category) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-rose-200 text-rose-600 font-semibold hover:bg-rose-50 transition shadow-sm">
                    <i class="fa-regular fa-trash-can"></i> Xóa danh mục
                </button>
            </form>
        </div>
    </div>
</div>
@endsection