<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // Giả sử đã có
use App\Models\Brand;    // Giả sử đã có
use App\Enums\ProductStatus; // Import Enum
use App\Http\Requests\Api\StoreProductRequest as ApiStoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest as ApiUpdateProductRequest;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // 1. Danh sách
    public function index(Request $request)
    {
        // Tận dụng hàm list của Service, thêm phân trang
        $products = $this->productService->list($request->all(), 10);
        return view('admin.products.index', compact('products'));
    }

    // 2. Form thêm mới
    public function create()
    {
        // Cần lấy danh mục và thương hiệu để hiển thị trong <select>
        $categories = Category::all(); 
        $brands = Brand::all();
        $statuses = ProductStatus::cases(); // Lấy list Enum

        return view('admin.products.create', compact('categories', 'brands', 'statuses'));
    }

    // 3. Xử lý lưu
    public function store(ApiStoreProductRequest $request)
    {
        $this->productService->create($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công');
    }

    // 4. Form sửa
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $statuses = ProductStatus::cases();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'statuses'));
    }

    // 5. Xử lý cập nhật
    public function update(ApiUpdateProductRequest $request, Product $product)
    {
        $this->productService->update($product, $request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật thành công');
    }

    // 6. Xóa
    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm');
    }
}