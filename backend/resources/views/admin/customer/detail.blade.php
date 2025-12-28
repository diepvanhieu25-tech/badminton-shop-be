@extends('layouts.admin')

@section('title', 'Admin - Chi tiết khách hàng')
@section('page_title', 'Chi tiết khách hàng: {{ $customer->name }}')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Thông tin chi tiết và lịch sử hoạt động của khách hàng</div>
    <div class="flex gap-3">
        <a href="/admin/customers/{{ $customer->id }}/edit" 
           class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-md">
            Sửa thông tin
        </a>
        <a href="/admin/customers" 
           class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
            ← Quay lại danh sách
        </a>
    </div>
</div>

<!-- Thông tin chính & Thống kê nhanh -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card thông tin khách hàng -->
    <div class="lg:col-span-1 bg-white rounded-xl border border-slate-200 p-6">
        <div class="text-center">
            <div class="w-32 h-32 mx-auto rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-4xl font-bold shadow-md mb-6">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>

            <h2 class="text-2xl font-bold text-slate-900 mb-2">{{ $customer->name }}</h2>
            <p class="text-sm text-slate-500 mb-6">ID: #{{ $customer->id }}</p>

            <div class="space-y-4 text-sm">
                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Email:</span>
                    <span class="font-medium">{{ $customer->email }}</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Số điện thoại:</span>
                    <span class="font-medium">{{ $customer->phone ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Ngày đăng ký:</span>
                    <span class="font-medium">{{ $customer->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-slate-600">Trạng thái:</span>
                    <x-badge 
                        text="{{ $customer->status == 'active' ? 'Active' : ($customer->status == 'inactive' ? 'Inactive' : 'VIP') }}" 
                        tone="{{ $customer->status == 'active' ? 'success' : ($customer->status == 'inactive' ? 'danger' : 'primary') }}" />
                </div>
            </div>

            <!-- Nút đổi mật khẩu (nếu cần) -->
            <div class="mt-8">
                <button onclick="document.getElementById('change-password-modal').classList.remove('hidden')"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                    Đổi mật khẩu khách hàng
                </button>
            </div>
        </div>
    </div>

    <!-- Thống kê nhanh -->
    <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-slate-900">{{ $customer->orders_count ?? 0 }}</div>
            <div class="text-sm text-slate-500 mt-1">Tổng đơn hàng</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">
                {{ number_format($customer->total_spent ?? 0) }}₫
            </div>
            <div class="text-sm text-slate-500 mt-1">Tổng chi tiêu</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-slate-900">
                {{ number_format($customer->average_order_value ?? 0) }}₫
            </div>
            <div class="text-sm text-slate-500 mt-1">Giá trị trung bình đơn</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center">
            <div class="text-3xl font-bold text-emerald-600">
                {{ $customer->last_order_days_ago ?? 'Chưa có' }}
            </div>
            <div class="text-sm text-slate-500 mt-1">Đơn hàng gần nhất</div>
        </div>
    </div>
</div>

<!-- Địa chỉ giao hàng & Lịch sử đơn hàng -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Địa chỉ giao hàng -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Địa chỉ giao hàng mặc định</h3>
        @if($customer->default_address)
        <div class="text-slate-700 space-y-2">
            <p><strong>Người nhận:</strong> {{ $customer->default_address->recipient_name }}</p>
            <p><strong>SĐT:</strong> {{ $customer->default_address->phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $customer->default_address->full_address }}</p>
            <p><strong>Ghi chú:</strong> {{ $customer->default_address->note ?? '-' }}</p>
        </div>
        @else
        <p class="text-slate-500 italic">Khách hàng chưa thiết lập địa chỉ giao hàng mặc định.</p>
        @endif
    </div>

    <!-- Lịch sử đơn hàng gần đây -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Đơn hàng gần đây</h3>
            <a href="/admin/orders?customer={{ $customer->id }}" class="text-sm text-emerald-600 hover:underline">
                Xem tất cả ({{ $customer->orders_count ?? 0 }}) →
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251226-001</div>
                    <div class="text-sm text-slate-600">26/12/2025 • 3 sản phẩm</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">2,850,000₫</div>
                    <x-badge text="Hoàn thành" tone="success" class="mt-1" />
                </div>
            </div>

            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251215-012</div>
                    <div class="text-sm text-slate-600">15/12/2025 • 1 sản phẩm</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">1,290,000₫</div>
                    <x-badge text="Đang giao" tone="primary" class="mt-1" />
                </div>
            </div>

            <div class="p-4 hover:bg-slate-50 flex items-center justify-between">
                <div>
                    <div class="font-medium text-emerald-700">#ORD-20251128-005</div>
                    <div class="text-sm text-slate-600">28/11/2025 • 2 sản phẩm</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold">4,120,000₫</div>
                    <x-badge text="Hoàn thành" tone="success" class="mt-1" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal đổi mật khẩu -->
<div id="change-password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl border border-slate-200 p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Đổi mật khẩu cho {{ $customer->name }}</h3>
        <form action="/admin/customers/{{ $customer->id }}/change-password" method="POST">
            @csrf
            @method('PATCH')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mật khẩu mới</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" required 
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('change-password-modal').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">
                    Hủy
                </button>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                    Cập nhật mật khẩu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection