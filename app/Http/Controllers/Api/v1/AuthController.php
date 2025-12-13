<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Authentication service instance for handling auth logic.
     */
    protected AuthService $authService;

    /**
     * Constructor to inject the AuthService dependency.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user account.
     * Endpoint: POST /api/auth/register
     *
     * Exceptions handled by global exception handler:
     * - ValidationException (422): If validation fails
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param RegisterRequest $request The validated registration request
     * @return JsonResponse Success response with user details
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Register the user using the auth service
        // Any exceptions will be caught by the global exception handler
        $user = $this->authService->register($request->validated());

        // Return success response with user details
        return $this->success(
            data: [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
            ],
            message: 'auth.registration_successful',
            status: 201
        );
    }

    /**
     * Verify user account using verification code.
     * Endpoint: POST /api/auth/verify
     *
     * Exceptions handled by global exception handler:
     * - ValidationException (422): If verification fails or code is invalid
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param VerifyRequest $request The validated verification request
     * @return JsonResponse Success response with authentication token
     */
    public function verify(VerifyRequest $request): JsonResponse
    {
        // Verify the account and get authentication token
        // Any exceptions will be caught by the global exception handler
        $token = $this->authService->verifyAccount(
            identifier: $request->identifier,
            code: $request->code
        );

        // Return success response with authentication token
        return $this->success(
            data: [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            message: 'auth.account_verified_successfully',
            status: 200
        );
    }

    /**
     * User login with email/phone and password.
     * Endpoint: POST /api/auth/login
     *
     * Exceptions handled by global exception handler:
     * - ValidationException (422): If credentials are invalid or account is blocked
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param LoginRequest $request The validated login request
     * @return JsonResponse Success response with authentication token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Authenticate user and get token
        // Any exceptions will be caught by the global exception handler
        $token = $this->authService->login(
            identifier: $request->identifier,
            password: $request->password
        );

        // Return success response with authentication token
        return $this->success(
            data: [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            message: 'auth.login_successful',
            status: 200
        );
    }

    /**
     * Change password for authenticated user.
     * Endpoint: POST /api/auth/change-password
     * Requires: Authentication (Bearer token)
     *
     * Exceptions handled by global exception handler:
     * - ValidationException (422): If current password is incorrect
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param ChangePasswordRequest $request The validated password change request
     * @return JsonResponse Success response
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        // Change password for the authenticated user
        // Any exceptions will be caught by the global exception handler
        $this->authService->changePassword(
            user: $request->user(),
            currentPassword: $request->current_password,
            newPassword: $request->new_password
        );

        // Return success response
        return $this->success(
            message: 'auth.password_changed_successfully',
            status: 200
        );
    }

    /**
     * Request password reset code.
     * Endpoint: POST /api/auth/forgot-password
     *
     * Note: This endpoint always returns success for security reasons.
     * It prevents user enumeration attacks by not revealing whether the user exists.
     *
     * Exceptions handled by global exception handler:
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param ForgotPasswordRequest $request The validated forgot password request
     * @return JsonResponse Success response (always returns success for security)
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        // Initiate password reset process
        // Any exceptions will be caught by the global exception handler
        $this->authService->forgotPassword(
            identifier: $request->identifier
        );

        // Always return success message for security reasons
        // This prevents user enumeration attacks
        return $this->success(
            message: 'auth.password_reset_code_sent',
            status: 200
        );
    }

    /**
     * Reset password using verification code.
     * Endpoint: POST /api/auth/reset-password
     *
     * Exceptions handled by global exception handler:
     * - ValidationException (422): If reset code is invalid or expired
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param ResetPasswordRequest $request The validated password reset request
     * @return JsonResponse Success response with authentication token
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        // Reset password and get authentication token
        // Any exceptions will be caught by the global exception handler
        $token = $this->authService->resetPassword(
            identifier: $request->identifier,
            code: $request->code,
            newPassword: $request->new_password
        );

        // Return success response with authentication token
        return $this->success(
            data: [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            message: 'auth.password_reset_successfully',
            status: 200
        );
    }

    /**
     * Logout user by revoking all tokens.
     * Endpoint: POST /api/auth/logout
     * Requires: Authentication (Bearer token)
     *
     * Exceptions handled by global exception handler:
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param Request $request The HTTP request containing authenticated user
     * @return JsonResponse Success response
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke all tokens for the authenticated user
        // Any exceptions will be caught by the global exception handler
        $request->user()->tokens()->delete();

        // Return success response
        return $this->success(
            message: 'auth.logout_successful',
            status: 200
        );
    }

    /**
     * Get authenticated user profile.
     * Endpoint: GET /api/auth/me
     * Requires: Authentication (Bearer token)
     *
     * Exceptions handled by global exception handler:
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param Request $request The HTTP request containing authenticated user
     * @return JsonResponse Success response with user profile data
     */
    public function me(Request $request): JsonResponse
    {
        // Get the authenticated user
        $user = $request->user();

        // Return success response with user profile data
        return $this->success(
            data: [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'country_id' => $user->country_id,
                'city_id' => $user->city_id,
                'is_active' => $user->is_active,
                'email_verified_at' => $user->email_verified_at,
                'phone_verified_at' => $user->phone_verified_at,
            ],
            message: 'auth.user_profile_retrieved',
            status: 200
        );
    }

    /**
     * Resend verification code.
     * Endpoint: POST /api/auth/resend-code
     *
     * Exceptions handled by global exception handler:
     * - ValidationException (422): If validation fails
     * - QueryException (422): If database error occurs
     * - Generic Exception (500): Any other error
     *
     * @param Request $request The HTTP request containing identifier and channel
     * @return JsonResponse Success response
     */
    public function resendCode(Request $request): JsonResponse
    {
        // Validate the request data
        // ValidationException will be caught by the global exception handler
        $validated = $request->validate([
            'identifier' => 'required|string',
            'channel' => 'required|in:email,sms',
        ]);

        // TODO: Implement resend code logic in AuthService
        // This would require a separate method in AuthService to handle resending codes

        // Return success response
        return $this->success(
            message: 'auth.verification_code_sent',
            status: 200
        );
    }
}

/**
 * AuthController Summary
 *
 * This controller is now completely free of try-catch blocks.
 * All exception handling is delegated to the global exception handler
 * defined in bootstrap/exceptions.php
 *
 * Benefits of this approach:
 * 1. Cleaner and more readable controller code
 * 2. Centralized exception handling logic
 * 3. Consistent error response format across all endpoints
 * 4. Easier to maintain and modify error handling behavior
 * 5. Automatic logging of all exceptions
 * 6. Proper HTTP status codes for each exception type
 *
 * Exception Handling Flow:
 * 1. Controller method executes service method
 * 2. If exception is thrown, it bubbles up
 * 3. Global exception handler catches it
 * 4. Exception handler returns standardized JSON response
 * 5. Response is sent to client with appropriate status code
 */