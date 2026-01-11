{{-- resources/views/admin/dashboard/partials/header.blade.php --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Xin chào, Admin 👋</h2>
        <p class="text-slate-500 text-sm mt-1">Đây là tình hình kinh doanh của cửa hàng hôm nay.</p>
    </div>
    <div class="flex gap-2">
        <button class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-50 transition shadow-sm">
            <i class="fa-solid fa-calendar-day mr-2"></i> Hôm nay
        </button>
        {{-- Link tới route export --}}
        @if(Route::has('admin.orders.export'))
        <a href="{{ route('admin.orders.export') }}" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-50 transition shadow-sm">
            <i class="fa-solid fa-download mr-2"></i> Xuất báo cáo
        </a>
        @endif
    </div>
</div>