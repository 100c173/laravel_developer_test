<?php

namespace App\Repositories\Contracts;

use App\Models\OtpCode;
use App\Models\User;

interface OtpRepositoryInterface
{
    public function createOtp(array $data): OtpCode;
    public function findValidOtp(array $identifier, string $code): ?OtpCode;
    public function markAsVerified(OtpCode $otp): void;
    public function generateCode():string ;
}