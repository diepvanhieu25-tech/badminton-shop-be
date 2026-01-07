@extends('layouts.admin')

@section('title', 'Admin - Khách hàng')
@section('page_title', 'Quản lý khách hàng')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Danh sách khách hàng đã đăng ký và mua hàng</div>
    <div class="flex gap-9">
        <form action="{{ route('admin.user.index') }}" method="GET" class="flex gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition w-64"
                    placeholder="Tìm khách hàng..." />
            </div>
            
            <button type="submit"
                    class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-medium transition shadow-sm flex items-center gap-2">
                <span>Tìm kiếm</span>
            </button>
            
            @if(request('search'))
                <a href="{{ route('admin.user.index') }}"
                class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium transition flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Xóa lọc
                </a>
            @endif
        </form>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-200 flex flex-wrap gap-3 bg-slate-50/50">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                <i class="fa-solid fa-filter text-xs"></i>
            </span>
            <!-- <select class="pl-9 pr-8 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500 text-sm focus:ring-2 focus:ring-emerald-200 transition cursor-pointer">
                <option>Tất cả khách hàng</option>
                <option>Đã mua hàng</option>
                <option>Chưa mua hàng</option>
            </select> -->
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6">Khách hàng</th>
                    <th class="py-4 px-6">Tên hiển thị</th>
                    <th class="py-4 px-6">Email</th>
                    <th class="py-4 px-6">Số điện thoại</th>
                    <th class="py-4 px-6">Trạng thái</th> 
                    <th class="py-4 px-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($user as $b)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            @if($b->avatar_url)
                                <img src="{{ $b->avatar_url }}" alt="{{ $b->name }}" class="h-10 w-10 rounded-full object-cover border border-slate-200">
                            @else
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-900">
                            <a href="{{ route('admin.user.show', $b) }}" class="hover:text-emerald-600 transition">{{ $b->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $b->email }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $b->phone ?? '---' }}</td>
                        <td class="px-6 py-4">
                            @if($b->status)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-circle text-[6px]"></i> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                                    <i class="fa-solid fa-circle text-[6px]"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.user.show', $b) }}"
                                class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"> </i>  Xem thông tin
                                </a>
                                <!-- <a href="{{ route('admin.user.edit', $b) }}"
                                class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition" title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a> -->
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-10 text-center text-slate-500" colspan="6">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-3"></i>
                                <p>Không tìm thấy dữ liệu phù hợp.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection