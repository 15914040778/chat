<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Users;
class Login
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
        $Users = new Users();
        $login_state = $Users->whether_login();
        //if user not login
        if(empty($login_state)){
          //redirect to login page
          return redirect('login');
        }
        return $next($request);
    }
}
