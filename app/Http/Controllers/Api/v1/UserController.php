<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    /**
     * List users (paginated)
     */
    public function index(Request $request): JsonResponse
    {
        $users = $this->userService->getPaginatedUsers(
            $request->all(),
            $request->get('per_page', 15)
        );

        return self::paginated($users, 'users.listed_successfully');
    }

    /**
     * Show single user
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return self::error('users.not_found', 404);
        }

        return self::success($user, 'users.fetched_successfully');
    }

    /**
     * Create new user
     */
    public function store(Request $request): JsonResponse
    {
        $user = $this->userService->createUser($request->all());

        return self::success($user, 'users.created_successfully', 201);
    }

    /**
     * Update user
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return self::error('users.not_found', 404);
        }

        $this->userService->updateUser($user, $request->all());

        return self::success($user->fresh(), 'users.updated_successfully');
    }

    /**
     * Delete user
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return self::error('users.not_found', 404);
        }

        $this->userService->deleteUser($user);

        return self::success(null, 'users.deleted_successfully');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $this->userService->getUserById($id);

        if (!$user) {
            return self::error('users.not_found', 404);
        }

        $this->userService->changePassword($user, $request->password);

        return self::success(null, 'users.password_changed');
    }

    /**
     * Send email to user
     */
    public function sendEmail(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return self::error('users.not_found', 404);
        }

        $sent = $this->userService->sendEmail($user, $request->all());

        if (!$sent) {
            return self::error('users.email_failed', 500);
        }

        return self::success(null, 'users.email_sent');
    }

    /**
     * Show products assigned to user
     */
    public function products(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return self::error('users.not_found', 404);
        }

        $productsData = $this->userService->getUserProducts($user);

        return self::success($productsData, 'users.products_fetched');
    }
}
