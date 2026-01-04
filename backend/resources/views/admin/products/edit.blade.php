@extends('layouts.admin')

@section('title', 'Cập nhật sản phẩm')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-slate-800">Cập nhật: {{ $product->name }}</h1>
    <a href="{{ route('admin.products.index') }}" class="text-slate-600 hover:underline">← Quay lại</a>
</div>

@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
    <ul class="list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.products._form', ['mode' => 'edit'])
</form>
@endsection
