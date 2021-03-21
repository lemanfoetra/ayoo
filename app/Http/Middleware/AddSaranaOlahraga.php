<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class AddSaranaOlahraga
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user != null) {
            if ($user->role_id == 3 || $user->role_id == 2 || $user->role_id == 1) {
                return $next($request);
            }
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'You cannot access this page',
        ]);
    }
}
