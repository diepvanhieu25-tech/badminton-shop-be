<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
    <h3 class="font-semibold text-slate-800 mb-4 border-b pb-3">Tiến độ đơn hàng</h3>
    
    {{-- THANH TIẾN TRÌNH (STEPPER) --}}
    <div class="relative pl-4 border-l-2 border-slate-200 space-y-6 my-4">
        @php
            $steps = [
                ['status' => 'pending', 'label' => 'Đặt hàng thành công', 'icon' => 'fa-cart-shopping'],
                ['status' => 'processing', 'label' => 'Đã xác nhận', 'icon' => 'fa-box-open'],
                ['status' => 'shipping', 'label' => 'Đang giao hàng', 'icon' => 'fa-truck-fast'],
                ['status' => 'completed', 'label' => 'Hoàn thành', 'icon' => 'fa-check'],
            ];

            // Tìm index của status hiện tại để highlight
            $currentStatus = $order->status->value;
            $currentIndex = -1;
            foreach ($steps as $index => $step) {
                if ($step['status'] === $currentStatus) {
                    $currentIndex = $index;
                    break;
                }
            }
            // Nếu status là cancelled/refunded/returned thì không highlight theo luồng này
            $isFailure = in_array($currentStatus, ['cancelled', 'refunded', 'returned']);
        @endphp

        @if($isFailure)
            {{-- Trường hợp thất bại --}}
            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-red-500 border-2 border-white"></div>
            <div class="text-red-600 font-bold text-sm">
                @if($currentStatus == 'cancelled') Đã hủy đơn
                @elseif($currentStatus == 'refunded') Đã hoàn tiền
                @else Đã trả hàng
                @endif
            </div>
        @else
            {{-- Trường hợp bình thường --}}
            @foreach($steps as $index => $step)
                <div class="relative">
                    {{-- Dot --}}
                    <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full border-2 border-white 
                        {{ $index <= $currentIndex ? 'bg-emerald-500 ring-2 ring-emerald-100' : 'bg-slate-300' }}">
                    </div>
                    
                    {{-- Text --}}
                    <div class="{{ $index <= $currentIndex ? 'text-emerald-700 font-medium' : 'text-slate-400' }} text-sm">
                        {{ $step['label'] }}
                    </div>
                    
                    {{-- Time (Optional: Hiển thị nếu có log) --}}
                    @if($index === 0) 
                        <div class="text-xs text-slate-400">{{ $order->created_at->format('H:i d/m') }}</div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    {{-- KHU VỰC HÀNH ĐỘNG (ACTION BUTTONS) --}}
    <div class="mt-6 pt-4 border-t border-slate-100">
        
        {{-- CASE 1: ĐƠN MỚI (PENDING) --}}
        @if($order->status === \App\Enums\OrderStatus::PENDING)
            <p class="text-xs text-slate-500 mb-3">Hành động tiếp theo:</p>
            <div class="grid grid-cols-2 gap-3">
                {{-- Nút Duyệt --}}
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="processing">
                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition shadow-sm">
                        Xác nhận đơn
                    </button>
                </form>

                {{-- Nút Hủy --}}
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?')">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="w-full py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition">
                        Hủy bỏ
                    </button>
                </form>
            </div>

        {{-- CASE 2: ĐANG XỬ LÝ (PROCESSING) --}}
        @elseif($order->status === \App\Enums\OrderStatus::PROCESSING)
            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 flex gap-3">
                <i class="fa-solid fa-arrow-left text-blue-500 mt-1"></i>
                <div class="text-xs text-blue-800">
                    <p class="font-bold">Đang chờ vận đơn</p>
                    <p class="mt-1">Vui lòng sử dụng chức năng <b>"Tạo vận đơn"</b> ở cột bên trái để chuyển sang giao hàng.</p>
                </div>
            </div>
            
            {{-- Vẫn cho phép hủy nếu lỡ xác nhận nhầm --}}
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-3 text-center" onsubmit="return confirm('Hủy đơn hàng đang xử lý?')">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="text-xs text-red-500 hover:underline">Hủy đơn hàng này</button>
            </form>

        {{-- CASE 3: ĐANG GIAO (SHIPPING) --}}
        @elseif($order->status === \App\Enums\OrderStatus::SHIPPING)
            <p class="text-xs text-slate-500 mb-3">Xác nhận kết quả giao hàng:</p>
            <div class="space-y-3">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" onsubmit="return confirm('Xác nhận khách đã nhận hàng?')">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Giao thành công
                    </button>
                </form>

                <form action="{{ route('admin.orders.update', $order) }}" method="POST" onsubmit="return confirm('Xác nhận khách trả hàng?')">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="returned">
                    <button type="submit" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
                        Khách trả hàng
                    </button>
                </form>
            </div>

        {{-- CASE 4: ĐÃ HOÀN THÀNH / HỦY --}}
        @else
            <div class="text-center py-4 bg-slate-50 rounded-lg border border-slate-100">
                <p class="text-sm text-slate-500">Đơn hàng đã kết thúc.</p>
            </div>
        @endif

    </div>
</div>