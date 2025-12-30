@extends('layouts.admin')

@section('title', 'Tạo hãng')

@section('content')
<div class="rounded-2xl bg-white border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between p-5 border-b border-slate-200">
        <div>
            <div class="text-lg font-bold text-slate-900">Tạo hãng</div>
            <div class="text-sm text-slate-500">Thêm mới một hãng sản phẩm.</div>
        </div>
        <a href="{{ route('admin.brands.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            ← Quay lại
        </a>
    </div>

    <form method="POST" action="{{ route('admin.brands.store') }}" class="p-5 space-y-5">
        @csrf
        @include('admin.brands._form')
        <div class="flex items-center justify-end gap-2 pt-2">
            <a href="{{ route('admin.brands.index') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Hủy</a>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 shadow-sm">✅ Tạo hãng</button>
        </div>
    </form>
</div>
@endsection
