<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;

    // Inject Service vào Controller
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        try {
            // Lấy toàn bộ data từ Service
            $data = $this->dashboardService->getDashboardData();

            // Truyền sang View
            return view('admin.dashboard.index', $data);

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            
            return back()->with('error', 'Không thể tải dữ liệu thống kê: ' . $e->getMessage());
        }
    }
}