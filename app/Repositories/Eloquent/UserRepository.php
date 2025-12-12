<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user record.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Find a user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find a user by phone number.
     */
    public function findByPhone(string $phone): ?User
    {
        return User::where('phone_number', $phone)->first();
    }

    /**
     * Find a user by ID.
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Increment failed login attempts for the given user.
     */
    public function incrementLoginAttempts(User $user): void
    {
        $user->increment('login_attempts');
    }

    /**
     * Reset login attempts and unblock the user.
     */
    public function resetLoginAttempts(User $user): void
    {
        $user->update([
            'login_attempts' => 0,
            'blocked_until' => null,
        ]);
    }

    /**
     * Block the user for a specific number of minutes.
     *
     * @param int $minutes Number of minutes to block the user.
     */
    public function blockUser(User $user, int $minutes): void
    {
        $user->update([
            'blocked_until' => Carbon::now()->addMinutes($minutes),
            'login_attempts' => 0, // Reset attempts after applying block
        ]);
    }

    /**
     * Check if the user is currently blocked.
     */
    public function isBlocked(User $user): bool
    {
        // User is not blocked if there is no block timestamp
        if (!$user->blocked_until) {
            return false;
        }

        // User is still blocked if the current time is before the unblock time
        if (Carbon::now()->lessThan($user->blocked_until)) {
            return true;
        }

        // If block period is expired, remove the block
        $this->resetLoginAttempts($user);
        return false;
    }

    /**
     * Mark the user account as active and verified.
     */
    public function activateUser(User $user): void
    {
        $user->update([
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
