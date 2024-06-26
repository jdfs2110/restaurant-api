<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponsesTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserIsOwnerCheck
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
        $userId = $request->user()->getId();
        $paramId = $request->route()->parameter('id');

        if ($userId != $paramId && $role != 4 && $role != 3) {
            return $this->unauthorizedResponse();
        }

        return $next($request);
    }
}
