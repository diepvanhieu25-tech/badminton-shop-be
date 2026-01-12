<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\OrderService;
use App\Services\Admin\ViettelPostService;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\order\OrderFilterRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

class OrderController extends Controller
{

    public function __construct(
        private readonly OrderService $service
    ) {}

    public function index(OrderFilterRequest $request)
    {
        $filters = array_merge([
            'q' => null,
            'status' => null,
            'sort' => 'date_desc',
        ], $request->validated());

        $orders = $this->service->list($filters);

        return view('admin.orders.index', compact('orders', 'filters'));
    }

    public function show(Order $order)
    {
        $order = $this->service->detail($order->id);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        try {
            $this->service->updateStatus($order->id, $request->status);

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Cập nhật trạng thái thành công.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function ship(Request $request, Order $order, ViettelPostService $shippingService)
    {

        try {
            // 1. Gọi Service để lấy mã vận đơn tự động
            $trackingCode = $shippingService->createOrder($order);

            // 2. Lưu vào DB (Giống logic cũ)
            \Illuminate\Support\Facades\DB::transaction(function () use ($order, $trackingCode) {

                \App\Models\Shipment::create([
                    'order_id' => $order->id,
                    'carrier' => 'Viettel Post', // Cố định
                    'tracking_code' => $trackingCode, // Mã tự động từ API (hoặc Mock)
                    'status' => 'shipping',
                    'cod_amount' => ($order->payment_status !== 'paid') ? $order->total : 0,
                    'shipped_at' => now(),
                ]);

                $order->update(['status' => \App\Enums\OrderStatus::SHIPPING]);
            });

            return redirect()->back()->with('success', 'Đã đẩy đơn sang Viettel Post thành công! Mã: ' . $trackingCode);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi kết nối vận chuyển: ' . $e->getMessage());
        }
    }

    public function print(Order $order)
    {
        // Load view riêng dành cho việc in ấn
        return view('admin.orders.print', compact('order'));
    }
}
