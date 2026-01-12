@php
    $isEdit = isset($product) && $product->exists;
@endphp

<form action="{{ $route }}" method="POST" enctype="multipart/form-data" id="product-form">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-2 space-y-6">
            @include('admin.products.partials.basic-info')
            @include('admin.products.partials.variants')
            @include('admin.products.partials.images')
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="lg:col-span-1 space-y-6">
            @include('admin.products.partials.sidebar')
        </div>
    </div>
</form>

{{-- JS LOGIC --}}
@include('admin.products.partials.scripts')