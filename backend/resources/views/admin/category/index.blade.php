@extends('layouts.admin')

@section('title', 'Admin - Quản lý danh mục')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Quản lý danh mục sản phẩm</div>
    <a href="{{ route('admin.category.create') }}" class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-md">
        + Thêm danh mục
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-4 border-b border-slate-200">
        <form action="{{ route('admin.category.index') }}" method="GET" class="flex gap-3 max-w-lg">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="flex-1 px-4 py-2.5 rounded-lg border border-slate-300 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                   placeholder="Tìm kiếm danh mục...">
            <button type="submit" class="px-4 py-2.5 rounded-lg bg-slate-800 text-white hover:bg-slate-900 font-medium transition">
                Tìm
            </button>
            @if(request('search'))
                <a href="{{ route('admin.category.index') }}" class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium transition">
                    Xóa
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600 uppercase font-medium">
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
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-500">{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            @if($item->image_url)
                                {{-- QUAN TRỌNG: Sử dụng Storage::url() --}}
                                <img src="{{ Storage::url($item->image_url) }}" 
                                     alt="{{ $item->name }}" 
                                     class="h-10 w-10 rounded-lg object-cover border border-slate-200 bg-slate-100">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                    No img
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->name }}</td>
                        <td class="px-6 py-4">
                            @if($item->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    ● Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                    ● Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.category.edit', $item) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                                ✏️ Sửa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-8 text-center text-slate-500" colspan="5">
                            Chưa có danh mục nào được tạo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-slate-200">
        {{ $category->withQueryString()->links() }}
    </div>
</div>
@endsection