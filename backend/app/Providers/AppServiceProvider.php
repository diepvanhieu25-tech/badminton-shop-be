<?php

namespace App\Providers;

use App\Repositories\Eloquent\Admin\BrandRepository as AdminBrandRepository;
use App\Repositories\Eloquent\Admin\CategoryRepository as AdminCategoryRepository;
use App\Repositories\Eloquent\Admin\ProductRepository as AdminProductRepository;
use App\Repositories\Eloquent\Admin\UserRepository;
use App\Repositories\Eloquent\Api\BrandRepository;
use App\Repositories\Eloquent\Api\CartRepository;
use App\Repositories\Eloquent\Api\CategoryRepository;
use App\Repositories\Eloquent\Api\EloquentUserRepository;
use App\Repositories\Eloquent\Api\OrderRepository;
use App\Repositories\Eloquent\Api\ProductRepository;
use App\Repositories\Interfaces\Admin\BrandRepositoryInterface as AdminBrandRepositoryInterface;
use App\Repositories\Interfaces\Admin\CategoryRepositoryInterface as AdminCategoryRepositoryInterface;
use App\Repositories\Interfaces\Admin\ProductRepositoryInterface as AdminProductRepositoryInterface;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface as AdminUserRepositoryInterface;
use App\Repositories\Interfaces\Api\BrandRepositoryInterface;
use App\Repositories\Interfaces\Api\CartRepositoryInterface;
use App\Repositories\Interfaces\Api\CategoryRepositoryInterface;
use App\Repositories\Interfaces\Api\OrderRepositoryInterface;
use App\Repositories\Interfaces\Api\ProductRepositoryInterface;
use App\Repositories\Interfaces\Api\UserRepositoryInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\Admin\OrderRepositoryInterface as AdminOrderRepositoryInterface;;

use App\Repositories\Eloquent\Admin\OrderRepository as AdminOrderRepository;
use App\Repositories\Eloquent\Api\PasswordResetRepository;
use App\Repositories\Interfaces\Api\PasswordResetRepositoryInterface;;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdminOrderRepositoryInterface::class, AdminOrderRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(PasswordResetRepositoryInterface::class, PasswordResetRepository::class);
        $this->app->bind(AdminBrandRepositoryInterface::class, AdminBrandRepository::class);
        $this->app->bind(AdminCategoryRepositoryInterface::class, AdminCategoryRepository::class);
        $this->app->bind(AdminUserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AdminProductRepositoryInterface::class, AdminProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            // Thay đổi URL này thành URL trang Reset Password của Frontend (React/NextJS)
            return config('app.frontend_url') . "/reset-password?token={$token}&email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
