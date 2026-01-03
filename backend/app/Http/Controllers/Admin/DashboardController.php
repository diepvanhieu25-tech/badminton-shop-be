<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Debug từng bước
        // dd('Step 1: Controller chạy');
        
        try {
            // ========== THỐNG KÊ CARDS ==========
            
            // Doanh thu hôm nay
            $todayRevenue = Order::whereDate('created_at', Carbon::today())
                ->where('status', 'completed')
                ->sum('total');
            
            // dd('Step 2: todayRevenue', $todayRevenue);
            
            // Doanh thu hôm qua
            $yesterdayRevenue = Order::whereDate('created_at', Carbon::yesterday())
                ->where('status', 'completed')
                ->sum('total');
            
            // % tăng giảm doanh thu
            $revenueGrowth = $yesterdayRevenue > 0 
                ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
                : 0;

            // Đơn hàng hôm nay
            $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
            
            // Đơn hàng hôm qua
            $yesterdayOrders = Order::whereDate('created_at', Carbon::yesterday())->count();
            
            // Chênh lệch đơn hàng
            $ordersGrowth = $todayOrders - $yesterdayOrders;

            // dd('Step 3: Orders', $todayOrders, $ordersGrowth);

            // Khách hàng mới tuần này (role = customer)
            $weekCustomers = User::where('role', 'customer')
                ->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])->count();
            
            // Khách hàng tuần trước
            $lastWeekCustomers = User::where('role', 'customer')
                ->whereBetween('created_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ])->count();
            
            // % tăng trưởng khách hàng
            $customersGrowth = $lastWeekCustomers > 0
                ? round((($weekCustomers - $lastWeekCustomers) / $lastWeekCustomers) * 100, 1)
                : 0;

            // dd('Step 4: Customers', $weekCustomers, $customersGrowth);

            // Sản phẩm đã bán tháng này
            $monthProducts = OrderItem::whereHas('order', function($query) {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year)
                          ->where('status', 'completed');
                })
                ->sum('quantity');

            // dd('Step 5: monthProducts', $monthProducts);

            // ========== BIỂU ĐỒ DOANH THU 7 NGÀY ==========
            
            $revenueData = [];
            $revenueLabels = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $revenueLabels[] = $date->format('d/m');
                
                $revenue = Order::whereDate('created_at', $date)
                    ->where('status', 'completed')
                    ->sum('total');
                
                $revenueData[] = round($revenue / 1000000, 2); // Chuyển sang triệu
            }

            // dd('Step 6: Revenue chart', $revenueLabels, $revenueData);

            // ========== BIỂU ĐỒ TRẠNG THÁI ĐƠN HÀNG ==========
            
            $orderStatusData = [
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'shipping' => Order::where('status', 'shipping')->count(),
                'completed' => Order::where('status', 'completed')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ];

            // dd('Step 7: Status chart', $orderStatusData);

            // ========== TOP 5 SẢN PHẨM BÁN CHẠY THÁNG NÀY ==========
            
            $topProducts = OrderItem::select(
                    'product_name',
                    'variant_name',
                    DB::raw('SUM(quantity) as total_sold'),
                    DB::raw('SUM(total_price) as total_revenue')
                )
                ->whereHas('order', function($query) {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year)
                          ->where('status', 'completed');
                })
                ->groupBy('product_name', 'variant_name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();

            // dd('Step 8: Top products', $topProducts);

            // Lấy tên sản phẩm bán chạy nhất cho card
            $topProductName = $topProducts->isNotEmpty() 
                ? $topProducts->first()->product_name 
                : 'Chưa có dữ liệu';

            // ========== ĐƠN HÀNG GẦN ĐÂY ==========
            
            $recentOrders = Order::with('user:id,name,email') // Chỉ load field cần thiết
                ->select('id', 'user_id', 'code', 'receiver_name', 'total', 'status', 'created_at')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            // dd('Step 9: Recent orders', $recentOrders);

            // ========== TRẢ VỀ VIEW ==========
            
            return view('admin.dashboard.index', compact(
                'todayRevenue',
                'revenueGrowth',
                'todayOrders',
                'ordersGrowth',
                'weekCustomers',
                'customersGrowth',
                'monthProducts',
                'topProductName',
                'revenueData',
                'revenueLabels',
                'orderStatusData',
                'topProducts',
                'recentOrders'
            ));

        } catch (\Exception $e) {
            // Debug lỗi
            dd('Error:', $e->getMessage(), $e->getLine(), $e->getFile());
        }
    }
}