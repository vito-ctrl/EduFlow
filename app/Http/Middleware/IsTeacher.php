<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if(!$user || $user->role !== 'teacher'){
            return response()->json([
                'error' => 'Access denied. Teachers only.'
            ], 403);
        }
        return $next($request);
    }
}
