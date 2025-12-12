<?php

namespace App\Repositories\Eloquent;

use App\Models\OtpCode;
use App\Repositories\Contracts\OtpRepositoryInterface;

class OtpRepository implements OtpRepositoryInterface
{
    /**
     * Create or update an OTP entry for the given email or phone.
     * Automatically generates a new code and sets a new expiration time.
     */
    public function createOtp(array $data): OtpCode
    {
        return OtpCode::updateOrCreate(
            attributes: [
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
            ],
            values: [
                'code' => $this->generateCode(),
                'expires_at' => now()->addMinutes(10), // OTP valid for 10 minutes
            ]
        );
    }

    /**
     * Retrieve a valid OTP for the given identifier (email/phone) and code.
     * Returns null if expired or does not match.
     */
    public function findValidOtp(array $identifier, string $code): ?OtpCode
    {
        return OtpCode::where(function ($q) use ($identifier) {
                $q->where('email', $identifier['email'])
                  ->orWhere('phone', $identifier['phone']);
            })
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Generate a 4-digit numeric verification code.
     */
    public function generateCode(): string
    {
        return str_pad(
            string: random_int(1000, 9999),
            length: 4,
            pad_string: '0',
            pad_type: STR_PAD_LEFT
        );
    }

    /**
     * Mark the OTP as used/verified.
     * Recommended: delete or invalidate the OTP after successful verification.
     */
    public function markAsVerified(OtpCode $otp): void
    {
        // Best practice: delete OTP after being used
        $otp->delete();

        // Alternative approach:
        // $otp->update(['verified_at' => now()]);
    }
}
