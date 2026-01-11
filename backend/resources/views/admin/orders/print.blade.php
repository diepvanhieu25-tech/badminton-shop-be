<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font chữ Google cho đẹp --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Roboto', sans-serif; 
            background: #e5e7eb; /* Màu nền xám khi xem trên web */
        }
        .invoice-box {
            background: #fff;
            width: 210mm; /* Khổ A4 */
            min-height: 297mm;
            margin: 20px auto;
            padding: 15mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        
        /* CSS DÀNH RIÊNG CHO MÁY IN */
        @media print {
            @page { margin: 0; size: A4; }
            body { margin: 0; background: #fff; }
            .invoice-box { 
                width: 100%; 
                margin: 0; 
                padding: 10mm; 
                box-shadow: none; 
                border: none; 
            }
            .no-print { display: none !important; }
            
            /* Bắt buộc in màu nền (background-color) */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body>

    {{-- NÚT IN (Sẽ ẩn khi in) --}}
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded shadow-lg hover:bg-blue-700 font-bold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            In ngay
        </button>
        <button onclick="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded shadow-lg hover:bg-gray-600">
            Đóng
        </button>
    </div>

    {{-- KHUNG HÓA ĐƠN A4 --}}
    <div class="invoice-box relative">
        
        {{-- 1. HEADER & BARCODE --}}
        <div class="flex justify-between items-start border-b-2 border-gray-800 pb-6 mb-6">
            <div class="w-2/3">
                <div class="flex items-center gap-4">
                    {{-- Logo Shop (Thay ảnh thật của bạn vào đây) --}}
                    <div class="w-16 h-16 bg-black text-white flex items-center justify-center font-bold text-xl rounded">
                        PRO
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold uppercase tracking-widest text-gray-800">Badminton Pro</h1>
                        <p class="text-sm text-gray-600">Chuyên dụng cụ cầu lông chính hãng</p>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-600 space-y-1">
                    <p><strong>Địa chỉ:</strong> Số 1 Võ Văn Ngân, Thủ Đức, TP.HCM</p>
                    <p><strong>Hotline:</strong> 0912.345.678 - <strong>Email:</strong> support@badmintonpro.vn</p>
                    <p><strong>Website:</strong> www.badmintonpro.vn</p>
                </div>
            </div>
            
            <div class="w-1/3 text-right">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">HÓA ĐƠN</h2>
                <div class="flex flex-col items-end">
                    {{-- Tạo Barcode tự động từ mã đơn --}}
                    <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $order->code }}&code=Code128&translate-esc=on&unit=Fit&dpi=96&imagetype=Png&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0" 
                         alt="Barcode" class="h-12 mb-1">
                    <p class="text-sm font-mono font-bold text-gray-600">{{ $order->code }}</p>
                </div>
                <p class="text-sm text-gray-500 mt-2">Ngày: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        {{-- 2. THÔNG TIN GỬI / NHẬN --}}
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-50 p-4 rounded border border-gray-200">
                <h3 class="font-bold text-gray-800 uppercase text-xs mb-3 border-b border-gray-300 pb-1">Nhà cung cấp</h3>
                <p class="font-bold text-lg">Cửa hàng Badminton Pro</p>
                <p class="text-sm text-gray-600">Kho hàng tổng TP.HCM</p>
                <p class="text-sm text-gray-600">SĐT: 0912.345.678</p>
            </div>
            <div class="bg-gray-50 p-4 rounded border border-gray-200">
                <h3 class="font-bold text-gray-800 uppercase text-xs mb-3 border-b border-gray-300 pb-1">Khách hàng</h3>
                <p class="font-bold text-lg text-blue-900">{{ $order->receiver_name }}</p>
                <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                <p class="text-sm text-gray-600">SĐT: {{ $order->receiver_phone }}</p>
            </div>
        </div>

        {{-- 3. BẢNG SẢN PHẨM --}}
        <table class="w-full mb-8 border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white text-sm uppercase">
                    <th class="py-3 px-4 text-left rounded-tl">STT</th>
                    <th class="py-3 px-4 text-left w-1/2">Tên sản phẩm</th>
                    <th class="py-3 px-4 text-center">ĐVT</th>
                    <th class="py-3 px-4 text-right">Đơn giá</th>
                    <th class="py-3 px-4 text-center">SL</th>
                    <th class="py-3 px-4 text-right rounded-tr">Thành tiền</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @foreach($order->items as $index => $item)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-4 text-left">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 font-medium">
                        {{ $item->product_name }}
                        @if($item->variant_name)
                            <div class="text-xs text-gray-500 mt-0.5">Phân loại: {{ $item->variant_name }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">Cái</td>
                    <td class="py-3 px-4 text-right">{{ number_format($item->unit_price) }}</td>
                    <td class="py-3 px-4 text-center font-bold">{{ $item->quantity }}</td>
                    <td class="py-3 px-4 text-right font-bold">{{ number_format($item->total_price) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- 4. TỔNG KẾT TIỀN --}}
        <div class="flex justify-end mb-10">
            <div class="w-1/2">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Cộng tiền hàng:</span>
                    <span class="font-medium">{{ number_format($order->subtotal) }} ₫</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span class="font-medium">{{ number_format($order->shipping_fee) }} ₫</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between py-2 border-b border-gray-100 text-green-600">
                    <span>Giảm giá (Voucher):</span>
                    <span>-{{ number_format($order->discount_amount) }} ₫</span>
                </div>
                @endif
                <div class="flex justify-between py-3 border-t-2 border-gray-800 mt-2">
                    <span class="font-bold text-xl uppercase">Tổng thanh toán:</span>
                    <span class="font-bold text-2xl text-red-600">{{ number_format($order->total) }} ₫</span>
                </div>
                <div class="text-right text-xs italic text-gray-500 mt-1">
                    (Đã bao gồm thuế GTGT nếu có)
                </div>
            </div>
        </div>

        {{-- 5. CHỮ KÝ --}}
        <div class="grid grid-cols-3 gap-4 text-center text-sm mb-12">
            <div>
                <p class="font-bold uppercase mb-1">Người mua hàng</p>
                <p class="text-xs text-gray-500 italic">(Ký, ghi rõ họ tên)</p>
            </div>
            <div>
                <p class="font-bold uppercase mb-1">Người giao hàng</p>
                <p class="text-xs text-gray-500 italic">(Ký, ghi rõ họ tên)</p>
            </div>
            <div>
                <p class="font-bold uppercase mb-1">Người bán hàng</p>
                <p class="text-xs text-gray-500 italic">(Ký, đóng dấu)</p>
                <div class="h-20 mt-2 flex items-center justify-center opacity-30">
                    {{-- Chỗ này để đóng dấu mộc đỏ --}}
                    <div class="border-2 border-red-500 text-red-500 rounded-full w-24 h-24 flex items-center justify-center font-bold transform -rotate-12">
                        ĐÃ KÝ
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. FOOTER --}}
        <div class="border-t border-gray-300 pt-4 text-center text-xs text-gray-500">
            <p class="mb-1">Cảm ơn quý khách đã tin tưởng và mua sắm tại Badminton Pro!</p>
            <p>Vui lòng kiểm tra kỹ hàng hóa trước khi nhận. Đổi trả trong vòng 7 ngày nếu có lỗi từ nhà sản xuất.</p>
            <p class="mt-2 font-mono">Invoice generated at: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        {{-- BACKGROUND DECORATION (Watermark) --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0 opacity-5 overflow-hidden">
            <h1 class="text-[150px] font-bold transform -rotate-45 text-gray-900 whitespace-nowrap">BADMINTON PRO</h1>
        </div>
    </div>

    {{-- SCRIPT TỰ ĐỘNG IN --}}
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // Đợi 0.5s để ảnh/font load xong mới in
        }
    </script>
</body>
</html>