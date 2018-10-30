<?php

namespace App\Http\Middleware;

use App\Util\HttpResponse;
use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $userRole = $request->user()->role;

        foreach ($roles as $role){
            if ($userRole == $role){
                return $next($request);
            }
        }
        return response()->json(HttpResponse::handleResponse("Unauthorized request."), 403);
    }
}
