{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin - Dashboard')
@section('page_title', 'Tổng quan')

@section('content')
    
    {{-- 1. Header & Filter --}}
    @include('admin.dashboard.partials.header')

    {{-- 2. Thống kê KPI Cards --}}
    @include('admin.dashboard.partials.kpi-cards')

    {{-- 3. Biểu đồ Charts --}}
    @include('admin.dashboard.partials.charts')

    {{-- 4. Danh sách chi tiết (Chia cột 1/3 và 2/3) --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- Top sản phẩm --}}
        @include('admin.dashboard.partials.top-products')

        {{-- Đơn hàng gần đây --}}
        @include('admin.dashboard.partials.recent-orders')
        
    </div>

@endsection

@section('scripts')
    {{-- 5. Scripts Cấu hình Chart --}}
    @include('admin.dashboard.partials.scripts')
@endsection