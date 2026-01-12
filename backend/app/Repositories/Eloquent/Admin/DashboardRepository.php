<?php

namespace App\Repositories\Eloquent\Admin;

use App\Repositories\Interfaces\Admin\DashboardRepositoryInterface;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getRevenueByDate($date)
    {
        return Order::whereDate('created_at', $date)
            ->where('status', OrderStatus::COMPLETED) // Chỉ tính đơn hoàn thành
            ->sum('total');
    }

    public function getRevenueBetweenDates($startDate, $endDate)
    {
        return Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', OrderStatus::COMPLETED)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function countOrdersByDate($date)
    {
        return Order::whereDate('created_at', $date)->count();
    }

    public function countOrdersByStatus()
    {
        return Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function getRecentOrders(int $limit = 5)
    {
        return Order::with('user') // Eager load user
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function countNewCustomersBetween($startDate, $endDate)
    {
        return User::where('role', UserRole::CUSTOMER) // Giả sử enum UserRole có case CUSTOMER
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    public function countLowStockVariants(int $threshold = 10)
    {
        // Đếm số biến thể có tồn kho dưới mức quy định
        return ProductVariant::where('stock_qty', '<=', $threshold)->count();
    }

    public function getTopSellingProducts(int $limit = 5)
    {
        // Join orders để check status, group theo variant hoặc product_name
        return OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatus::COMPLETED)
            ->selectRaw('
                order_items.product_name, 
                order_items.product_variant_id,
                SUM(order_items.quantity) as total_sold,
                MAX(order_items.unit_price) as price
            ')
            ->groupBy('order_items.product_name', 'order_items.product_variant_id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->with(['variant.product']) // Load để lấy ảnh thumbnail
            ->get();
    }
}