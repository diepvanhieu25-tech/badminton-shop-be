<aside class="w-64 hidden md:flex flex-col bg-white border-r border-slate-200 font-sans">
    <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-200">
        <div class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center shadow-md">
            <i class="fa-solid fa-feather text-xl"></i>
        </div>
        <div>
            <div class="font-bold text-lg leading-5 text-slate-800">Badminton Pro</div>
            <div class="text-xs text-slate-500 font-medium mt-0.5">Quản trị cửa hàng</div>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-6 text-center">
                <i class="fa-solid fa-gauge-high text-lg group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="font-medium">Tổng quan</span>
        </a>

        <a href="{{ route('admin.products.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.products*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-6 text-center">
                <i class="fa-solid fa-box-open text-lg group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="font-medium">Sản phẩm</span>
        </a>

        <a href="{{ route('admin.category.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.category*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-6 text-center">
                <i class="fa-solid fa-layer-group text-lg group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="font-medium">Danh mục</span>
        </a>

        <a href="{{ route('admin.brands.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.brands*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-6 text-center">
                <i class="fa-solid fa-tags text-lg group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="font-medium">Hãng</span>
        </a>

        {{-- Lưu ý: Mình sửa route cứng thành route('admin.orders.index') nếu bạn đã định nghĩa --}}
        <a href="{{ route('admin.orders.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.orders*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-6 text-center">
                <i class="fa-solid fa-file-invoice-dollar text-lg group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="font-medium">Đơn hàng</span>
        </a>

        <a href="{{ route('admin.user.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.user*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-6 text-center">
                <i class="fa-solid fa-users text-lg group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="font-medium">Khách hàng</span>
        </a>

        <a href="/admin/report"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('admin/reports*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <div class="w-6 text-center">
                <i class="fa-solid fa-chart-line text-lg group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="font-medium">Báo cáo</span>
        </a>

    </nav>

    <div class="mt-auto p-4 border-t border-slate-200 bg-slate-50">
        <a href="{{ route('admin.logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class="fa-solid fa-right-from-bracket"></i>
            Đăng xuất
        </a>

        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>