<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\UserNotification;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService
{
        public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get paginated users for admin.
     */
    public function getPaginatedUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->paginate($filters, $perPage);
    }

    /**
     * Get user by ID.
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Create new user.
     */
    public function  createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $data['is_active'] ?? true;
        $data['email_verified_at'] = $data['email_verified'] ? Carbon::now() : null;
        
        $user = $this->userRepository->create($data);
        
        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }
        
        return $user;
    }

    /**
     * Update user information.
     */
    public function updateUser(User $user, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        if (isset($data['email_verified'])) {
            $data['email_verified_at'] = $data['email_verified'] ? Carbon::now() : null;
            unset($data['email_verified']);
        }
        
        $updated = $this->userRepository->update($user, $data);
        
        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }
        
        return $updated;
    }

    /**
     * Delete user.
     */
    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    /**
     * Change user password.
     */
    public function changePassword(User $user, string $password): bool
    {
        $this->userRepository->updatePassword($user, $password);
        $user->notify(new PasswordChangedNotification());
        return true;
    }

    /**
     * Send email to user.
     */
    public function sendEmail(User $user, array $emailData): bool
    {
        try {
            $user->notify(new UserNotification($emailData)); 
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send email to user: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user's products with statistics.
     */
    public function getUserProducts(User $user): array
    {
        $products = $this->userRepository->getUserProducts($user);
        
        return [
            'total' => $products->count(),
            'products' => $products,
            'total_value' => $products->sum('price'),
            'average_price' => $products->avg('price') ?? 0,
        ];
    }


    /**
     * Activate user account.
     */
    public function activateUser(User $user): bool
    {
        $this->userRepository->activateUser($user);
        return true;
    }

    /**
     * Deactivate user account.
     */
    public function deactivateUser(User $user): bool
    {
        $user->update(['is_active' => false]);
        return true;
    }

    /**
     * Block user account for specified minutes.
     */
    public function blockUserAccount(User $user, int $minutes): bool
    {
        $this->userRepository->blockUser($user, $minutes);
        return true;
    }

    /**
     * Unblock user account.
     */
    public function unblockUserAccount(User $user): bool
    {
        $this->userRepository->resetLoginAttempts($user);
        return true;
    }

    /**
     * Check if user is blocked.
     */
    public function isUserBlocked(User $user): bool
    {
        return $this->userRepository->isBlocked($user);
    }

    /**
     * Verify user email.
     */
    public function verifyUserEmail(User $user): bool
    {
        $user->update(['email_verified_at' => Carbon::now()]);
        return true;
    }

    /**
     * Unverify user email.
     */
    public function unverifyUserEmail(User $user): bool
    {
        $user->update(['email_verified_at' => null]);
        return true;
    }
}


