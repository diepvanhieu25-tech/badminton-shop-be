@if($order->note)
<div class="bg-yellow-50 rounded-xl border border-yellow-200 p-4">
    <h3 class="font-semibold text-yellow-800 text-sm mb-2 flex items-center gap-2">
        <i class="fa-regular fa-note-sticky"></i> Ghi chú từ khách
    </h3>
    <p class="text-sm text-yellow-800/80 italic">"{{ $order->note }}"</p>
</div>
@endif