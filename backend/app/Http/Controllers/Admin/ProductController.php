<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\product\ProductStoreRequest;
use App\Http\Requests\Admin\product\ProductUpdateRequest;
use App\Models\Product;
use App\Services\Admin\ProductService;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $service
    ) {}

    public function index(Request $request): View
    {
        $products = $this->service->getAll($request->all());
        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::all(); // Nên dùng Repository cho clean, đây làm nhanh
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $this->service->create(
            $request->validated(),
            $request->file('thumbnail'),
            $request->file('gallery', [])
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product): View
    {
        $product->load(['variants', 'images']);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $this->service->update(
            $product,
            $request->validated(),
            $request->file('thumbnail'),
            $request->file('gallery', [])
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->service->delete($product);
        return redirect()->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công!');
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'brand', 'variants', 'images']);

        return view('admin.products.show', compact('product'));
    }
}
