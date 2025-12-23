<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\SendOtpCodeToMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use App\Repositories\Contracts\OtpRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected OtpRepositoryInterface $otpRepository,
    ) {
    }

    /**
     * Register a new user.
     */
    public function register(array $data): User
    {

        // Create inactive user
        $user = $this->userRepository->create($data);

        // Send OTP for verification
        $this->sendVerificationCode($data);

        return $user;
    }

    public function resendCode (array $data)
    {
        $this->sendVerificationCode($data);
    }

    /**
     * Send verification code via Email or SMS.
     */
    protected function sendVerificationCode(array $data): void
    {
        
        $otp = $this->otpRepository->createOtp($data);

        // Should be queued using Jobs
        if (isset($data['identifier']['email'])) {

             Notification::route('mail',$data['identifier']['email'])->notify(new SendOtpCodeToMail( $otp->code));
        }

        if (isset($data['identifier']['phone'])) {
            // SMS::send($data['phone'], $otp->code);
        }
    }

    /**
     * Verify user account using OTP.
     */
    public function verifyAccount(array $identifier, string $code): string
    {
        $user = $this->findUserByIdentifier($identifier);

        if (!$user) {
            throw ValidationException::withMessages([
                'identifier' => 'User not found.',
            ]);
        }

        $otp = $this->otpRepository->findValidOtp($identifier, $code);

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired verification code.',
            ]);
        }

        $this->userRepository->activateUser($user);
        $this->otpRepository->markAsVerified($otp);

        return $this->createTokenWithExpiration($user);
    }

    /**
     * Login using email or phone number.
     */
    public function login(array $identifier, string $password): string
    {
        $user = $this->findUserByIdentifier($identifier);

        if (!$user || !Hash::check($password, $user->password)) {
            if ($user) {
                $this->handleFailedLogin($user);
            }

            throw ValidationException::withMessages([
                'login' => 'Invalid credentials.',
            ]);
        }

        if ($this->userRepository->isBlocked($user)) {
            throw ValidationException::withMessages([
                'login' => 'Your account is temporarily blocked.',
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'login' => 'Your account is not verified.',
            ]);
        }

        // Reset attempts after successful login
        $this->userRepository->resetLoginAttempts($user);

        return $this->createTokenWithExpiration($user);
    }

    /**
     * Logout user (revoke current token).
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Get authenticated user (me endpoint).
     */
    public function me(User $user): User
    {
        return $user;
    }

    /**
     * Generate token with expiration time.
     */
    protected function createTokenWithExpiration(User $user): string
    {
        $token = $user->createToken('auth_token', ['*']);

        // Save expiration manually
        $token->accessToken->update([
            'expires_at' => Carbon::now()->addHours(2) // 2 hours token validity
        ]);

        return $token->plainTextToken;
    }

    /**
     * Handle failed login attempts.
     */
    protected function handleFailedLogin(User $user): void
    {
        $this->userRepository->incrementLoginAttempts($user);

        if ($user->login_attempts >= 3) {
            $blockMinutes = rand(15, 60);
            $this->userRepository->blockUser($user, $blockMinutes);
        }
    }

    /**
     * Change user password.
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        if (Hash::check($newPassword, $user->password)) {
            throw ValidationException::withMessages([
                'new_password' => 'New password must be different.',
            ]);
        }

        $this->userRepository->updatePassword($user, $newPassword);
    }

    /**
     * Forgot password (send OTP).
     */
    public function forgotPassword(array $identifier): void
    {

        $user = $this->findUserByIdentifier($identifier);

        if (!$user) {
            throw ValidationException::withMessages([
                'user' => 'user not found .',
            ]);
            //return ; // security best practice
        }

        $this->sendVerificationCode($identifier);
    }

    /**
     * Reset password using OTP.
     */
    public function resetPassword(array $identifier, string $code, string $newPassword): string
    {
        
        $user = $this->findUserByIdentifier($identifier);

        if (!$user) {
            throw ValidationException::withMessages([
                'identifier' => 'User not found.',
            ]);
        }

        $otp = $this->otpRepository->findValidOtp($identifier, $code);

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired reset code.',
            ]);
        }

        $this->userRepository->updatePassword($user, $newPassword);
        $this->otpRepository->markAsVerified($otp);

        return $this->createTokenWithExpiration($user);
    }

    /**
     * Find user by email or phone number.
     */
    protected function findUserByIdentifier(array $identifier): ?User
    {
       
        if (isset($identifier['email'])) {
            return $this->userRepository->findByEmail($identifier['email']);
        }
        
        if (isset($identifier['phone'])) {
            return $this->userRepository->findByPhone($identifier['phone']);
        }
 
        return null;
    }
}
