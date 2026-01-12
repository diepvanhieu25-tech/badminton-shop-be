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
        $users = $this->service->list($request->all(), 15);
        return view('admin.user.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.user.create');
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());
        return redirect()->route('admin.user.index')->with('success', 'Tạo khách hàng thành công.');
    }

    public function show($id): View
    {
        // Hàm này giờ chỉ lấy User + Số liệu thống kê (không load list đơn hàng)
        $user = $this->service->getDetail($id);
        
        return view('admin.user.detail', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $this->service->update($user, $request->validated());
        return redirect()->route('admin.user.index')->with('success', 'Cập nhật khách hàng thành công.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->service->delete($user);
        return redirect()->route('admin.user.index')->with('success', 'Xóa khách hàng thành công.');
    }
}