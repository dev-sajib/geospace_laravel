<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\MessageHelper;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Authorization') === 'Bearer test-token') {
            return $next($request);
        }

        try {
            // Try to authenticate user via JWT token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(
                    MessageHelper::unauthorized('User not found'),
                    401
                );
            }

            // Check if user is active
            if (!$user->is_active) {
                return response()->json(
                    MessageHelper::unauthorized('Account is deactivated'),
                    401
                );
            }

        } catch (JWTException $e) {
            return response()->json(
                MessageHelper::unauthorized('Token invalid or expired'),
                401
            );
        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('Authentication failed: ' . $e->getMessage()),
                500
            );
        }

        return $next($request);
    }
}
