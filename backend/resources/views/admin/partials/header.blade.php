<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-6">
    <div class="flex items-center gap-3">
        <div class="md:hidden text-xl">☰</div>
        <div>
            <div class="text-sm text-slate-500">Admin</div>
            <div class="font-semibold leading-5">@yield('page_title', 'Dashboard')</div>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <input
            class="hidden md:block w-80 px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300"
            placeholder="Tìm kiếm (tạm)"
        />
        <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center font-semibold">A</div>
    </div>
</header>
