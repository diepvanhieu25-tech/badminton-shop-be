<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\category\CategoryStoreRequest;
use App\Http\Requests\Admin\category\CategoryUpdateRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['q','is_active']);
        $category  = $this->service->list($filters, (int)$request->integer('per_page', 15));

        return view('admin.category.index', compact('category','filters'));
    }

    public function create(): View
    {
        return view('admin.category.create');
    }

    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.category.index')->with('success','Tạo category thành công.');
    }

    public function edit(Category $category): View
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(CategoryUpdateRequest $request, Category $category): RedirectResponse
    {
        $this->service->update($category, $request->validated());

        return redirect()->route('admin.category.index', $category)->with('success','Cập nhật category thành công.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->service->delete($category);

        return redirect()->route('admin.category.index')->with('success','Xóa category thành công.');
    }
}
