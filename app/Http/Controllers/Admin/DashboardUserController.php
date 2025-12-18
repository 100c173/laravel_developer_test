<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashboardUserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    /**
     * Display users listing page.
     */
    public function index()
    {
        $stats = $this->userService->getStatistics();
        return view('dashboard.users.index', compact('stats'));
    }

    /**
     * Return DataTables JSON response.
     */
    public function datatable(Request $request)
    {
        $filters = [
            'search' => $request->input('search.value'),
            'verified' => $request->input('verified'),
            'active' => $request->input('active'),
            'blocked' => $request->input('blocked'),
            'country_id' => $request->input('country_id'),
            'city_id' => $request->input('city_id'),
            'role' => $request->input('role'),
            'has_products' => $request->input('has_products'),
        ];

        $query = User::with(['country', 'city'])->withCount('products');

        // Apply filters
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', "%{$filters['search']}%")
                    ->orWhere('last_name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%")
                    ->orWhere('phone_number', 'like', "%{$filters['search']}%");
            });
        }

        if ($filters['verified'] === '1') {
            $query->whereNotNull('email_verified_at');
        } elseif ($filters['verified'] === '0') {
            $query->whereNull('email_verified_at');
        }

        if ($filters['active'] === '1') {
            $query->where('is_active', true);
        } elseif ($filters['active'] === '0') {
            $query->where('is_active', false);
        }

        if ($filters['blocked'] === '1') {
            $query->whereNotNull('blocked_until')
                ->where('blocked_until', '>', Carbon::now());
        }

        if (!empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (!empty($filters['role'])) {
            $query->role($filters['role']);
        }

        if (!empty($filters['has_products'])) {
            $query->has('products');
        }

        return DataTables::of($query)
            ->addColumn('name', function (User $user) {
                return $user->first_name . ' ' . $user->last_name;
            })
            ->addColumn('email', function (User $user) {
                return $user->email;
            })
            ->addColumn('phone', function (User $user) {
                return $user->phone_number ?? '-';
            })
            ->addColumn('country', function (User $user) {
                return $user->country->name ?? '-';
            })
            ->addColumn('city', function (User $user) {
                return $user->city->name ?? '-';
            })
            ->addColumn('active', function (User $user) {
                return $user->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('verified', function (User $user) {
                return $user->email_verified_at
                    ? '<span class="badge bg-success">Verified</span>'
                    : '<span class="badge bg-warning">Unverified</span>';
            })
            ->addColumn('blocked', function (User $user) {
                if ($user->blocked_until && Carbon::now()->lessThan($user->blocked_until)) {
                    $remaining = Carbon::parse($user->blocked_until)->diffForHumans();
                    return '<span class="badge bg-danger">Blocked (' . $remaining . ')</span>';
                }
                return '<span class="badge bg-secondary">Not Blocked</span>';
            })
            ->addColumn('products_count', function (User $user) {
                return $user->products_count;
            })
            ->addColumn('role', function (User $user) {
                return $user->getRoleNames()->first() ?? 'User';
            })
            ->addColumn('created_at', function (User $user) {
                return $user->created_at->format('Y-m-d H:i');
            })
            ->addColumn('actions', function (User $user) {
                // إنشاء الروابط
                $viewUrl = route('admin.dashboard.users.show', $user);
                $editUrl = route('admin.dashboard.users.edit', $user);
                $deleteUrl = route('admin.dashboard.users.destroy', $user);
                $changePasswordUrl = route('admin.dashboard.users.change-password', $user);
                $sendEmailUrl = route('admin.dashboard.users.send-email', $user);
                $blockUrl = route('admin.dashboard.users.block', $user);
                $unblockUrl = route('admin.dashboard.users.unblock', $user);
                $activateUrl = route('admin.dashboard.users.activate', $user);
                $deactivateUrl = route('admin.dashboard.users.deactivate', $user);
                $verifyUrl = route('admin.dashboard.users.verify', $user);
                $unverifyUrl = route('admin.dashboard.users.unverify', $user);

                // بناء HTML للـ actions
                $html = '<div class="btn-group btn-group-sm" role="group">';

                // زر View
                $html .= '<a href="' . $viewUrl . '" class="btn btn-info" title="View" data-bs-toggle="tooltip">
                        <i class="fas fa-eye"></i>
                      </a>';

                // زر Edit
                $html .= '<a href="' . $editUrl . '" class="btn btn-primary" title="Edit" data-bs-toggle="tooltip">
                        <i class="fas fa-edit"></i>
                      </a>';

                // Dropdown for more actions
                $html .= '<div class="btn-group" role="group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu">';

                // Change Password
                $html .= '<li>
                        <a class="dropdown-item" href="' . $changePasswordUrl . '">
                            <i class="fas fa-key me-2"></i> Change Password
                        </a>
                      </li>';

                // Send Email
                $html .= '<li>
                        <a class="dropdown-item" href="' . $sendEmailUrl . '">
                            <i class="fas fa-envelope me-2"></i> Send Email
                        </a>
                      </li>';

                // Separator
                $html .= '<li><hr class="dropdown-divider"></li>';

                // Block/Unblock Action
                if ($user->blocked_until && Carbon::now()->lessThan($user->blocked_until)) {
                    $html .= '<li>
                            <button class="dropdown-item text-success unblock-btn" 
                                    data-url="' . $unblockUrl . '">
                                <i class="fas fa-unlock me-2"></i> Unblock Account
                            </button>
                          </li>';
                } else {
                    $html .= '<li>
                            <button class="dropdown-item text-warning block-btn" 
                                    data-url="' . $blockUrl . '">
                                <i class="fas fa-lock me-2"></i> Block Account
                            </button>
                          </li>';
                }

                // Activate/Deactivate Action
                if ($user->is_active) {
                    $html .= '<li>
                            <button class="dropdown-item text-danger deactivate-btn" 
                                    data-url="' . $deactivateUrl . '">
                                <i class="fas fa-ban me-2"></i> Deactivate
                            </button>
                          </li>';
                } else {
                    $html .= '<li>
                            <button class="dropdown-item text-success activate-btn" 
                                    data-url="' . $activateUrl . '">
                                <i class="fas fa-check me-2"></i> Activate
                            </button>
                          </li>';
                }

                // Verify/Unverify Email
                if ($user->email_verified_at) {
                    $html .= '<li>
                            <button class="dropdown-item text-warning unverify-btn" 
                                    data-url="' . $unverifyUrl . '">
                                <i class="fas fa-times-circle me-2"></i> Unverify Email
                            </button>
                          </li>';
                } else {
                    $html .= '<li>
                            <button class="dropdown-item text-success verify-btn" 
                                    data-url="' . $verifyUrl . '">
                                <i class="fas fa-check-circle me-2"></i> Verify Email
                            </button>
                          </li>';
                }

                // Separator
                $html .= '<li><hr class="dropdown-divider"></li>';

                // Delete Action
                $html .= '<li>
                        <button class="dropdown-item text-danger delete-btn" 
                                data-url="' . $deleteUrl . '">
                            <i class="fas fa-trash me-2"></i> Delete User
                        </button>
                      </li>';

                $html .= '</ul></div></div>';

                // Add block modal HTML
                $html .= $this->getBlockModalHtml($user);

                return $html;
            })
            ->rawColumns(['active', 'verified', 'blocked', 'actions'])
            ->make(true);
    }

    /**
     * Generate block modal HTML for a user
     */
    private function getBlockModalHtml(User $user): string
    {
        return '
    <div class="modal fade" id="blockModal-' . $user->id . '" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Block User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="blockForm-' . $user->id . '" 
                      action="' . route('admin.dashboard.users.block', $user) . '" 
                      method="POST">
                    ' . csrf_field() . '
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="minutes-' . $user->id . '" class="form-label">
                                Block Duration (minutes)
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="minutes-' . $user->id . '" 
                                   name="minutes" 
                                   min="1" 
                                   max="1440" 
                                   value="60" 
                                   required>
                            <small class="text-muted">
                                Maximum 24 hours (1440 minutes)
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="reason-' . $user->id . '" class="form-label">
                                Reason (optional)
                            </label>
                            <textarea class="form-control" 
                                      id="reason-' . $user->id . '" 
                                      name="reason" 
                                      rows="3" 
                                      placeholder="Enter reason for blocking..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-lock me-1"></i>
                            Block User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>';
    }

    /**
     * Show user creation form.
     */
    public function create()
    {
        return view('admin.dashboard.users.create');
    }

    /**
     * Store new user.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', __('User created successfully'));
    }

    /**
     * Display user details.
     */
    public function show(User $user)
    {
        $user->load('products.images');
        $productsData = $this->userService->getUserProducts($user);

        return view('dashboard.users.show', compact('user', 'productsData'));
    }

    /**
     * Show edit form.
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()
            ->route('admin.dashboard.users.edit', $user)
            ->with('success', __('User updated successfully'));
    }

    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return response()->json([
            'success' => true,
            'message' => __('User deleted successfully')
        ]);
    }

    /**
     * Show change password form.
     */
    public function changePasswordForm(User $user)
    {
        return view('dashboard.users.change-password', compact('user'));
    }

    /**
     * Change user password.
     */
    public function changePassword(ChangePasswordRequest $request, User $user)
    {
        $this->userService->changePassword($user, $request->password);

        return redirect()
            ->route('admin.dashboard.users.show', $user)
            ->with('success', __('Password changed successfully'));
    }

    /**
     * Show send email form.
     */
    public function sendEmailForm(User $user)
    {
        return view('dashboard.users.send-email', compact('user'));
    }

    /**
     * Send email to user.
     */
    public function sendEmail(SendEmailRequest $request, User $user)
    {
        $sent = $this->userService->sendEmail($user, $request->validated());

        if ($sent) {
            return redirect()
                ->route('admin.dashboard.users.show', $user)
                ->with('success', __('Email sent successfully'));
        }

        return back()
            ->with('error', __('Failed to send email'));
    }

    /**
     * Block user account.
     */
    public function block(Request $request, User $user)
    {
        $this->userService->blockUserAccount($user, $request['minutes']);

        return response()->json([
            'success' => true,
            'message' => __('User blocked successfully for ' . $request['minutes'] . ' minutes')
        ]);
    }

    /**
     * Unblock user account.
     */
    public function unblock(User $user)
    {
        $this->userService->unblockUserAccount($user);

        return response()->json([
            'success' => true,
            'message' => __('User unblocked successfully')
        ]);
    }

    /**
     * Activate user account.
     */
    public function activate(User $user)
    {
        $this->userService->activateUser($user);

        return response()->json([
            'success' => true,
            'message' => __('User activated successfully')
        ]);
    }

    /**
     * Deactivate user account.
     */
    public function deactivate(User $user)
    {
        $this->userService->deactivateUser($user);

        return response()->json([
            'success' => true,
            'message' => __('User deactivated successfully')
        ]);
    }

    /**
     * Verify user email.
     */
    public function verify(User $user)
    {
        $this->userService->verifyUserEmail($user);

        return response()->json([
            'success' => true,
            'message' => __('User email verified successfully')
        ]);
    }

    /**
     * Unverify user email.
     */
    public function unverify(User $user)
    {
        $this->userService->unverifyUserEmail($user);

        return response()->json([
            'success' => true,
            'message' => __('User email unverified successfully')
        ]);
    }

    /**
     * Export users to CSV.
     */
    public function export(Request $request)
    {
        $filters = $request->only(['search', 'verified', 'active', 'blocked', 'country', 'city', 'role']);

        $csvContent = $this->userService->exportToCsv($filters);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users-' . date('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Get user's products via AJAX.
     */
    public function getProducts(User $user)
    {
        $products = $this->userService->getUserProducts($user);

        return response()->json($products);
    }
}
