<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


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

    // Admin Management Methods
    public function update(User $user, array $data): bool;
    public function delete(User $user): bool;
    public function all(array $filters = []): Collection;
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function getByRole(string $role): Collection;
    public function getStatistics(): array;
    public function countByVerificationStatus(bool $verified): int;
    public function countByCountry(?string $country = null): int;
    public function countByCity(?string $city = null): int;
    public function getUserProducts(User $user): Collection;
}