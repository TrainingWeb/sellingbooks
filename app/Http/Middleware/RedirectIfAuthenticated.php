<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\APIBaseController as APIBaseController;

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
        if($request->user()->role == 1){
            return $next($request);
        }elseif($request->user()->role == 0){
            return $this->sendError('You are have no permission to do this.');
        }elseif(!$request->user()->token()){
            return $this->sendError('You are must login before manage !');
        }
    }
}
