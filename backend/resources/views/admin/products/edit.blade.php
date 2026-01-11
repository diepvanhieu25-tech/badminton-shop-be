@extends('layouts.admin')

@section('title', 'Cập nhật: ' . $product->name)

@section('content')
    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Cập nhật sản phẩm</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-sm text-slate-500">Đang sửa:</span>
                <span class="text-sm font-semibold text-blue-600">{{ $product->name }}</span>
            </div>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('admin.products.detail', $product) }}" 
               class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition font-medium text-sm">
                Xem chi tiết
            </a>
            <a href="{{ route('admin.products.index') }}" 
               class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition font-medium text-sm">
                ← Quay lại
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm">
            <p class="font-bold">Có lỗi xảy ra!</p>
            <ul class="list-disc list-inside text-sm mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- INCLUDE FORM CHÍNH --}}
    @include('admin.products.form', [
        'route' => route('admin.products.update', $product),
        'product' => $product
    ])

@endsection