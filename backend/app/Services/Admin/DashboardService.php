<?php

namespace App\Services\Admin;

use App\Repositories\Interfaces\Admin\DashboardRepositoryInterface;
use Carbon\Carbon;

class DashboardService
{
    protected $dashboardRepo;

    public function __construct(DashboardRepositoryInterface $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }

    public function getDashboardData()
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 1. Thống kê Doanh thu
        $todayRevenue = $this->dashboardRepo->getRevenueByDate($today);
        $yesterdayRevenue = $this->dashboardRepo->getRevenueByDate($yesterday);
        $revenueGrowth = $this->calculateGrowth($todayRevenue, $yesterdayRevenue);

        // 2. Thống kê Đơn hàng
        $todayOrders = $this->dashboardRepo->countOrdersByDate($today);
        $yesterdayOrders = $this->dashboardRepo->countOrdersByDate($yesterday);
        $ordersGrowth = $todayOrders - $yesterdayOrders;

        // 3. Thống kê Khách hàng (Tuần này vs Tuần trước)
        $weekCustomers = $this->dashboardRepo->countNewCustomers($now->copy()->startOfWeek(), $now->copy()->endOfWeek());
        $lastWeekCustomers = $this->dashboardRepo->countNewCustomers($now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek());
        $customersGrowth = $this->calculateGrowth($weekCustomers, $lastWeekCustomers);

        // 4. Sản phẩm bán trong tháng
        $monthProducts = $this->dashboardRepo->countSoldProducts($now->month, $now->year);

        // 5. Data Biểu đồ doanh thu 7 ngày
        $chartData = $this->prepareRevenueChartData();

        // 6. Data Biểu đồ trạng thái đơn hàng
        $orderStatusData = $this->dashboardRepo->getOrderStatusCounts();

        // 7. Top sản phẩm bán chạy
        $topProducts = $this->dashboardRepo->getTopSellingProducts($now->month, $now->year);
        // Logic lấy tên sp bán chạy nhất để hiển thị card (nếu cần)
        $topProductName = $topProducts->isNotEmpty() ? $topProducts->first()->product_name : 'Chưa có dữ liệu';

        // 8. Đơn hàng gần đây
        $recentOrders = $this->dashboardRepo->getRecentOrders();

        // Trả về mảng dữ liệu tổng hợp
        return [
            'todayRevenue'    => $todayRevenue,
            'revenueGrowth'   => $revenueGrowth,
            'todayOrders'     => $todayOrders,
            'ordersGrowth'    => $ordersGrowth,
            'weekCustomers'   => $weekCustomers,
            'customersGrowth' => $customersGrowth,
            'monthProducts'   => $monthProducts,
            'revenueData'     => $chartData['data'],
            'revenueLabels'   => $chartData['labels'],
            'orderStatusData' => $orderStatusData,
            'topProducts'     => $topProducts,
            'topProductName'  => $topProductName, // Biến này để hiển thị ở card KPI nếu muốn
            'recentOrders'    => $recentOrders,
        ];
    }

    // Hàm phụ: Tính % tăng trưởng
    private function calculateGrowth($current, $previous)
    {
        return $previous > 0 
            ? round((($current - $previous) / $previous) * 100, 1) 
            : 0;
    }

    // Hàm phụ: Chuẩn bị data biểu đồ 7 ngày
    private function prepareRevenueChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $revenue = $this->dashboardRepo->getRevenueByDate($date);
            $data[] = round($revenue / 1000000, 2); // Chuyển sang đơn vị triệu
        }

        return ['data' => $data, 'labels' => $labels];
    }
}