<?php

namespace Domain\Auth\Actions;

use Domain\Auth\Services\LoginAnomalyDetectionService;
use Domain\Auth\Models\LoginEvent;
use Illuminate\Support\Facades\Auth;
use Domain\SharedKernel\Exceptions\DomainException;

class LoginUserAction
{
    public function __construct(
        private LoginAnomalyDetectionService $anomalyDetection
    ) {}

    public function execute(array $credentials, string $ipAddress, string $userAgent): string
    {
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
            
            // Check for lock
            if ($user->account_locked_until && $user->account_locked_until->isFuture()) {
                $this->recordEvent($user->id, $ipAddress, $userAgent, false, 'Account Locked');
                Auth::logout();
                throw new DomainException("Account is locked. Try again later.");
            }
            
            // Reset failed logins
            $user->update(['failed_login_count' => 0]);

            $isAnomaly = $this->anomalyDetection->detectAnomaly($user->id, $ipAddress, $userAgent);
            
            // If anomaly detected, a real system would trigger MFA or email alert
            $this->recordEvent($user->id, $ipAddress, $userAgent, true, $isAnomaly ? 'Anomaly Detected' : null);

            // Generate Sanctum token (Laravel default API token system)
            return $user->createToken('auth_token')->plainTextToken;
        }

        // Handle failure
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        if ($user) {
            $count = $user->failed_login_count + 1;
            $updates = ['failed_login_count' => $count];
            
            if ($count >= 5) {
                $updates['account_locked_until'] = now()->addMinutes(15);
            }
            $user->update($updates);
            $this->recordEvent($user->id, $ipAddress, $userAgent, false, 'Invalid Credentials');
        } else {
            $this->recordEvent(null, $ipAddress, $userAgent, false, 'User Not Found');
        }

        throw new DomainException("Invalid credentials provided.");
    }
    
    private function recordEvent(?string $userId, string $ipAddress, string $userAgent, bool $success, ?string $reason): void
    {
        LoginEvent::create([
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'browser' => $userAgent,
            'success' => $success,
            'failure_reason' => $reason
        ]);
    }
}
