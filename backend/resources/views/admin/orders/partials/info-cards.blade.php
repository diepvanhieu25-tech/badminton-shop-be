<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- CARD THANH TOÁN (Giữ nguyên) --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 h-full">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Thanh toán</h3>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-slate-500">Phương thức</span>
                <span class="font-medium uppercase">{{ $order->payment_method ?? 'COD' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-slate-500">Trạng thái</span>
                {{-- Cách an toàn nhất: kiểm tra xem nó có phải là enum không --}}
                @php
                    // Lấy giá trị raw string bất kể nó là Enum hay String
                    $statusValue =
                        $order->payment_status instanceof \UnitEnum
                            ? $order->payment_status->value
                            : $order->payment_status;
                @endphp

                <span class="font-medium {{ $statusValue === 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                    {{ $statusValue === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </span>
            </div>
        </div>
    </div>

    {{-- CARD VẬN CHUYỂN (SỬA PHẦN NÀY) --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 h-full flex flex-col">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Vận chuyển</h3>
        </div>

        @if ($order->shipment)
            {{-- CASE 1: ĐÃ CÓ VẬN ĐƠN --}}
            <div class="text-sm space-y-3 flex-1">
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Đơn vị:</span>
                    <span class="font-bold text-slate-900">{{ $order->shipment->carrier }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500">Mã vận đơn:</span>
                    <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-700 select-all font-bold">
                        {{ $order->shipment->tracking_code }}
                    </span>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                    <a href="https://viettelpost.com.vn/tra-cuu-hanh-trinh-don/phan-hoi-khieu-nai?code={{ $order->shipment->tracking_code }}"
                        target="_blank"
                        class="text-red-600 hover:underline text-xs flex items-center justify-center gap-1 font-medium">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Tra cứu Viettel Post
                    </a>
                </div>
            </div>
        @else
            {{-- CASE 2: CHƯA CÓ VẬN ĐƠN --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center py-2">
                <p class="text-xs text-slate-400 mb-4">Chưa tạo vận đơn</p>

                {{-- Chỉ cho phép tạo vận đơn nếu đơn chưa hủy/hoàn thành --}}
                @if (!in_array($order->status->value, ['cancelled', 'completed', 'returned']))
                    <button type="button" onclick="openShippingModal()"
                        class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tạo vận đơn
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
