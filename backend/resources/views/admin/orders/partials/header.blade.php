<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.index') }}"
            class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:border-slate-300 transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                {{ $order->code }}
                <x-badge :text="$order->status->label()" :tone="$order->status->color()" class="text-sm" />
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                <i class="fa-regular fa-calendar mr-1"></i> {{ $order->created_at->format('H:i, d/m/Y') }}
            </p>
        </div>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('admin.orders.print', $order) }}" target="_blank"
            class="px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-print"></i> In hóa đơn
        </a>
    </div>
</div>
