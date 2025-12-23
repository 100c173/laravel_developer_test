<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DashboardLogController;
use App\Http\Controllers\Admin\DashboardProductController;
use App\Http\Controllers\Admin\DashboardUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'role:admin'])->prefix('admin/dashboard')->name('admin.dashboard.')->group(function () {

    // admin/dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::get('/product-chart', [DashboardController::class, 'productChart'])->name('products-chart');

    // admin/dashboard/products
    Route::prefix('products')
        ->name('products.')
        ->group(function () {

            Route::get('/', [DashboardProductController::class, 'index'])->name('index');
            Route::get('/datatable', [DashboardProductController::class, 'datatable'])->name('datatable');

            Route::get('/create', [DashboardProductController::class, 'create'])->name('create');
            Route::post('/', [DashboardProductController::class, 'store'])->name('store');

            Route::get('/{product}/edit', [DashboardProductController::class, 'edit'])->name('edit');
            Route::put('/{product}', [DashboardProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [DashboardProductController::class, 'destroy'])->name('destroy');

            Route::get('/{product}/images', [DashboardProductController::class, 'imagesIndex'])->name('images.index');
            Route::post('/{product}/images', [DashboardProductController::class, 'addImage'])->name('images.store');
            Route::delete('/images/{image}', [DashboardProductController::class, 'deleteImage'])->name('images.destroy');
            Route::put('/images/{image}/primary', [DashboardProductController::class, 'setPrimaryImage'])->name('images.primary');
        });
});


Route::middleware(['auth', 'role:admin'])->prefix('admin/dashboard')->name('admin.dashboard.')->group(function () {
    // Users Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [DashboardUserController::class, 'index'])->name('index');
        Route::get('/datatable', [DashboardUserController::class, 'datatable'])->name('datatable');

        Route::get('/create', [DashboardUserController::class, 'create'])->name('create');
        Route::post('/', [DashboardUserController::class, 'store'])->name('store');

        Route::get('/{user}', [DashboardUserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [DashboardUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [DashboardUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [DashboardUserController::class, 'destroy'])->name('destroy');

        // Account Management
        Route::post('/{user}/block', [DashboardUserController::class, 'block'])->name('block');
        Route::post('/{user}/unblock', [DashboardUserController::class, 'unblock'])->name('unblock');
        Route::post('/{user}/activate', [DashboardUserController::class, 'activate'])->name('activate');
        Route::post('/{user}/deactivate', [DashboardUserController::class, 'deactivate'])->name('deactivate');
        Route::post('/{user}/verify', [DashboardUserController::class, 'verify'])->name('verify');
        Route::post('/{user}/unverify', [DashboardUserController::class, 'unverify'])->name('unverify');

        Route::get('/{user}/change-password', [DashboardUserController::class, 'changePasswordForm'])->name('change-password');
        Route::post('/{user}/change-password', [DashboardUserController::class, 'changePassword'])->name('change-password.update');

        Route::get('/{user}/send-email', [DashboardUserController::class, 'sendEmailForm'])->name('send-email');
        Route::post('/{user}/send-email', [DashboardUserController::class, 'sendEmail'])->name('send-email.send');

        Route::get('/cities/{country}', [DashboardUserController::class, 'getCities'])
            ->name('cities.by-country');

        Route::get('/{user}/products', [DashboardUserController::class, 'getProducts'])->name('products');
    });
});


Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/dashboard')->name('admin.dashboard.')->group(function () {
    Route::get('/activity-logs', [DashboardLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/datatable', [DashboardLogController::class, 'datatable'])->name('activity-logs.datatable');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('language/{locale}', function ($locale) {

    if (!in_array($locale, ['en', 'ar'])) {
        abort(400);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('language.switch');

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';
