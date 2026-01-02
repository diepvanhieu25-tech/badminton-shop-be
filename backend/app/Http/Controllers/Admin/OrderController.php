<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\order\OrderFilterRequest;

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

}