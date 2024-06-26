<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrCollector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!auth()->check() || (auth()->user()?->status != 0 && auth()->user()?->status != 1 && auth()->user()?->status != 2)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
