<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Menu;

class IsAdmin
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
        $menus = Menu::all();
        if (Auth::check()) {
            $auth = Auth::user();
            if ($auth->is_admin == 1) {
                return $next($request);
            } else {
                // if (isset($menus) && count($menus) > 0) {
                //   return redirect()->route('home', $menus[0]->slug);
                // }
                return redirect('/');
            }
        } else {
            return redirect('login');
        }    
    }
}
