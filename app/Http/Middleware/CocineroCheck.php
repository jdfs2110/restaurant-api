<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponsesTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CocineroCheck
{
    use ApiResponsesTrait;
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()->getIdRol();

        if ($role !== 2 && $role !== 4) {
            return $this->unauthorizedResponse();
        }

        return $next($request);
    }
}
