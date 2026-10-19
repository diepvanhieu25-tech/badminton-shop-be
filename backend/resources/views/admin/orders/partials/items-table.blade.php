<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <h3 class="font-semibold text-slate-800">Chi tiết sản phẩm</h3>
        <span
            class="text-xs font-medium px-2.5 py-1 bg-slate-200 rounded-full text-slate-600">{{ $order->items->count() }}
            món</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200">
                <tr>
                    <th class="py-3 px-6 w-[50%]">Sản phẩm</th>
                    <th class="py-3 px-6 text-center">Đơn giá</th>
                    <th class="py-3 px-6 text-center">SL</th>
                    <th class="py-3 px-6 text-right">Tổng</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="py-4 px-6">
                            <div class="flex gap-4">
                                <div
                                    class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden relative group">
                                    @php
                                        $resolveImg = function($path) {
                                            if (!$path) return null;
                                            return filter_var($path, FILTER_VALIDATE_URL) ? $path : Storage::url($path);
                                        };

                                        $imgUrl = null;
                                        if ($item->variant) {
                                            if ($item->variant->image) {
                                                $imgUrl = $resolveImg($item->variant->image);
                                            } elseif ($item->variant->product && $item->variant->product->thumbnail) {
                                                $imgUrl = $resolveImg($item->variant->product->thumbnail);
                                            } elseif ($item->variant->product && $item->variant->product->images->count() > 0) {
                                                $imgUrl = $resolveImg($item->variant->product->images->first()->image_url);
                                            }
                                        }
                                    @endphp

                                    @if ($imgUrl)
                                        <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-image text-slate-300"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900 line-clamp-2">{{ $item->product_name }}</p>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        <span class="text-xs text-slate-500">Mã: <strong class="text-slate-700 font-mono">{{ $item->variant_name ?? 'Mặc định' }}</strong></span>
                                        @php
                                            $attrs = $item->attributes ?? ($item->variant?->attributes ?? []);
                                        @endphp
                                        @if (!empty($attrs) && is_array($attrs))
                                            @foreach ($attrs as $k => $v)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    {{ strtoupper($k) }}: {{ is_array($v) ? json_encode($v) : $v }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center text-slate-600">
                            {{ number_format($item->unit_price) }}₫
                        </td>
                        <td class="py-4 px-6 text-center font-medium text-slate-900">
                            x{{ $item->quantity }}
                        </td>
                        <td class="py-4 px-6 text-right font-semibold text-slate-900">
                            {{ number_format($item->total_price) }}₫
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTAL CALCULATION --}}
    <div class="bg-slate-50/50 px-6 py-4 border-t border-slate-200">
        <div class="flex justify-end">
            <div class="w-full md:w-1/2 space-y-2 text-sm text-slate-600">
                <div class="flex justify-between">
                    <span>Tạm tính:</span>
                    <span class="font-medium text-slate-900">{{ number_format($order->subtotal) }}₫</span>
                </div>
                <div class="flex justify-between">
                    <span>Phí vận chuyển:</span>
                    <span class="font-medium text-slate-900">{{ number_format($order->shipping_fee) }}₫</span>
                </div>
                {{-- Nếu có giảm giá coupon --}}
                @if ($order->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Giảm giá:</span>
                        <span class="font-medium">-{{ number_format($order->discount_amount) }}₫</span>
                    </div>
                @endif
                <div class="border-t border-slate-200 pt-2 mt-2 flex justify-between items-center text-base">
                    <span class="font-bold text-slate-800">Tổng thanh toán:</span>
                    <span class="font-bold text-emerald-600 text-lg">{{ number_format($order->total) }}₫</span>
                </div>
            </div>
        </div>
    </div>
</div>
