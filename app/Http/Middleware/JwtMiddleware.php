<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->get('token');

        if (!$token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        $user = User::where('token', $token)->first();
        
        if (!$user) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        }

        try {
            $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error while decoding token. Provided token is invalid.'
            ], 400);
        }

        $user = User::findOrFail($credentials->sub);

        $request->auth = $user;

        return $next($request);
    }
}