@extends('layouts.admin')

@section('title', 'Cập nhật hãng')

@section('content')
<div class="rounded-2xl bg-white border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between p-5 border-b border-slate-200">
        <div>
            <div class="text-lg font-bold text-slate-900">Cập nhật hãng</div>
            <div class="text-sm text-slate-500">Chỉnh sửa thông tin: <span class="font-semibold text-slate-700">{{ $brand->name }}</span></div>
        </div>
        <a href="{{ route('admin.brands.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            ← Quay lại
        </a>
    </div>

    {{-- QUAN TRỌNG: Thêm enctype để upload file --}}
    <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data" class="p-5 space-y-5">
        @csrf
        @method('PUT')

        @include('admin.brands._form', ['brand' => $brand])

        <div class="flex items-center justify-end gap-2 pt-2">
            <a href="{{ route('admin.brands.index') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Hủy</a>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 shadow-sm">💾 Lưu thay đổi</button>
        </div>
    </form>

    {{-- Form Delete giữ nguyên --}}
    <div class="p-5 pt-0 border-t border-slate-200">
        <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}"
              onsubmit="return confirm('Xóa hãng này? (soft delete)')">
            @csrf
            @method('DELETE')
            <button type="submit" class="mt-5 rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-700 shadow-sm">
                🗑️ Xóa hãng
            </button>
        </form>
    </div>
</div>
@endsection