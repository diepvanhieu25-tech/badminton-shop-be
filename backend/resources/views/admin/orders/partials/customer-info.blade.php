<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
    <h3 class="font-semibold text-slate-800 mb-4 border-b pb-3">Khách hàng</h3>
    
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-lg">
            {{ substr($order->receiver_name, 0, 1) }}
        </div>
        <div>
            <div class="font-medium text-slate-900">{{ $order->receiver_name }}</div>
            @if($order->user_id)
                {{-- Link tới trang chi tiết user nếu cần --}}
                <span class="text-xs text-blue-600">Thành viên</span>
            @else
                <span class="text-xs text-slate-400">Khách vãng lai</span>
            @endif
        </div>
    </div>

    <div class="space-y-3 text-sm">
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-envelope text-slate-400 mt-1 w-4 text-center"></i>
            <span class="text-slate-600 break-all">{{ $order->receiver_email ?? $order->user->email ?? 'N/A' }}</span>
        </div>
        <div class="flex items-start gap-3">
            <i class="fa-solid fa-phone text-slate-400 mt-1 w-4 text-center"></i>
            <span class="text-slate-600">{{ $order->receiver_phone }}</span>
        </div>
         <div class="flex items-start gap-3">
            <i class="fa-solid fa-location-dot text-slate-400 mt-1 w-4 text-center"></i>
            <span class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}</span>
        </div>
    </div>
</div>