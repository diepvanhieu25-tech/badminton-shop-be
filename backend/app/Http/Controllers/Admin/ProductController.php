<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;;
use App\Models\Product;
use App\Services\Admin\ProductService;
use App\Http\Requests\Admin\product\ProductFilterRequest;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Brand;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service
    ) {}

    public function index(ProductFilterRequest $request)
    {
        $products = $this->service->list(
            $request->validated()
        );

        $categories = Category::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.products.index', compact(
            'products',
            'categories'
        ));
    }

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

    public function store(StoreProductRequest $request)
    {
        $product = $this->service->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.products.detail', $product)
            ->with('success', 'Tạo sản phẩm thành công');
    }

    public function detail(Product $product)
    {
        $product = $this->service->detail($product->id);

        return view('admin.products.detail', compact('product'));
    }

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

    public function destroy(Product $product)
    {
        $this->service->delete($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }
}
