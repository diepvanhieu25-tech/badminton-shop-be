    <?php

    use Illuminate\Support\Facades\Route;
    // Import các Controller Admin
    use App\Http\Controllers\Admin\AuthController;
    use App\Http\Controllers\Admin\BrandController;
    use App\Http\Controllers\Admin\CategoryController;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\Admin\DashboardController;
    use App\Http\Controllers\Admin\ProductController;
    use App\Http\Controllers\Admin\OrderController;

    Route::get('/', function () {
        return view('welcome');
    });

    // --- NHÓM ROUTE ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {

        // 1. Route Đăng nhập (KHÔNG dùng middleware auth/admin để người lạ còn vào được)
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // 2. Route Cần bảo vệ (Phải đăng nhập VÀ là Admin mới vào được)
        // Lưu ý: 'admin' là tên middleware bạn đã đăng ký ở bước trước (trong bootstrap/app.php)
        Route::middleware(['auth', 'admin'])->group(function () {

            // Dashboard
            Route::get('/', [DashboardController::class, 'index']); // Vào /admin tự chuyển dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Các Resource (Quản lý dữ liệu)
            Route::resource('products', ProductController::class);
            Route::resource('brands', BrandController::class)->except(['show']);
            Route::resource('category', CategoryController::class)->except(['show']);
            Route::resource('user', UserController::class);

            Route::prefix('user')->name('user.')->group(function () {
                Route::get('/', [UserController::class, 'index'])
                    ->name('index');
                Route::get('create', [UserController::class, 'create'])
                    ->name('create');
                Route::post('/', [UserController::class, 'store'])
                    ->name('store');
                Route::get('{user}', [UserController::class, 'show'])
                    ->name('show');
                Route::get('{user}/edit', [UserController::class, 'edit'])
                    ->name('edit');
                Route::put('{user}', [UserController::class, 'update'])
                    ->name('update');
                Route::delete('{user}', [UserController::class, 'destroy'])
                    ->name('destroy');
            });

            Route::prefix('orders')->name('orders.')->group(function () {
                Route::get('/', [OrderController::class, 'index'])->name('index');
                Route::get('{order}', [OrderController::class, 'show'])->name('show');
                Route::put('{order}', [OrderController::class, 'update'])->name('update');
                Route::post('{order}/ship', [OrderController::class, 'ship'])->name('ship');
                Route::get('{order}/print', [OrderController::class, 'print'])->name('print');
            });

            Route::prefix('products')->name('products.')->group(function () {

                Route::get('/', [ProductController::class, 'index'])->name('index');
                Route::get('create', [ProductController::class, 'create'])->name('create');
                Route::post('/', [ProductController::class, 'store'])->name('store');

                Route::get('{product}', [ProductController::class, 'detail'])->name('detail');
                Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
                Route::put('{product}', [ProductController::class, 'update'])->name('update');
                Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
            });
        });
    });
