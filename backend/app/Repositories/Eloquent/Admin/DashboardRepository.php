<?php

namespace App\Repositories\Eloquent\Admin;

use App\Repositories\Interfaces\Admin\DashboardRepositoryInterface;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getRevenueByDate($date)
    {
        return Order::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->sum('total');
    }

    public function countOrdersByDate($date)
    {
        return Order::whereDate('created_at', $date)->count();
    }

    public function countNewCustomers($startDate, $endDate)
    {
        return User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    public function countSoldProducts($month, $year)
    {
        return OrderItem::whereHas('order', function($query) use ($month, $year) {
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year)
                  ->where('status', 'completed');
        })->sum('quantity');
    }

    public function getOrderStatusCounts()
    {
        return [
            'pending'    => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipping'   => Order::where('status', 'shipping')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];
    }

    public function getTopSellingProducts($month, $year, $limit = 5)
    {
        return OrderItem::select(
                'product_name',
                'variant_name',
                'product_id', // Nên lấy thêm ID hoặc thumbnail để hiển thị ảnh
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->whereHas('order', function($query) use ($month, $year) {
                $query->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year)
                      ->where('status', 'completed');
            })
            ->groupBy('product_name', 'variant_name', 'product_id') // Group by thêm ID nếu có
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    public function getRecentOrders($limit = 5)
    {
        return Order::with('user:id,name,email')
            ->select('id', 'user_id', 'code', 'receiver_name', 'total', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}