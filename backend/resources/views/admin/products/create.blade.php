@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Thêm sản phẩm</h1>
            <p class="text-sm text-slate-500 mt-1">Tạo mới sản phẩm và thiết lập kho hàng ban đầu.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" 
           class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition font-medium text-sm">
            ← Quay lại danh sách
        </a>
    </div>

    {{-- ERROR ALERT (Global) --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm" role="alert">
            <p class="font-bold">Lưu ý!</p>
            <p>Vui lòng kiểm tra lại các thông tin bên dưới.</p>
        </div>
    @endif

    {{-- INCLUDE FORM CHÍNH --}}
    {{-- Lưu ý: Đây là file form.blade.php chứa thẻ <form> mà ta đã tách ở bước trước --}}
    @include('admin.products.form', [
        'route' => route('admin.products.store'),
        'product' => new \App\Models\Product(), // Truyền model rỗng để tránh lỗi null
    ])

@endsection