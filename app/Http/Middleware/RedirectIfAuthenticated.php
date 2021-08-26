<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    private const GUARD_USER = 'users';
    private const GUARD_OWNER = 'owners';
    private const GUARD_ADMIN = 'admin';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Userが認証されていない場合のレダイレクト処理
        // check = 認証されていたらTrueを返す
        // routeIsメソッドを使用して、受信リクエストが名前付きルートに一致するかを判定できます。

        if(Auth::guard(self::GUARD_USER)->check() && $request->routeIs('users.*')){
            return redirect(RouteServiceProvider::HOME);
        }

        if(Auth::guard(self::GUARD_OWNER)->check() && $request->routeIs('owner.*')){
            return redirect(RouteServiceProvider::OWNER_HOME);
        }

        if(Auth::guard(self::GUARD_ADMIN)->check() && $request->routeIs('admin.*')){
            return redirect(RouteServiceProvider::ADNIN_HOME);
        }
        // $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         return redirect(RouteServiceProvider::HOME);
        //     }
        // }

        return $next($request);
    }
}
