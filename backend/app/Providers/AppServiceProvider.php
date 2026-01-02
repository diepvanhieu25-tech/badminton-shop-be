<?php

namespace App\Providers;

use App\Repositories\Eloquent\Admin\BrandRepository as AdminBrandRepository;
use App\Repositories\Eloquent\Api\BrandRepository;
use App\Repositories\Eloquent\Api\CategoryRepository;
use App\Repositories\Eloquent\Api\EloquentUserRepository;
use App\Repositories\Eloquent\CartRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Interfaces\Admin\BrandRepositoryInterface as AdminBrandRepositoryInterface;
use App\Repositories\Interfaces\Api\BrandRepositoryInterface;
use App\Repositories\Interfaces\Api\CategoryRepositoryInterface;
use App\Repositories\Interfaces\Api\UserRepositoryInterface;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind( BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );
        $this->app->bind(AdminBrandRepositoryInterface::class, AdminBrandRepository::class);
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
