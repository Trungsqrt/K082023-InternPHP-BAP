<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\AppError;
use Illuminate\Http\Request;
use App\Traits\APIResponsesTrait;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminapiRole
{
    use APIResponsesTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $role = 0): Response
    {
        // check login truoc roi moi check role
        if ($request->getRequestUri() == '/api/admin/refresh') {
            if (auth()->guard('adminapi')->check())
                return $next($request);
            return $this->respondError(config('error.unauthenticated'));
        }

        if (auth()->guard('adminapi')->check()) {
            if (auth()->guard('adminapi')->user()->AUTHORITY == $role)
                return $next($request);
            return $this->respondError(config('error.access_denied'));
        }

        $appError = new AppError(config('error.unauthenticated'));
        return $this->respondError($appError->getErrorData());
    }
}
