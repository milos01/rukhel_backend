<?php

namespace App\Http\Middleware;

use App\Util\HttpResponse;
use Carbon\Carbon;
use Closure;

class CheckOAuthToken
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
        $now = Carbon::now();
        $accessTokenExpiry = $request->user()->token()->expires_at;

        if ($now->gte($accessTokenExpiry)){
            return response()->json(HttpResponse::handleResponse("Token expired."), 401);
        }
        return $next($request);
    }
}
