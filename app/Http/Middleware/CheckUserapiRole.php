<?php

namespace App\Http\Middleware;

use App\Utils\AppError;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\APIResponsesTrait;

class CheckUserapiRole
{
    use APIResponsesTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('userapi')->check())
            return $next($request);

        if ($request->getRequestUri() == '/api/refresh')
            $appError = new AppError(config('error.unauthenticated'));
        else
            $appError = new AppError(config('error.access_denied'));


        return $this->respondError($appError->getErrorData());
    }
}
