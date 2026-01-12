<?php

namespace App\Repositories\Interfaces\Admin;

interface DashboardRepositoryInterface
{
    public function getRevenueByDate($date);
    public function countOrdersByDate($date);
    public function countNewCustomers($startDate, $endDate);
    public function countSoldProducts($month, $year);
    public function getOrderStatusCounts();
    public function getTopSellingProducts($month, $year, $limit = 5);
    public function getRecentOrders($limit = 5);
}