<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VideoTokenService
{
    /**
     * Generate a temporary access token for a video.
     *
     * @param string $type 'lesson' or 'module'
     * @param int $id The lesson or module ID
     * @param int $userId The user ID
     * @param int $expiryMinutes How long the token is valid (default 15 minutes)
     * @return string The access token
     */
    public function generateToken(string $type, int $id, int $userId, int $expiryMinutes = 15): string
    {
        // Generate a secure random token
        $token = Str::random(64);
        
        // Store token data in cache
        $cacheKey = "video_token:{$token}";
        $tokenData = [
            'type' => $type,
            'id' => $id,
            'user_id' => $userId,
            'created_at' => now()->toDateTimeString(),
        ];
        
        Cache::put($cacheKey, $tokenData, now()->addMinutes($expiryMinutes));
        
        return $token;
    }

    /**
     * Generate a video URL with access token.
     *
     * @param string $routeName The route name
     * @param string $type 'lesson' or 'module'
     * @param int $id The lesson or module ID
     * @param int $userId The user ID
     * @param array $extraParams Additional query parameters
     * @return string The full URL with token
     */
    public function generateUrl(string $routeName, string $type, int $id, int $userId, array $extraParams = []): string
    {
        $token = $this->generateToken($type, $id, $userId);
        
        $params = array_merge([
            'type' => $type,
            'id' => $id,
            'token' => $token,
        ], $extraParams);
        
        return route($routeName, $params);
    }
}

