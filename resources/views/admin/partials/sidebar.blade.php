<aside class="w-64 hidden md:flex flex-col bg-white border-r border-slate-200">
    <!-- Header / Logo -->
    <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-200">
        <div class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-lg shadow-md">
            🏸
        </div>
        <div>
            <div class="font-bold text-lg leading-5">Badminton Pro</div>
            <div class="text-xs text-slate-500">Quản trị cửa hàng</div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <a href="/admin" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 transition-colors group <?= request()->is('admin') || request()->is('admin/dashboard') ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-slate-700' ?>">
            <span class="text-lg group-hover:scale-110 transition-transform">📊</span>
            <span class="font-medium">Tổng quan</span>
        </a>

        <a href="/admin/products" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 transition-colors group <?= request()->is('admin/products*') ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-slate-700' ?>">
            <span class="text-lg group-hover:scale-110 transition-transform">🛍️</span>
            <span class="font-medium">Sản phẩm</span>
        </a>
        <a href="/admin/category/index" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 transition-colors group <?= request()->is('admin/customers*') ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-slate-700' ?>">
            <span class="text-lg group-hover:scale-110 transition-transform">👥</span>
            <span class="font-medium">Danh mục</span>
        </a>
        <a href="/admin/brand/index" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 transition-colors group <?= request()->is('admin/customers*') ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-slate-700' ?>">
            <span class="text-lg group-hover:scale-110 transition-transform">👥</span>
            <span class="font-medium">Hãng</span>
        </a>

        <a href="/admin/order/index" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 transition-colors group <?= request()->is('admin/orders*') ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-slate-700' ?>">
            <span class="text-lg group-hover:scale-110 transition-transform">🧾</span>
            <span class="font-medium">Đơn hàng</span>
        </a>

        <a href="/admin/customer/index" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 transition-colors group <?= request()->is('admin/customers*') ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-slate-700' ?>">
            <span class="text-lg group-hover:scale-110 transition-transform">👥</span>
            <span class="font-medium">Khách hàng</span>
        </a>

       

        <a href="/admin/report" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 transition-colors group <?= request()->is('admin/reports*') ? 'bg-emerald-100 text-emerald-700 font-semibold' : 'text-slate-700' ?>">
            <span class="text-lg group-hover:scale-110 transition-transform">📈</span>
            <span class="font-medium">Báo cáo</span>
        </a>

     </nav>

    <!-- Footer - Đăng xuất -->
    <div class="mt-auto p-4 border-t border-slate-200">
        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
           class="w-full px-4 py-3 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2 shadow-md">
            <span>🚪</span>
            Đăng xuất
        </a>
        
         <form id="logout-form" action="/logout" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>