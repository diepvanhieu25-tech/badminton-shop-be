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
    <form method="POST" action="{{ route('admin.category.store') }}"  >
        @csrf
        @php($category = $category ?? null)
        <div class="p-6 space-y-6">
            <!-- Tên danh mục & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                    <input name="name"
                        value="{{ old('name', $category?->name) }}"
                        placeholder="VD: Yonex"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400">
                    @error('name')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                </div>

                
            </div>

           

            
             <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Logo URL</label>
                <input name="image"
                    value="{{ old('image', $category?->image) }}"
                    placeholder="https://..."
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400">
                <div class="mt-1 text-xs text-slate-500">Nếu chưa có, có thể để trống.</div>
            </div>
 

            <!-- Trạng thái -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Trạng thái</label>
                <div class="flex gap-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }}
                            class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-200">
                        <span class="text-sm font-medium text-slate-700">Active</span>
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