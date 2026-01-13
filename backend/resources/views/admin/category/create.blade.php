@extends('layouts.admin')

@section('title', 'Admin - Thêm danh mục')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="text-xl font-bold text-slate-900">Tạo danh mục mới</div>
        <div class="text-sm text-slate-500">Thêm nhóm sản phẩm mới vào cửa hàng.</div>
    </div>
    <a href="{{ route('admin.category.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition text-sm font-medium shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <form method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tên danh mục <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-layer-group text-slate-400 text-xs"></i>
                    </div>
                    <input type="text" 
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="VD: Yonex, Lining..."
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400 transition @error('name') border-rose-500 @enderror">
                </div>
                @error('name')<div class="mt-1 text-sm text-rose-600 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Hình ảnh</label>
                
                {{-- Input File --}}
                <input type="file" 
                       id="image_input"
                       name="image_url"
                       accept="image/*"
                       onchange="previewImage(event)"
                       class="block w-full text-sm text-slate-500
                              file:mr-4 file:py-2.5 file:px-4
                              file:rounded-xl file:border-0
                              file:text-sm file:font-semibold
                              file:bg-emerald-50 file:text-emerald-700
                              hover:file:bg-emerald-100
                              cursor-pointer border border-slate-200 rounded-xl bg-white">
                
                <div class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> Định dạng: JPG, PNG, GIF. Tối đa 2MB.
                </div>
                @error('image_url')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror

                {{-- Khung hiển thị ảnh Preview (Mặc định ẩn) --}}
                <div id="preview_container" class="hidden mt-4">
                    <label class="block text-xs font-semibold text-slate-500 mb-2">Ảnh xem trước:</label>
                    <div class="relative inline-block">
                        <img id="preview_img" src="" class="h-32 w-32 object-cover rounded-xl border border-slate-200 shadow-sm">
                        
                        {{-- Nút xóa ảnh đã chọn --}}
                        <button type="button" onclick="removePreview()" 
                                class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow-md border border-slate-200 text-slate-400 hover:text-rose-500 transition">
                            <i class="fa-solid fa-xmark text-sm w-4 h-4 flex items-center justify-center"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Trạng thái</label>
                <div class="flex items-center p-4 border border-slate-200 rounded-xl bg-slate-50">
                    <label class="flex items-center gap-3 cursor-pointer select-none w-full">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-200">
                        </div>
                        <span class="text-sm font-medium text-slate-700">Kích hoạt hiển thị ngay sau khi tạo</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">
            <a href="{{ route('admin.category.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition font-medium text-sm">
                Hủy bỏ
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition shadow-md shadow-emerald-100 text-sm">
                <i class="fa-solid fa-check"></i> Tạo danh mục
            </button>
        </div>
    </form>
</div>
@endsection

<script>
    function previewImage(event) {
        const input = event.target;
        const container = document.getElementById('preview_container');
        const img = document.getElementById('preview_img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePreview() {
        document.getElementById('image_input').value = '';
        document.getElementById('preview_container').classList.add('hidden');
        document.getElementById('preview_img').src = '';
    }
</script>