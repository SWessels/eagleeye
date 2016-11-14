<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use Session;

class StatusMiddleware
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


        if (Auth::check()) { // check if user is login

            $user_status = $request->user()->status;
            if ($user_status !== 'active') {
                Auth::logout();
                Session::flash('flash_message', 'Your account is not active!');
                return redirect('login');
            }
        }

        return $next($request);
    }
}
