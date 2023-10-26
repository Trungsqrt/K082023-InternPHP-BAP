<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\APIResponsesTrait;
use Symfony\Component\HttpFoundation\Response;

class PreventAccess
{
    use APIResponsesTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$groups): Response
    {
        foreach ($groups as $group) {
            if ($this->checkGuard($group)) {
                return $this->respondError(config('error.access_denied'));
            }
        }
        return $next($request);
    }

    private function checkGuard($group)
    {
        /**
         * case user: check if userapi -> access deny
         * case admin: check if adminapi && authority == 1 -> access deny
         * case employee: check if adminapi && authority != 1 -> access deny
         * 
         */
        switch ($group) {
            case 'user':
                return auth()->guard('userapi')->check();
            case 'admin':
                return auth()->guard('adminapi')->check() &&  auth()->guard('adminapi')->user()->AUTHORITY == 1;
            case 'employee':
                return auth()->guard('adminapi')->check() &&  auth()->guard('adminapi')->user()->AUTHORITY != 1;
            default:
                return false;
        }
    }
}
