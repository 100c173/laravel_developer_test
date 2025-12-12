<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
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

/**
 * Summary of All Endpoints:
 * 
 * PUBLIC ENDPOINTS (No Authentication Required):
 * - POST   /api/auth/register           - Register new user
 * - POST   /api/auth/verify             - Verify account with code
 * - POST   /api/auth/login              - Login with credentials
 * - POST   /api/auth/forgot-password    - Request password reset
 * - POST   /api/auth/reset-password     - Reset password with code
 * - POST   /api/auth/resend-code        - Resend verification code
 * 
 * PROTECTED ENDPOINTS (Authentication Required):
 * - GET    /api/auth/me                 - Get user profile
 * - POST   /api/auth/change-password    - Change password
 * - POST   /api/auth/logout             - Logout user
 */