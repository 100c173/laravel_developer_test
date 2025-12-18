<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected User $model)
    {
    }
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

    /**
     * Update user information.
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete user.
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Get all users with filters.
     */
    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery();
        return $this->applyFilters($query, $filters)->get();
    }

    /**
     * Get paginated users.
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with('products');
        return $this->applyFilters($query, $filters)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
    /**
     * Get users by role.
     */
    public function getByRole(string $role): Collection
    {
        return $this->model->role($role)->get();
    }

    /**
     * Get users statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('is_active', true)->count(),
            'inactive' => $this->model->where('is_active', false)->count(),
            'verified' => $this->model->whereNotNull('email_verified_at')->count(),
            'unverified' => $this->model->whereNull('email_verified_at')->count(),
            'blocked' => $this->model->whereNotNull('blocked_until')
                ->where('blocked_until', '>', Carbon::now())
                ->count(),
            'with_products' => $this->model->has('products')->count(),

            // استخدام العلاقات مع جدول countries
            'by_country' => $this->model->with('country')
                ->select('country_id', DB::raw('count(*) as total'))
                ->whereNotNull('country_id')
                ->groupBy('country_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'country' => $item->country->name ?? 'Unknown',
                        'country_id' => $item->country_id,
                        'total' => $item->total
                    ];
                })
                ->toArray(),

            
            'by_city' => $this->model->with('city')
                ->select('city_id', DB::raw('count(*) as total'))
                ->whereNotNull('city_id')
                ->groupBy('city_id')
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'city' => $item->city->name ?? 'Unknown',
                        'city_id' => $item->city_id,
                        'country' => $item->city->country->name ?? 'Unknown',
                        'total' => $item->total
                    ];
                })
                ->toArray(),
        ];
    }

    /**
     * Count users by city.
     */
    public function countByCity(?string $city = null): int
    {
        $query = $this->model->whereNotNull('city');

        if ($city) {
            $query->where('city', $city);
        }

        return $query->count();
    }

    /**
     * Count users by country.
     */
    public function countByCountry(?string $country = null): int
    {
        $query = $this->model->whereNotNull('country');

        if ($country) {
            $query->where('country', $country);
        }

        return $query->count();
    }

    /**
     * Get user's products.
     */
    public function getUserProducts(User $user): Collection
    {
        return $user->products()->with('images')->get();
    }

    /**
     * Apply filters to query.
     */
    protected function applyFilters($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['verified'])) {
            $query->whereNotNull('email_verified_at');
        } elseif (isset($filters['verified']) && $filters['verified'] === '0') {
            $query->whereNull('email_verified_at');
        }

        if (!empty($filters['active'])) {
            $query->where('is_active', true);
        } elseif (isset($filters['active']) && $filters['active'] === '0') {
            $query->where('is_active', false);
        }

        if (!empty($filters['blocked'])) {
            $query->whereNotNull('blocked_until')
                ->where('blocked_until', '>', Carbon::now());
        }

        if (!empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (!empty($filters['role'])) {
            $query->role($filters['role']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['has_products'])) {
            $query->has('products');
        }

        return $query;
    }

    /**
     * Count users by verification status.
     */
    public function countByVerificationStatus(bool $verified): int
    {
        return $verified
            ? $this->model->whereNotNull('email_verified_at')->count()
            : $this->model->whereNull('email_verified_at')->count();
    }



}
