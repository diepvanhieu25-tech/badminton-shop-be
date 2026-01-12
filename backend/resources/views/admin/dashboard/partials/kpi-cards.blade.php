{{-- resources/views/admin/dashboard/partials/kpi-cards.blade.php --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    {{-- 1. Doanh thu hôm nay --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group hover:shadow-md transition-all">
        <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <span class="text-slate-500 text-sm font-medium">Doanh thu hôm nay</span>
            </div>
            <div class="text-2xl font-bold text-slate-800 mb-1">{{ number_format($todayRevenue ?? 0, 0, ',', '.') }}₫</div>
            <div class="flex items-center text-xs font-medium">
                @php $growth = $revenueGrowth ?? 0; @endphp
                <span class="{{ $growth >= 0 ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50' }} px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="fa-solid fa-arrow-{{ $growth >= 0 ? 'trend-up' : 'trend-down' }}"></i>
                    {{ abs($growth) }}%
                </span>
                <span class="text-slate-400 ml-2">so với hôm qua</span>
            </div>
        </div>
    </div>

    {{-- 2. Đơn hàng mới --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group hover:shadow-md transition-all">
        <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="text-slate-500 text-sm font-medium">Đơn hàng mới</span>
            </div>
            <div class="text-2xl font-bold text-slate-800 mb-1">{{ $todayOrders ?? 0 }}</div>
            <div class="flex items-center text-xs font-medium">
                @php $growth = $ordersGrowth ?? 0; @endphp
                <span class="{{ $growth >= 0 ? 'text-blue-600 bg-blue-50' : 'text-rose-600 bg-rose-50' }} px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="fa-solid fa-arrow-{{ $growth >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($growth) }}
                </span>
                <span class="text-slate-400 ml-2">so với hôm qua</span>
            </div>
        </div>
    </div>

    {{-- 3. Khách tuần này --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden group hover:shadow-md transition-all">
        <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="text-slate-500 text-sm font-medium">Khách tuần này</span>
            </div>
            <div class="text-2xl font-bold text-slate-800 mb-1">{{ $weekCustomers ?? 0 }}</div>
            <div class="flex items-center text-xs font-medium">
                 @php $growth = $customersGrowth ?? 0; @endphp
                <span class="{{ $growth >= 0 ? 'text-purple-600 bg-purple-50' : 'text-rose-600 bg-rose-50' }} px-2 py-0.5 rounded-full flex items-center gap-1">
                    <i class="fa-solid fa-arrow-{{ $growth >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($growth) }}%
                </span>
                <span class="text-slate-400 ml-2">so với tuần trước</span>
            </div>
        </div>
    </div>
    
    {{-- 4. Sản phẩm sắp hết (Đã cập nhật logic Backend & Link) --}}
    <a href="{{ route('admin.products.index', ['filter' => 'low_stock']) }}" class="block group">
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden hover:shadow-md transition-all h-full">
            <div class="absolute right-0 top-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <span class="text-slate-500 text-sm font-medium">Sản phẩm sắp hết</span>
                </div>
                
                {{-- Hiển thị dữ liệu thực từ biến $lowStockCount --}}
                <div class="text-2xl font-bold text-slate-800 mb-1">{{ $lowStockCount ?? 0 }}</div>
                
                <div class="flex items-center text-xs font-medium">
                    @if(($lowStockCount ?? 0) > 0)
                        <span class="text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full flex items-center gap-1 animate-pulse">
                            <i class="fa-solid fa-circle-exclamation"></i> Cần nhập thêm
                        </span>
                    @else
                        <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fa-solid fa-check"></i> Kho ổn định
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </a>
</div>