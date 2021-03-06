<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\APIBaseController as APIBaseController;
use Closure;

class ProtectedUserLogin extends APIBaseController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->user()) {
            if ($request->user()->role == 2) {
                return $this->sendErrorPermission('Your account has been banned ! Please contact us to active.');
            }
            return $next($request);
        }
        return $this->sendErrorAuth('You are must login before manage.');
    }
}
