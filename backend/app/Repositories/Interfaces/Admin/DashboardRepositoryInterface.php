<?php

namespace App\Repositories\Interfaces\Admin;

use Illuminate\Support\Collection;

interface DashboardRepositoryInterface
{
    // Thống kê doanh thu
    public function getRevenueByDate($date);
    public function getRevenueBetweenDates($startDate, $endDate);

    // Thống kê đơn hàng
    public function countOrdersByDate($date);
    public function countOrdersByStatus(); // Cho biểu đồ tròn
    public function getRecentOrders(int $limit = 5);

    // Thống kê khách hàng
    public function countNewCustomersBetween($startDate, $endDate);

    // Thống kê sản phẩm
    public function countLowStockVariants(int $threshold = 10);
    public function getTopSellingProducts(int $limit = 5);
}