@extends('layouts.admin')

@section('title', 'Admin - Chi tiết khách hàng')
@section('page_title', 'Chi tiết khách hàng')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.user.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
        <div>
            <div class="text-sm text-slate-500">Thông tin chi tiết về khách hàng</div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.user.edit', $user) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-medium transition shadow-sm">
            <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa
        </a>
        <form action="{{ route('admin.user.destroy', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 font-medium transition">
                <i class="fa-regular fa-trash-can"></i> Xóa
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
                <i class="fa-regular fa-id-card text-slate-400"></i>
                <h3 class="text-lg font-semibold text-slate-900">Thông tin cá nhân</h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Họ và tên</label>
                        <div class="text-slate-900 font-medium text-lg">{{ $user->name }}</div>
                    </div>
                    <div>
                         <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Trạng thái</label>
                         @if($user->status)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                <i class="fa-solid fa-check-circle"></i> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                                <i class="fa-solid fa-ban"></i> Inactive
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Email</label>
                            <div class="text-slate-900">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                         <div class="mt-1 w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Số điện thoại</label>
                            <div class="text-slate-900">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4">
                     <div class="flex items-start gap-3">
                        <div class="mt-1 w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500">Địa chỉ</label>
                            <div class="text-slate-900">{{ $user->address ?? 'Chưa cập nhật địa chỉ' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                    <h3 class="text-lg font-semibold text-slate-900">Lịch sử đơn hàng</h3>
                </div>
                <span class="bg-slate-200 text-slate-700 text-xs px-2.5 py-1 rounded-full font-bold">{{ $user->orders->count() ?? 0 }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                        <tr class="border-b border-slate-200">
                            <th class="py-3 px-6">Mã đơn</th>
                            <th class="py-3 px-6">Ngày đặt</th>
                            <th class="py-3 px-6">Tổng tiền</th>
                            <th class="py-3 px-6">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($user->orders ?? [] as $order)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3 font-semibold text-emerald-600">
                                    <a href="#">#{{ $order->id }}</a>
                                </td>
                                <td class="px-6 py-3 text-slate-500">
                                    <i class="fa-regular fa-calendar mr-1"></i> {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-3 text-slate-900 font-semibold">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</td>
                                <td class="px-6 py-3">
                                    @php
                                        $statusClass = match($order->status ?? 'pending') {
                                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-slate-50 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold border {{ $statusClass }}">
                                        {{ ucfirst($order->status ?? 'Pending') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-12 text-center text-slate-500" colspan="4">
                                    <i class="fa-solid fa-cart-arrow-down text-3xl text-slate-300 mb-2 block"></i>
                                    Chưa có đơn hàng nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <div class="flex flex-col items-center text-center">
                <div class="relative mb-4">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-28 w-28 rounded-full object-cover border-4 border-slate-100 shadow-sm">
                    @else
                        <div class="h-28 w-28 rounded-full bg-slate-100 flex items-center justify-center text-4xl font-bold text-slate-400 border-4 border-slate-50">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="absolute bottom-1 right-1 bg-white rounded-full p-1">
                        @if($user->status)
                            <div class="bg-emerald-500 w-4 h-4 rounded-full border-2 border-white" title="Active"></div>
                        @else
                            <div class="bg-slate-400 w-4 h-4 rounded-full border-2 border-white" title="Inactive"></div>
                        @endif
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $user->name }}</h3>
                <p class="text-sm text-slate-500 mb-4 flex items-center gap-1">
                    <i class="fa-solid fa-at text-xs"></i> {{ $user->email }}
                </p>
                
                <div class="w-full border-t border-slate-100 pt-4 flex justify-center gap-4">
                    <div class="text-center">
                        <div class="text-xs text-slate-400 uppercase font-bold">Ngày tham gia</div>
                        <div class="text-sm font-medium text-slate-700 mt-1">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y') : '---' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection