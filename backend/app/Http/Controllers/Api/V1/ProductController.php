<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\ProductService;
use App\Http\Requests\Api\GetProductListRequest;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ProductDetailResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    // API 1: Lấy danh sách + Lọc + Phân trang
    public function index(GetProductListRequest $request): JsonResponse
    {
        try {
            $products = $this->productService->getProducts($request->validated());

            return response()->json([
                'status'  => true,
                'message' => 'Lấy danh sách sản phẩm thành công',
                'data'    => ProductResource::collection($products)->response()->getData(true)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    // API 2: Lấy chi tiết + SP liên quan
    public function show($id): JsonResponse
    {
        try {
            $product = $this->productService->getProductDetail($id);

            return response()->json([
                'status'  => true,
                'message' => 'Lấy chi tiết sản phẩm thành công',
                'data'    => new ProductDetailResource($product)
            ], 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Sản phẩm không tồn tại.'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
}