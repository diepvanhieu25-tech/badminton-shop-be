<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\BrandService;
use App\Http\Resources\Api\BrandResource;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(): JsonResponse
    {
        try {
            $brands = $this->brandService->getAllBrands();

            return response()->json([
                'status'  => true,
                'message' => 'Lấy danh sách thương hiệu thành công',
                'data'    => BrandResource::collection($brands)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
