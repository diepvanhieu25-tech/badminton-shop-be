@extends('layouts.admin')

@section('title', 'Admin - Hồ sơ khách hàng')
@section('page_title', 'Hồ sơ khách hàng')

@section('content')
    {{-- 1. Top Navigation --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.user.index') }}"
            class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 transition font-medium">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
        </a>
        
    </div>

    {{-- 2. Main Content Wrapper --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Avatar & Basic Info --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden h-full">
                <div class="p-6 flex flex-col items-center text-center border-b border-slate-100">
                    <div class="relative mb-4">
                        @if ($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                class="h-28 w-28 rounded-full object-cover border-4 border-slate-50 shadow-sm">
                        @else
                            <div
                                class="h-28 w-28 rounded-full bg-slate-800 text-white flex items-center justify-center text-4xl font-bold border-4 border-slate-50 shadow-sm">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="absolute bottom-1 right-1 bg-white rounded-full p-1 shadow-sm">
                            @if ($user->status)
                                <div class="bg-emerald-500 w-4 h-4 rounded-full border-2 border-white" title="Active"></div>
                            @else
                                <div class="bg-slate-400 w-4 h-4 rounded-full border-2 border-white" title="Inactive"></div>
                            @endif
                        </div>
                    </div>
                    
                    <h2 class="text-xl font-bold text-slate-900 mb-1">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 mb-4">{{ $user->email }}</p>
                    
                    <div class="w-full flex justify-center gap-2">
                         @if ($user->status)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đang hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Đã khóa
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-5">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Thông tin liên hệ</h3>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-phone text-xs"></i>
                            </div>
                            <div>
                                <span class="block text-slate-500 text-xs mb-0.5">Điện thoại</span>
                                <span class="font-medium text-slate-800">{{ $user->phone ?? '---' }}</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-location-dot text-xs"></i>
                            </div>
                            <div>
                                <span class="block text-slate-500 text-xs mb-0.5">Địa chỉ giao hàng</span>
                                <span class="font-medium text-slate-800 leading-relaxed">{{ $user->address ?? 'Chưa cập nhật' }}</span>
                            </div>
                        </li>
                         <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-calendar text-xs"></i>
                            </div>
                            <div>
                                <span class="block text-slate-500 text-xs mb-0.5">Ngày tham gia</span>
                                <span class="font-medium text-slate-800">{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right Column: Shopping Overview Stats --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Stats Cards Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Card 1: Tổng đơn hàng --}}
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 font-medium mb-1">Tổng đơn hàng</div>
                        <div class="text-2xl font-bold text-slate-800">{{ $user->orders_count ?? 0 }}</div>
                        <div class="text-xs text-slate-400 mt-1">đơn hàng đã đặt</div>
                    </div>
                </div>

                {{-- Card 2: Tổng chi tiêu --}}
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div>
                        <div class="text-sm text-slate-500 font-medium mb-1">Tổng chi tiêu</div>
                        <div class="text-2xl font-bold text-emerald-600">
                             {{ number_format($user->lifetime_spent ?? 0, 0, ',', '.') }}đ
                        </div>
                        <div class="text-xs text-slate-400 mt-1">doanh thu trọn đời</div>
                    </div>
                </div>
            </div>

             <div class="bg-slate-50 rounded-xl border border-dashed border-slate-300 p-8 flex flex-col items-center justify-center text-center h-[calc(100%-110px)] min-h-[200px]">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm">
                    <i class="fa-solid fa-chart-pie text-slate-300 text-2xl"></i>
                </div>
                <h3 class="text-slate-900 font-medium mb-1">Thống kê mua sắm</h3>
                <p class="text-sm text-slate-500 max-w-xs">
                    Khu vực này có thể hiển thị biểu đồ phân bổ chi tiêu hoặc danh mục yêu thích của khách hàng trong tương lai.
                </p>
            </div>
        </div>
    </div>
@endsection