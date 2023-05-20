<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Librarian
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
        //dd($user->user_rol);
        if ($user->user_rol->value == UserRoles::LIBRARIAN->value) {
            return $next($request);
        }
        
        return response()->json([
            'message' => 'Unauthorized only accecible by librarian role.',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
