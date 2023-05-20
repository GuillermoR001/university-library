<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Student
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('api')->user();

        if ($user->user_rol->value == UserRoles::STUDENT->value) {
            return $next($request);
        }
        
        return response()->json([
            'message' => 'Unauthorized only accecible by student role.',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
