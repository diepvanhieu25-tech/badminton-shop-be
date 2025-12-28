<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetCategoryListRequest;
use App\Services\CategoryService;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(GetCategoryListRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $categories = $this->categoryService->getCategories($validatedData);

            return response()->json([
                'status'  => true,
                'message' => 'Lấy danh mục thành công',
                'data'    => CategoryResource::collection($categories)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
