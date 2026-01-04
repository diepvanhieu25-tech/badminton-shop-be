@extends('layouts.admin')

@section('title', 'Tạo hãng')

@section('content')
<div class="rounded-2xl bg-white border border-slate-200 shadow-sm">
    <div class="flex items-center justify-between p-5 border-b border-slate-200">
        <div>
            <div class="text-lg font-bold text-slate-900">Tạo hãng mới</div>
            <div class="text-sm text-slate-500">Thêm mới một hãng sản phẩm vào hệ thống.</div>
        </div>
        <a href="{{ route('admin.brands.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
           <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data" class="p-5 space-y-5">
        @csrf
        @include('admin.brands._form')
        
        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 mt-5">
            <a href="{{ route('admin.brands.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors">Hủy bỏ</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 shadow-sm transition-all">
                <i class="fa-solid fa-check"></i> Hoàn tất
            </button>
        </div>
    </form>
</div>
@endsection