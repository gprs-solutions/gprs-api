<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class Authorize
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param Request $request        Request.
     * @param Closure $next           Next operation to be called.
     * @param string  ...$permissions required permissions for the route.
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        try {
            $user  = $request->user();
            $roles = $user->roles()->get();
            foreach ($roles as $role) {
                // If permissions is empty all permissions have already been validated.
                if (empty($permissions)) {
                    break;
                }
                $rolePermissions = $role->permissions()->pluck('code')->toArray();
                $permissions     = array_diff($permissions, $rolePermissions);
            }

            // Not empty means 1 or more permissions asked where not found for this user.
            if (!empty($permissions)) {
                return $this->unauthorized(__('messages.notEnoughPermissions'));
            }
        } catch (Exception $e) {
            Log::error(gethostname() . ' [' . get_class() . '::' . __FUNCTION__ . '] Exception: ' . $e->getMessage());
            return $this->error();
        }

        return $next($request);
    }
}
