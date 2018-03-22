<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        $openid = session("openid");
        if(empty($openid)){
            // 跳转登入
//            session(["uid"=>"1"]);
//            echo "需要登入了";exit;
            return redirect("/wxlogin");
        }

        return $next($request);
    }
}
