@extends('layouts.admin')

@section('title', 'Admin - Quản lý danh mục')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="text-xl font-bold text-slate-900">Danh mục sản phẩm</div>
        <div class="text-sm text-slate-500">Quản lý các nhóm sản phẩm trong hệ thống.</div>
    </div>
    <a href="{{ route('admin.category.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-md shadow-emerald-100">
        <i class="fa-solid fa-plus"></i> Thêm danh mục
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-200">
        <form action="{{ route('admin.category.index') }}" method="GET" class="flex gap-3 max-w-lg">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition text-sm"
                       placeholder="Tìm kiếm danh mục...">
            </div>
            
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 text-white hover:bg-slate-900 font-medium transition text-sm">
                Tìm kiếm
            </button>
            
            @if(request('search'))
                <a href="{{ route('admin.category.index') }}" class="inline-flex items-center justify-center w-10 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-500 transition" title="Xóa lọc">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                <tr>
                    <th class="py-4 px-6 text-left w-20">ID</th>
                    <th class="py-4 px-6 text-center w-32">Hình ảnh</th>
                    <th class="py-4 px-6 text-center">Tên danh mục</th>
                    <th class="py-4 px-6 text-center w-40">Trạng thái</th>
                    <th class="py-4 px-6 text-center w-52">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($category as $item)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-6 text-slate-500 font-medium align-middle">#{{ $item->id }}</td>
                        <td class="px-6 py-6 align-middle">
                            <div class="flex justify-center">
                                @if($item->image_url)
                                    <img src="{{ Storage::url($item->image_url) }}" 
                                         alt="{{ $item->name }}" 
                                         class="h-20 w-20 rounded-xl object-cover border-2 border-slate-200 bg-white shadow-sm">
                                @else
                                    <div class="h-20 w-20 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 border-2 border-slate-200 flex items-center justify-center text-slate-400 text-2xl">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-6 font-semibold text-slate-900 align-middle text-center">{{ $item->name }}</td>
                        <td class="px-6 py-6 text-center align-middle">
                            @if($item->is_active)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-circle text-[6px]"></i> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="fa-solid fa-circle text-[6px]"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-6 align-middle">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.category.edit', $item) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium bg-white border border-slate-300 text-slate-700 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow"
                                   title="Chỉnh sửa danh mục">
                                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    <span>Sửa</span>
                                </a>
                                
                                <button type="button"
                                        onclick="confirmDelete{{ $item->id }}()"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium bg-white border border-slate-300 text-slate-700 hover:bg-red-50 hover:text-red-700 hover:border-red-300 transition-all duration-200 shadow-sm hover:shadow"
                                        title="Xóa danh mục">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                    <span>Xóa</span>
                                </button>
                                
                                <form id="delete-form-{{ $item->id }}" 
                                      action="{{ route('admin.category.destroy', $item) }}" 
                                      method="POST" 
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-16 text-center text-slate-500" colspan="5">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fa-regular fa-folder-open text-3xl text-slate-300"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700 mb-1">Chưa có danh mục nào</p>
                                    <p class="text-sm text-slate-500">Bắt đầu bằng cách tạo danh mục đầu tiên của bạn.</p>
                                </div>
                                <a href="{{ route('admin.category.create') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition mt-2">
                                    <i class="fa-solid fa-plus"></i> Thêm danh mục
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($category->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $category->withQueryString()->links() }}
        </div>
    @endif
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in-up">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in-up">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    </div>
@endif

<!-- Modal Xác nhận Xóa -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fa-solid fa-triangle-exclamation text-3xl text-red-600"></i>
            </div>
            
            <h3 class="text-xl font-bold text-slate-900 text-center mb-2">Xác nhận xóa danh mục</h3>
            <p class="text-slate-600 text-center mb-6">
                Bạn có chắc chắn muốn xóa danh mục <span id="categoryName" class="font-semibold text-slate-900"></span>?
                <br><span class="text-sm text-red-600 font-medium">Hành động này không thể hoàn tác!</span>
            </p>
            
            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2.5 rounded-xl border-2 border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition-all duration-200">
                    <i class="fa-solid fa-xmark mr-1"></i> Hủy bỏ
                </button>
                <button type="button" 
                        onclick="submitDelete()"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition-all duration-200 shadow-lg shadow-red-200">
                    <i class="fa-solid fa-trash-can mr-1"></i> Xóa ngay
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentDeleteFormId = null;

@foreach($category as $item)
function confirmDelete{{ $item->id }}() {
    currentDeleteFormId = 'delete-form-{{ $item->id }}';
    document.getElementById('categoryName').textContent = '{{ $item->name }}';
    document.getElementById('deleteModal').classList.remove('hidden');
}
@endforeach

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentDeleteFormId = null;
}

function submitDelete() {
    if (currentDeleteFormId) {
        document.getElementById(currentDeleteFormId).submit();
    }
}

// Đóng modal khi click bên ngoài
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Đóng modal khi nhấn ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// Auto hide flash messages
setTimeout(function() {
    const alerts = document.querySelectorAll('[class*="animate-fade-in-up"]');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 3000);
</script>
@endsection