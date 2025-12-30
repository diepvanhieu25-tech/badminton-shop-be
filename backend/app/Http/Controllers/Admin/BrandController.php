<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\brand\BrandStoreRequest;
use App\Http\Requests\Admin\brand\BrandUpdateRequest;
use App\Models\Brand;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BrandController extends Controller
{
    public function __construct(
        private readonly BrandService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['q','is_active']);
        $brands  = $this->service->list($filters, (int)$request->integer('per_page', 15));

        return view('admin.brands.index', compact('brands','filters'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(BrandStoreRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.brands.index')->with('success','Tạo brand thành công.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(BrandUpdateRequest $request, Brand $brand): RedirectResponse
    {
        $this->service->update($brand, $request->validated());

        return redirect()->route('admin.brands.edit', $brand)->with('success','Cập nhật brand thành công.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->service->delete($brand);

        return redirect()->route('admin.brands.index')->with('success','Xóa brand thành công.');
    }
}
