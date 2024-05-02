<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponsesTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MeseroCheck
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

        if ($role !== 1 && $role !== 4) { // 4 -> admin
            return $this->errorResponse('El usuario no es mesero.', 403);
        }

        return $next($request);
    }
}
