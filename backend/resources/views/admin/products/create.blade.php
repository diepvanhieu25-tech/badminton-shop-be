@extends('layouts.admin')

@section('title', 'Admin - Thêm sản phẩm')
@section('page_title', 'Thêm sản phẩm mới')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Tạo sản phẩm mới cho cửa hàng</div>
    <a href="/admin/products" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
        ← Quay lại danh sách
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <form action="/admin/products" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="p-6 space-y-6">
            <!-- Tên sản phẩm & SKU -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                           placeholder="Ví dụ: Vợt cầu lông Yonex Astrox 99 Pro" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mã SKU <span class="text-red-500">*</span></label>
                    <input type="text" name="sku" required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                           placeholder="Ví dụ: YONEX-A99-001" />
                </div>
            </div>

            <!-- Giá bán & Giá gốc (nếu có khuyến mãi) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Giá bán (₫) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required min="0"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                           placeholder="1200000" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Giá gốc (nếu đang giảm giá)</label>
                    <input type="number" name="original_price" min="0"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                           placeholder="1500000" />
                </div>
            </div>

            <!-- Danh mục & Tồn kho -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition">
                        <option value="">-- Chọn danh mục --</option>
                        <option value="1">Vợt cầu lông</option>
                        <option value="2">Giày cầu lông</option>
                        <option value="3">Áo cầu lông</option>
                        <option value="4">Quần cầu lông</option>
                        <option value="5">Phụ kiện</option>
                        <option value="6">Túi đựng vợt</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Số lượng tồn kho <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" required min="0" value="0"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                           placeholder="0" />
                </div>
            </div>

            <!-- Trạng thái -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Trạng thái</label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="active" checked class="text-emerald-600 focus:ring-emerald-500" />
                        <span>Active (Hiển thị trên web)</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="inactive" class="text-emerald-600 focus:ring-emerald-500" />
                        <span>Inactive (Ẩn)</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="status" value="draft" class="text-emerald-600 focus:ring-emerald-500" />
                        <span>Draft (Nháp)</span>
                    </label>
                </div>
            </div>

            <!-- Hình ảnh sản phẩm -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Hình ảnh sản phẩm</label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center">
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="hidden" id="product-images" />
                    <label for="product-images" class="cursor-pointer">
                        <div class="text-4xl text-slate-400 mb-3">📸</div>
                        <p class="text-sm text-slate-600">Nhấn để tải lên nhiều ảnh</p>
                        <p class="text-xs text-slate-500 mt-1">Hỗ trợ JPG, PNG, tối đa 5MB/ảnh</p>
                    </label>
                </div>
            </div>

            <!-- Mô tả ngắn & Mô tả chi tiết -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Mô tả ngắn</label>
                <textarea name="short_description" rows="3"
                          class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                          placeholder="Mô tả ngắn gọn hiển thị ở danh sách sản phẩm..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Mô tả chi tiết</label>
                <textarea name="description" rows="8"
                          class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                          placeholder="Thông tin chi tiết về sản phẩm: chất liệu, công nghệ, ưu điểm..."></textarea>
            </div>
        </div>

        <!-- Nút hành động -->
        <div class="p-6 border-t border-slate-200 flex justify-end gap-3">
            <a href="/admin/products"
               class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                Hủy
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition shadow-md">
                Tạo sản phẩm
            </button>
        </div>
    </form>
</div>
@endsection