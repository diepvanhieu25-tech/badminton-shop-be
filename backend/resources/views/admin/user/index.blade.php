@extends('layouts.admin')

@section('title', 'Admin - Khách hàng')
@section('page_title', 'Quản lý khách hàng')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-slate-500">Danh sách khách hàng đã đăng ký và mua hàng</div>
    <div class="flex gap-9">
        <form action="{{ route('admin.user.index') }}" method="GET" class="flex gap-3">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
                placeholder="Tìm khách hàng..." />
            
            <button type="submit"
                    class="px-4 py-2.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-medium transition">
                🔍 Tìm
            </button>
            
            @if(request('search'))
                <a href="{{ route('admin.user.index') }}"
                class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-medium transition">
                    ✕ Xóa
                </a>
            @endif
        </form>
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
 
    </div>

    <!-- Bảng danh sách khách hàng -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="py-4 px-6 text-left font-medium">Khách hàng</th>
                    <th class="py-4 px-6 text-left font-medium">Tên</th>
                    <th class="py-4 px-6 text-left font-medium">Email</th>
                    <th class="py-4 px-6 text-left font-medium">Số điện thoại</th>
                    <th class="py-4 px-6 text-left font-medium">Trạng thái</th> 
                    <th class="py-4 px-6 text-right font-medium">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($user as $b)
                    <tr class="hover:bg-slate-50" >
                        <td class="px-5 py-3">
                            @if($b->avatar_url)
                                <img src="{{ $b->avatar_url }}" alt="{{ $b->name }}" class="h-8 w-8 rounded-lg object-cover border border-slate-200">
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-semibold text-slate-900"><a href="{{ route('admin.user.show', $b) }}">{{ $b->name }}</a></td>
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
                            <a href="{{ route('admin.user.show', $b) }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                👁️ Xem
                            </a>
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

    
</div>
@endsection