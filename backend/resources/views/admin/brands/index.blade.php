@extends('layouts.admin')

@section('title', 'Danh sách hãng')

@section('content')
<div class="flex items-start justify-between gap-4 mb-4">
    <div>
        <div class="text-xl font-bold text-slate-900">Hãng (Brands)</div>
        <div class="text-sm text-slate-500">Quản lý danh sách hãng sản phẩm.</div>
    </div>
    <a href="{{ route('admin.brands.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 shadow-sm">
        ➕ Tạo hãng
    </a>
</div>

<div class="rounded-2xl bg-white border border-slate-200 shadow-sm">
    <div class="p-5 border-b border-slate-200">
        <form method="GET" action="{{ route('admin.brands.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tìm kiếm</label>
                <input name="q" placeholder="Nhập tên hãng..."
                       value="{{ $filters['q'] ?? '' }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Trạng thái</label>
                <select name="is_active"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-emerald-100 focus:border-emerald-400">
                    <option value="">Tất cả</option>
                    <option value="1" {{ (($filters['is_active'] ?? '') === '1') ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ (($filters['is_active'] ?? '') === '0') ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Lọc
                </button>
                <a href="{{ route('admin.brands.index') }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
            <tr class="text-left">
                <th class="px-5 py-3 font-semibold">ID</th>
                <th class="px-5 py-3 font-semibold">Tên hãng</th>
                <th class="px-5 py-3 font-semibold">Logo</th>
                <th class="px-5 py-3 font-semibold">Trạng thái</th>
                <th class="px-5 py-3 font-semibold text-right">Hành động</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($brands as $b)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 text-slate-500">{{ $b->id }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-900">{{ $b->name }}</td>
                    <td class="px-5 py-3">
                        @if($b->logo_url)
                            <img src="{{ $b->logo_url }}" alt="{{ $b->name }}" class="h-8 w-8 rounded-lg object-cover border border-slate-200">
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($b->is_active)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">● Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">● Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.brands.edit', $b) }}"
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

    <div class="p-4 border-t border-slate-200">
        {{ $brands->withQueryString()->links() }}
    </div>
</div>
@endsection
