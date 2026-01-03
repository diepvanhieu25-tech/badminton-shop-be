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
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $service
    ) {}

    public function index(Request $request): View
    {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%"); // Thêm email nếu cần
            });
        }
        $category = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.category.index', compact('category'));

    }

    public function create(): View
    {
        return view('admin.category.create');
    }

    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Xử lý upload ảnh
        if ($request->hasFile('image_url')) {
            // Lưu vào storage/app/public/categories
            $path = $request->file('image_url')->store('categories', 'public'); 
            $data['image_url'] = $path; // Gán đường dẫn vào data
        }

        $this->service->create($data);

        return redirect()->route('admin.category.index')->with('success','Tạo category thành công.');
    }

    public function edit(Category $category): View
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(CategoryUpdateRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        // Xử lý upload ảnh mới
        if ($request->hasFile('image_url')) {
            // 1. Xóa ảnh cũ nếu tồn tại (để tiết kiệm dung lượng)
            if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
                Storage::disk('public')->delete($category->image_url);
            }

            // 2. Lưu ảnh mới
            $path = $request->file('image_url')->store('categories', 'public');
            $data['image_url'] = $path;
        }

        $this->service->update($category, $data);

        return redirect()->route('admin.category.index')->with('success','Cập nhật category thành công.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        //  Xóa ảnh khi xóa category 
        if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
            Storage::disk('public')->delete($category->image_url);
        }

        $this->service->delete($category);

        return redirect()->route('admin.category.index')->with('success','Xóa category thành công.');
    }
}
