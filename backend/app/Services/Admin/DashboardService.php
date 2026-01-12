<?php

namespace App\Services\Admin;

use App\Repositories\Interfaces\Admin\DashboardRepositoryInterface;
use Carbon\Carbon;
use App\Enums\OrderStatus;

class DashboardService
{
    protected $repository;

    public function __construct(DashboardRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getDashboardData()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        // 1. KPI: Doanh thu
        $todayRevenue = $this->repository->getRevenueByDate($today);
        $yesterdayRevenue = $this->repository->getRevenueByDate($yesterday);
        $revenueGrowth = $this->calculateGrowth($todayRevenue, $yesterdayRevenue);

        // 2. KPI: Đơn hàng mới
        $todayOrders = $this->repository->countOrdersByDate($today);
        $yesterdayOrders = $this->repository->countOrdersByDate($yesterday);
        $ordersGrowth = $this->calculateGrowth($todayOrders, $yesterdayOrders);

        // 3. KPI: Khách hàng tuần này
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();

        $weekCustomers = $this->repository->countNewCustomersBetween($startOfWeek, $endOfWeek);
        $lastWeekCustomers = $this->repository->countNewCustomersBetween($startOfLastWeek, $endOfLastWeek);
        $customersGrowth = $this->calculateGrowth($weekCustomers, $lastWeekCustomers);

        // 4. KPI: Sản phẩm sắp hết
        $lowStockCount = $this->repository->countLowStockVariants(10);

        // 5. Chart: Doanh thu 7 ngày qua
        $revenueChart = $this->repository->getRevenueBetweenDates(Carbon::now()->subDays(6), $today);
        $revenueLabels = $revenueChart->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d/m'))->toArray();
        $revenueData = $revenueChart->pluck('revenue')->toArray();

        // 6. Chart: Trạng thái đơn hàng (Format đúng key cho View)
        $rawStatusData = $this->repository->countOrdersByStatus();
        $orderStatusData = [
            'pending'    => $rawStatusData[OrderStatus::PENDING->value] ?? 0,
            'processing' => $rawStatusData[OrderStatus::PROCESSING->value] ?? 0,
            'shipping'   => $rawStatusData[OrderStatus::SHIPPING->value] ?? 0,
            'completed'  => $rawStatusData[OrderStatus::COMPLETED->value] ?? 0,
            'cancelled'  => ($rawStatusData[OrderStatus::CANCELLED->value] ?? 0) + ($rawStatusData[OrderStatus::REFUNDED->value] ?? 0),
        ];

        // 7. Lists
        $recentOrders = $this->repository->getRecentOrders(5);
        $topProducts = $this->repository->getTopSellingProducts(5);

        // Map lại topProducts để view dễ dùng (lấy thumbnail từ relation)
        $topProducts->transform(function ($item) {
            $item->thumbnail = $item->variant?->image ?? $item->variant?->product?->thumbnail;
            $item->name = $item->product_name; // Từ OrderItem
            return $item;
        });

        return compact(
            'todayRevenue', 'revenueGrowth',
            'todayOrders', 'ordersGrowth',
            'weekCustomers', 'customersGrowth',
            'lowStockCount',
            'revenueLabels', 'revenueData',
            'orderStatusData',
            'recentOrders',
            'topProducts'
        );
    }

    // Helper tính % tăng trưởng
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }
}