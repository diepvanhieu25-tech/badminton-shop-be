@extends('layouts.admin')

@section('title', 'Admin - Danh sách khách hàng')
@section('page_title', 'Quản lý khách hàng')

@section('content')
{{-- 1. Header & Actions --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Khách hàng</h1>
        <p class="text-sm text-slate-500 mt-1">Danh sách tất cả khách hàng đã đăng ký hệ thống.</p>
    </div>

    <div class="flex gap-2">
        <form action="{{ route('admin.user.index') }}" method="GET" class="relative group">
            <div class="flex items-center">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-emerald-500 transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="pl-10 pr-10 py-2.5 rounded-l-lg border border-slate-300 bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 w-64 transition sm:text-sm"
                           placeholder="Tên, email hoặc SĐT..." />
                    
                    @if(request('search'))
                        <a href="{{ route('admin.user.index') }}" 
                           class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-red-500 cursor-pointer transition"
                           title="Xóa tìm kiếm">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white font-medium rounded-r-lg hover:bg-slate-700 transition shadow-sm text-sm">
                    Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 2. Table Section --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs border-b border-slate-200">
                <tr>
                    <th class="py-4 px-6 w-16">Avatar</th>
                    <th class="py-4 px-6">Thông tin cá nhân</th>
                    <th class="py-4 px-6">Liên hệ</th>
                    <th class="py-4 px-6 text-center">Trạng thái</th>
                    <th class="py-4 px-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                {{-- SỬA: Đổi $user thành $users --}}
                @forelse($users as $item) 
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        {{-- Avatar --}}
                        <td class="px-6 py-4">
                            @if($item->avatar_url)
                                <img src="{{ $item->avatar_url }}" alt="{{ $item->name }}" class="h-10 w-10 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="h-10 w-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm border border-emerald-200">
                                    {{ substr($item->name, 0, 1) }}
                                </div>
                            @endif
                        </td>

                        {{-- Thông tin --}}
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.user.show', $item) }}" class="font-medium text-slate-900 hover:text-emerald-600 transition block mb-0.5">
                                {{ $item->name }}
                            </a>
                            <span class="text-xs text-slate-500">Tham gia: {{ $item->created_at->format('d/m/Y') }}</span>
                        </td>

                        {{-- Liên hệ --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-regular fa-envelope text-slate-400 text-xs w-4"></i> {{ $item->email }}
                                </span>
                                <span class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-phone text-slate-400 text-xs w-4"></i> {{ $item->phone ?? '---' }}
                                </span>
                            </div>
                        </td>

                        {{-- Trạng thái --}}
                        <td class="px-6 py-4 text-center">
                            @if($item->status)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                </span>
                            @endif
                        </td>

                        {{-- Hành động --}}
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.user.show', $item) }}" 
                               class="text-slate-400 hover:text-emerald-600 p-2 transition-colors rounded-lg hover:bg-emerald-50 inline-block" 
                               title="Xem chi tiết">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-12 text-center text-slate-500" colspan="5">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                                </div>
                                <p class="font-medium text-slate-900">Không tìm thấy khách hàng</p>
                                <p class="text-sm mt-1">Thử thay đổi từ khóa tìm kiếm của bạn.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination: SỬA ĐỔI biến $user thành $users --}}
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $users->links() }} 
        </div>
    @endif
</div>
@endsection