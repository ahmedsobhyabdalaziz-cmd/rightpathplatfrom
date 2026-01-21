<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ValidateVideoToken
{
    /**
     * Handle an incoming request for video access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get token from query string
        $token = $request->query('token');
        
        if (!$token) {
            abort(403, 'Video access token required.');
        }

        // Check if token exists and is valid in cache
        $cacheKey = "video_token:{$token}";
        $tokenData = Cache::get($cacheKey);
        
        if (!$tokenData) {
            abort(403, 'Video access token expired or invalid.');
        }

        // Verify user matches
        $user = auth()->user();
        if (!$user || $user->id !== $tokenData['user_id']) {
            abort(403, 'Invalid video access token for this user.');
        }

        // Verify video type and ID match
        $type = $request->route('type');
        $id = $request->route('id');
        
        if ($tokenData['type'] !== $type || $tokenData['id'] != $id) {
            abort(403, 'Video access token does not match requested video.');
        }

        return $next($request);
    }
}

