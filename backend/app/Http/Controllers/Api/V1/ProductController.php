<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Api\ProductService;
use App\Http\Requests\Api\GetProductListRequest;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ProductDetailResource;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

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
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $product = $this->productService->getProductDetail($id);

            return response()->json([
                'status'  => true,
                'message' => 'Lấy chi tiết sản phẩm thành công',
                'data'    => new ProductDetailResource($product)
            ], 200);
        } catch (\Exception $e) {
            $code = $e->getMessage() == "Sản phẩm không tồn tại." ? 404 : 500;
            return response()->json(['status' => false, 'message' => $e->getMessage()], $code);
        }
    }
}
