<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\user\UserStoreRequest;
use App\Http\Requests\Admin\user\UserUpdateRequest;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['q','is_active']);
        $user  = $this->service->list($filters, (int)$request->integer('per_page', 15));

        return view('admin.user.index', compact('user','filters'));
    }

    public function create(): View
    {
        return view('admin.user.create');
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.user.index')->with('success','Tạo User thành công.');
    }

    public function edit(User $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $this->service->update($user, $request->validated());

        return redirect()->route('admin.user.index', $user)->with('success','Cập nhật User thành công.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->service->delete($user);

        return redirect()->route('admin.user.index')->with('success','Xóa User thành công.');
    }
}
