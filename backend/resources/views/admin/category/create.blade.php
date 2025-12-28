@extends('layouts.admin')

@section('title', 'Admin - Thêm danh mục')
@section('page_title', 'Thêm danh mục mới')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Tạo danh mục sản phẩm mới cho cửa hàng cầu lông</div>
    <a href="/admin/category/index" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
        ← Quay lại danh sách
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <form action="/admin/categories" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="p-6 space-y-6">
            <!-- Tên danh mục & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition @error('name') border-red-500 @enderror"
                           placeholder="Ví dụ: Vợt cầu lông" />
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                
            </div>

           

            <!-- Icon hoặc Hình ảnh đại diện (tùy chọn) -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Hình ảnh đại diện (tùy chọn)</label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center">
                    <input type="file" name="image" accept="image/*"
                           class="hidden" id="category-image" />
                    <label for="category-image" class="cursor-pointer block">
                        <div class="text-4xl text-slate-400 mb-3">🖼️</div>
                        <p class="text-sm text-slate-600">Nhấn để tải lên ảnh đại diện</p>
                        <p class="text-xs text-slate-500 mt-1">JPG, PNG, tối đa 2MB</p>
                    </label>
                </div>
            </div>

            <!-- Mô tả -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Mô tả danh mục</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition @error('description') border-red-500 @enderror"
                          placeholder="Mô tả ngắn về danh mục này, hiển thị trên trang danh mục...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Trạng thái -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Trạng thái</label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500" />
                        <span>Active (Hiển thị trên website)</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="inactive" {{ old('status') == 'inactive' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500" />
                        <span>Inactive (Ẩn)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Nút hành động -->
        <div class="p-6 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">
            <a href="/admin/category/index"
               class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition">
                Hủy
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all shadow-md hover:shadow-lg">
                Tạo danh mục
            </button>
        </div>
    </form>
</div>
@endsection