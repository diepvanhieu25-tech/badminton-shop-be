@extends('layouts.admin')

@section('title', 'Admin - Thêm hãng')
@section('page_title', 'Thêm hãng/thương hiệu mới')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Tạo hãng/thương hiệu sản phẩm mới cho cửa hàng cầu lông</div>
    <a href="/admin/brand/index" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
        ← Quay lại danh sách
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <form action="/admin/brands" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="p-6 space-y-6">
            <!-- Tên hãng & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tên hãng <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition @error('name') border-red-500 @enderror"
                           placeholder="Ví dụ: Yonex" />
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Slug (URL thân thiện)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition @error('slug') border-red-500 @enderror"
                           placeholder="ví dụ: yonex (tự động tạo nếu để trống)" />
                    <p class="mt-1 text-xs text-slate-500">Để trống để tự động tạo từ tên hãng</p>
                    @error('slug')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Quốc gia -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Quốc gia <span class="text-red-500">*</span></label>
                    <select name="country" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition @error('country') border-red-500 @enderror">
                        <option value="">-- Chọn quốc gia --</option>
                        <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Nhật Bản 🇯🇵</option>
                        <option value="Taiwan" {{ old('country') == 'Taiwan' ? 'selected' : '' }}>Đài Loan 🇹🇼</option>
                        <option value="China" {{ old('country') == 'China' ? 'selected' : '' }}>Trung Quốc 🇨🇳</option>
                        <option value="Malaysia" {{ old('country') == 'Malaysia' ? 'selected' : '' }}>Malaysia 🇲🇾</option>
                        <option value="Korea" {{ old('country') == 'Korea' ? 'selected' : '' }}>Hàn Quốc 🇰🇷</option>
                        <option value="Indonesia" {{ old('country') == 'Indonesia' ? 'selected' : '' }}>Indonesia 🇮🇩</option>
                        <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>Mỹ 🇺🇸</option>
                        <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>Anh 🇬🇧</option>
                        <option value="Other">Khác</option>
                    </select>
                    @error('country')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Website chính thức (tùy chọn)</label>
                    <input type="url" name="website" value="{{ old('website') }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                           placeholder="https://www.yonex.com" />
                </div>
            </div>

            <!-- Logo hãng -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Logo hãng <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center">
                    <input type="file" name="logo" accept="image/*" required
                           class="hidden" id="brand-logo" />
                    <label for="brand-logo" class="cursor-pointer block">
                        <div class="text-4xl text-slate-400 mb-3">🏢</div>
                        <p class="text-sm text-slate-600">Nhấn để tải lên logo hãng</p>
                        <p class="text-xs text-slate-500 mt-1">JPG, PNG, SVG khuyến nghị, tối đa 2MB</p>
                    </label>
                </div>
                @error('logo')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mô tả -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Mô tả hãng</label>
                <textarea name="description" rows="5"
                          class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition @error('description') border-red-500 @enderror"
                          placeholder="Giới thiệu ngắn về hãng: lịch sử, thế mạnh, sản phẩm nổi bật...">{{ old('description') }}</textarea>
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
            <a href="/admin/brand/index"
               class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition">
                Hủy
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-all shadow-md hover:shadow-lg">
                Tạo hãng mới
            </button>
        </div>
    </form>
</div>
@endsection