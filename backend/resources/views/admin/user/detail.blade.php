@extends('layouts.admin')

@section('title', 'Admin - Chi tiết khách hàng')
@section('page_title', 'Chi tiết khách hàng')

@section('content')
<!-- Header với nút quay lại -->
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.user.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium transition">
            ← Quay lại
        </a>
        <div>
            <div class="text-sm text-slate-500">Thông tin chi tiết về khách hàng</div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.user.edit', $user) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-medium transition">
            ✏️ Chỉnh sửa
        </a>
        <form action="{{ route('admin.user.destroy', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-red-600 text-white hover:bg-red-700 font-medium transition">
                🗑️ Xóa
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Cột trái: Thông tin chính -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Thông tin cá nhân -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-lg font-semibold text-slate-900">Thông tin cá nhân</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Họ và tên</label>
                        <div class="mt-1 text-slate-900 font-semibold">{{ $user->name }}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-slate-500">Email</label>
                        <div class="mt-1 text-slate-900">{{ $user->email }}</div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-500">Số điện thoại</label>
                        <div class="mt-1 text-slate-900">{{ $user->phone ?? '—' }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-500">Địa chỉ</label>
                    <div class="mt-1 text-slate-900">{{ $user->address ?? '—' }}</div>
                </div>
            </div>
        </div>

        <!-- Lịch sử đơn hàng -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Lịch sử đơn hàng</h3>
                <span class="text-sm text-slate-500">{{ $user->orders->count() ?? 0 }} đơn hàng</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr class="border-b border-slate-200">
                            <th class="py-3 px-6 text-left font-medium">Mã đơn</th>
                            <th class="py-3 px-6 text-left font-medium">Ngày đặt</th>
                            <th class="py-3 px-6 text-left font-medium">Tổng tiền</th>
                            <th class="py-3 px-6 text-left font-medium">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($user->orders ?? [] as $order)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 font-semibold text-slate-900">#{{ $order->id }}</td>
                                <td class="px-6 py-3 text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-3 text-slate-900 font-semibold">{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}đ</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                        {{ $order->status ?? 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-slate-500" colspan="4">Chưa có đơn hàng nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cột phải: Thông tin tổng quan -->
    <div class="space-y-6">
        <!-- Avatar & Trạng thái -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex flex-col items-center text-center">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full object-cover border-4 border-slate-200 mb-4">
                @else
                    <div class="h-24 w-24 rounded-full bg-slate-200 flex items-center justify-center mb-4 text-3xl font-bold text-slate-500">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                
                <h3 class="text-lg font-semibold text-slate-900 mb-1">{{ $user->name }}</h3>
                <p class="text-sm text-slate-500 mb-3">{{ $user->email }}</p>
                
                @if($user->status)
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                        ● Active
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                        ● Inactive
                    </span>
                @endif
            </div>
        </div>

       
    </div>
</div>
@endsection