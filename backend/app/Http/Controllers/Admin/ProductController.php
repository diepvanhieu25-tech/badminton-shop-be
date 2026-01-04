<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;;
use App\Models\Product;
use App\Services\Admin\ProductService;

// Requests
use App\Http\Requests\Admin\product\ProductFilterRequest;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;

// Models cho select box
use App\Models\Category;
use App\Models\Brand;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service
    ) {}

    /**
     * Danh sách sản phẩm
     */
    public function index(ProductFilterRequest $request)
    {
        $products = $this->service->list(
            $request->validated()
        );

        return view('admin.products.index', compact('products'));
    }

    /**
     * Form tạo sản phẩm
     */
    public function create()
    {
        $categories = Category::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $brands = Brand::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact(
            'categories',
            'brands'
        ));
    }

    /**
     * Lưu sản phẩm mới
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->service->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.products.detail', $product)
            ->with('success', 'Tạo sản phẩm thành công');
    }

    /**
     * Chi tiết sản phẩm
     */
    public function detail(Product $product)
    {
        $product = $this->service->detail($product->id);

        return view('admin.products.detail', compact('product'));
    }

    /**
     * Form sửa sản phẩm
     */
    public function edit(Product $product)
    {
        $product = $this->service->detail($product->id);

        $categories = Category::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $brands = Brand::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'brands'
        ));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product = $this->service->update(
            $product,
            $request->validated()
        );

        return redirect()
            ->route('admin.products.detail', $product)
            ->with('success', 'Cập nhật sản phẩm thành công');
    }

    /**
     * Xóa sản phẩm (soft delete)
     */
    public function destroy(Product $product)
    {
        $this->service->delete($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }
}
