<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\User;
use App\Enums\UserRole;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = User::query();

        // Loại bỏ Admin
        $q->where('role', '!=', UserRole::ADMIN);

        // Search đa trường
        if (!empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $q->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $q->where('status', $filters['status']);
        }

        return $q->orderByDesc('created_at')->paginate($perPage);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function findWithDetails($id): User
    {
        // 1. Khởi tạo query
        $query = User::query();
        
        // 2. Bảo mật: Không cho phép xem chi tiết của user có role Admin thông qua ID
        $query->where('role', '!=', UserRole::ADMIN);

        return $query
            // 3. TỐI ƯU: Đã xóa with(['orders']) vì UI không hiển thị danh sách
            
            // 4. Chỉ lấy tổng số lượng đơn
            ->withCount('orders')

            // 5. Chỉ lấy tổng tiền (đơn completed)
            ->withSum(['orders as lifetime_spent' => function ($q) {
                $q->where('status', 'completed');
            }], 'total') 
            
            ->findOrFail($id);
    }
}