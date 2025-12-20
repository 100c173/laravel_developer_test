<?php


use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ProductController;

use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes Group
Route::prefix('auth')->group(function () {

    Route::post('register', [AuthController::class, 'register'])
        ->name('auth.register');

    /**
     * POST /api/auth/verify
     * Verify user account using verification code
     * 
     * */
    Route::post('verify', [AuthController::class, 'verify'])
        ->name('auth.verify');

    /**
     * POST /api/auth/login
     * Authenticate user with email/phone and password
     */
    Route::post('login', [AuthController::class, 'login'])
        ->name('auth.login');

    /**
     * POST /api/auth/forgot-password
     * Request password reset code
     * Note: Always returns success for security reasons
     */
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('auth.forgot-password');

    /**
     * POST /api/auth/reset-password
     * Reset password using verification code
     */
    Route::post('reset-password', [AuthController::class, 'resetPassword'])
        ->name('auth.reset-password');

    /**
     * POST /api/auth/resend-code
     * Resend verification code to user
     */
    Route::post('resend-code', [AuthController::class, 'resendCode'])
        ->name('auth.resend-code');

    // Protected Routes (Require Authentication)
    Route::middleware('auth:sanctum')->group(function () {

        /**
         * GET /api/auth/me
         * Get authenticated user profile
         */
        Route::get('me', [AuthController::class, 'me'])
            ->name('auth.me');

        /**
         * POST /api/auth/change-password
         * Change password for authenticated user
         */
        Route::post('change-password', [AuthController::class, 'changePassword'])
            ->name('auth.change-password');

        /**
         * POST /api/auth/logout
         * Logout user by revoking all tokens
         */
        Route::post('logout', [AuthController::class, 'logout'])
            ->name('auth.logout');
    });
});

Route::prefix('users')->middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // CRUD Users
    Route::get('/', [UserController::class, 'index']);          // List users (paginated)
    Route::post('/', [UserController::class, 'store']);         // Create user
    Route::get('{id}', [UserController::class, 'show']);        // Show user
    Route::put('{id}', [UserController::class, 'update']);      // Update user
    Route::delete('{id}', [UserController::class, 'destroy']);  // Delete user

});

Route::prefix('users')->middleware(['auth:sanctum'])->group(function () {
    // User Actions
    Route::post('{id}/change-password', [UserController::class, 'changePassword']);
    Route::post('{id}/send-email', [UserController::class, 'sendEmail']);

    // User Products
    Route::get('{id}/products', [UserController::class, 'products']);
});


Route::prefix('products')->middleware(['auth:sanctum','role:admin'])->group(function () {

    // CRUD Products
    Route::get('/', [ProductController::class, 'index']);          // List products
    Route::post('/', [ProductController::class, 'store']);         // Create product
    Route::get('{id}', [ProductController::class, 'show']);        // Show product
    Route::put('{id}', [ProductController::class, 'update']);      // Update product
    Route::delete('{id}', [ProductController::class, 'destroy']);  // Delete product

    // Assign product to user
    Route::post('{id}/assign-user', [ProductController::class, 'assignToUser']);

});