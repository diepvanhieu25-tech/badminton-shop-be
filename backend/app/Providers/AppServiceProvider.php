<?php

namespace App\Providers;

use App\Repositories\Eloquent\Admin\BrandRepository as AdminBrandRepository;
use App\Repositories\Eloquent\BrandRepository;
use App\Repositories\Eloquent\CartRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\Admin\BrandRepositoryInterface as AdminBrandRepositoryInterface;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\Admin\OrderRepositoryInterface;
use App\Repositories\Eloquent\Admin\OrderRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(
            BrandRepositoryInterface::class,
            BrandRepository::class
        );
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
