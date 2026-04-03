<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckTokenVersion
{
    public function handle($request, Closure $next)
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 401);
            }

            $payload = JWTAuth::parseToken()->getPayload();

            $user = auth()->user();

            if ($payload['token_version'] != $user->token_version) {
                return response()->json(['error' => 'Token expired'], 401);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalid'], 401);
        } catch (\Exception) {
            return response()->json(['error' => 'Token invalid'], 401);
        }

        return $next($request);
    }
}