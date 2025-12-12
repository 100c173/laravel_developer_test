<?php

namespace App\Repositories\Contracts;

use App\Models\User;


interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
    public function findByPhone(string $phone): ?User;
    public function findById(int $id): ?User;
    
    // Manage Login Attempts and Blocks
    public function incrementLoginAttempts(User $user): void;
    public function resetLoginAttempts(User $user): void;
    public function blockUser(User $user, int $minutes): void;
    public function isBlocked(User $user): bool;
    
    // Account Activation and Password Change
    public function activateUser(User $user): void;
    public function updatePassword(User $user, string $newPassword): void;
}