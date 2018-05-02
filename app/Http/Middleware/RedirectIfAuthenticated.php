<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\APIBaseController as APIBaseController;
use Closure;

class RedirectIfAuthenticated extends APIBaseController
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
                return $this->sendError('Your account has been banned ! Please contact us to active.');
            }
            if ($request->user()->role == 1) {
                return $next($request);
            } elseif ($request->user()->role == 0) {
                return $this->sendError('You are have no permission to do this.');
            }
        }
        return $this->sendError('You are must login before manage !');
    }
}
