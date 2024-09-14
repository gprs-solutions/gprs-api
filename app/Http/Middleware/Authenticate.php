<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponse;
use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authenticate
{
    use ApiResponse;

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request Request.
     * @param Closure $next    Next operation to be called.
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->bearerToken();

            if (null === $token) {
                return $this->unauthorized(__('messages.missingJWTToken'));
            }

            $secret = env('JWT_SECRET', '');
            $algo   = env('JWT_ALGO', '');

            if (empty($secret) || empty($algo)) {
                return $this->error(__('messages.invalidJWTVars'));
            }

            $tokenInfo = JWT::decode($token, new Key($secret, $algo));
            $user      = User::findOrFail($tokenInfo->sub);

            // Injects the user in the request for the authorize middleware.
            Auth::setUser($user);
        } catch (ExpiredException $e) {
            Log::info(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return $this->unauthorized(__('messages.tokenExpired'));
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return $this->unauthorized();
        }

        return $next($request);
    }
}
