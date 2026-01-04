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
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                <tr>
                    <th class="py-4 px-6 w-20">ID</th>
                    <th class="py-4 px-6 w-32">Hình ảnh</th>
                    <th class="py-4 px-6">Tên danh mục</th>
                    <th class="py-4 px-6 w-40">Trạng thái</th>
                    <th class="py-4 px-6 text-right w-32">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($category as $item)
                    <tr class="hover:bg-slate-50 transition duration-150">
                        <td class="px-6 py-4 text-slate-500">#{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            @if($item->image_url)
                                <img src="{{ Storage::url($item->image_url) }}" 
                                     alt="{{ $item->name }}" 
                                     class="h-10 w-10 rounded-lg object-cover border border-slate-200 bg-white p-0.5">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-lg">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->name }}</td>
                        <td class="px-6 py-4">
                            @if($item->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <i class="fa-solid fa-circle text-[6px]"></i> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="fa-solid fa-circle text-[6px]"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.category.edit', $item) }}"
                               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-slate-200 text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition shadow-sm">
                                <i class="fa-regular fa-pen-to-square"></i> Sửa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-12 text-center text-slate-500" colspan="5">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-regular fa-folder-open text-4xl text-slate-300"></i>
                                <p>Chưa có danh mục nào được tạo.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-slate-200 bg-slate-50">
        {{ $category->withQueryString()->links() }}
    </div>
</div>
@endsection