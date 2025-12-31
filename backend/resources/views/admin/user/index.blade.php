@extends('layouts.admin')

@section('title', 'Admin - Khách hàng')
@section('page_title', 'Quản lý khách hàng')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Danh sách khách hàng đã đăng ký và mua hàng</div>
    <div class="flex gap-3">
        <input type="text"
               class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
               placeholder="Tìm khách hàng..." />

    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <!-- Bộ lọc nhanh -->
    <div class="p-4 border-b border-slate-200 flex flex-wrap gap-3">
        <select class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500">
            <option>Tất cả khách hàng</option>
            <option>Đã mua hàng</option>
             <option>Chưa mua hàng</option>
        </select>

        <select class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500">
            <option>Ngày đăng ký: Mới nhất</option>
            <option>Ngày đăng ký: Cũ nhất</option>
            <option>Tổng chi tiêu: Cao → Thấp</option>
            <option>Tổng chi tiêu: Thấp → Cao</option>
        </select>
    </div>

    <!-- Bảng danh sách khách hàng -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6 text-left font-medium">Khách hàng</th>
                    <th class="py-4 px-6 text-left font-medium">Ten</th>
                    <th class="py-4 px-6 text-left font-medium">Email</th>
                    <th class="py-4 px-6 text-left font-medium">Số điện thoại</th>
                    <!-- <th class="py-4 px-6 text-left font-medium">Tổng đơn hàng</th>
                    <th class="py-4 px-6 text-left font-medium">Tổng chi tiêu</th> -->
                    <th class="py-4 px-6 text-left font-medium">Trạng thái</th>
                    <th class="py-4 px-6 text-right font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($user as $b)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $b->avatar_url }}</td>
                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $b->name }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $b->email }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $b->phone }}</td>
                        <td class="px-5 py-3">
                            @if($b->status)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">● Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">● Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.user.edit', $b) }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                ✏️ Sửa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-5 py-10 text-center text-slate-500" colspan="5">Không có dữ liệu.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="p-4 border-t border-slate-200 text-sm text-slate-500 flex items-center justify-between">
        <div>Hiển thị 1-20 của 156 khách hàng</div>
        <div class="flex gap-2">
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">Trước</button>
            <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white">1</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">2</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">3</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">...</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">8</button>
            <button class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50">Sau</button>
        </div>
    </div>
</div>
@endsection