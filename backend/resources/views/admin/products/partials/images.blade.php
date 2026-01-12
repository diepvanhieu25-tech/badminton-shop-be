<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">Thư viện ảnh</h3>

    <div class="space-y-4">
        
        {{-- 1. DANH SÁCH ẢNH HIỆN CÓ (CHẾ ĐỘ EDIT) --}}
        @if($isEdit && $product->images->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($product->images as $index => $img)
                    <div class="group relative aspect-square bg-slate-100 rounded-lg overflow-hidden border border-slate-200"
                         id="image-container-{{ $img->id }}">
                        
                        {{-- Ảnh --}}
                        <img src="{{ Storage::url($img->image_url) }}" 
                             class="w-full h-full object-cover transition-opacity duration-300"
                             id="img-preview-{{ $img->id }}">

                        {{-- Overlay Gradient --}}
                        <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        {{-- Nút Xóa (Checkbox ẩn) --}}
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <label class="cursor-pointer flex items-center justify-center w-8 h-8 bg-white/90 hover:bg-red-500 hover:text-white rounded-full shadow-sm text-slate-600 transition-all"
                                   title="Xóa ảnh này">
                                
                                <input type="checkbox" 
                                       name="existing_images[{{ $index }}][_delete]" 
                                       value="1" 
                                       class="hidden delete-checkbox"
                                       data-target="img-preview-{{ $img->id }}">
                                
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </label>
                            
                            {{-- ID ảnh giữ nguyên --}}
                            <input type="hidden" name="existing_images[{{ $index }}][id]" value="{{ $img->id }}">
                        </div>

                        {{-- Badge: Đã chọn xóa --}}
                        <div class="absolute inset-0 bg-red-50/80 items-center justify-center border-2 border-red-500 hidden deleted-badge">
                            <span class="text-red-600 font-bold text-sm uppercase">Sẽ xóa</span>
                            <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 undo-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- 2. UPLOAD ẢNH MỚI --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Tải lên ảnh mới</label>
            
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100 hover:border-blue-400 transition-all">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-2 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="text-sm text-slate-500"><span class="font-semibold text-blue-600">Click để tải ảnh</span> hoặc kéo thả vào đây</p>
                        <p class="text-xs text-slate-400 mt-1">SVG, PNG, JPG (Tối đa 5MB)</p>
                    </div>
                    <input id="dropzone-file" type="file" name="images[]" multiple class="hidden" />
                </label>
            </div>
            {{-- Preview ảnh mới upload (Optional - cần JS thêm nếu muốn hiển thị ngay) --}}
            <div id="new-images-preview" class="grid grid-cols-4 gap-4 mt-4"></div>
        </div>
    </div>
</div>

{{-- SCRIPT NHỎ XỬ LÝ UI XÓA ẢNH --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Xử lý hiệu ứng khi tick vào nút xóa ảnh cũ
        document.querySelectorAll('.delete-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const container = this.closest('.relative');
                const badge = container.querySelector('.deleted-badge');
                
                if (this.checked) {
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }
            });
        });

        // Nút Undo (Hủy xóa)
        document.querySelectorAll('.undo-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const container = this.closest('.relative');
                const checkbox = container.querySelector('.delete-checkbox');
                checkbox.checked = false;
                checkbox.dispatchEvent(new Event('change')); // Trigger sự kiện change ở trên
            });
        });

        // (Optional) Preview ảnh mới khi chọn file
        const fileInput = document.getElementById('dropzone-file');
        const previewContainer = document.getElementById('new-images-preview');

        if(fileInput && previewContainer) {
            fileInput.addEventListener('change', function(e) {
                previewContainer.innerHTML = ''; // Clear cũ
                const files = e.target.files;
                
                if(files.length > 0) {
                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'aspect-square rounded-lg border overflow-hidden relative';
                            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                            previewContainer.appendChild(div);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });
        }
    });
</script>