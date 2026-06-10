<?php

namespace Domain\Auth\Services;

use Domain\Auth\Models\LoginEvent;

class LoginAnomalyDetectionService
{
    public function detectAnomaly(string $userId, string $ipAddress, string $userAgent): bool
    {
        // Simple implementation for prototype
        // A real system would check distance between IPs (impossible travel)
        // or check if device fingerprint is completely new.
        
        $pastLogins = LoginEvent::where('user_id', $userId)
            ->where('success', true)
            ->exists();
            
        if (!$pastLogins) {
            // First login ever is not an anomaly
            return false;
        }
        
        $knownIp = LoginEvent::where('user_id', $userId)
            ->where('ip_address', $ipAddress)
            ->exists();
            
        return !$knownIp; // Returns true if this IP was never seen before
    }
}
