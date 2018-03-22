<?php

namespace App\Http\Middleware;

use App\Models\Ht\Menu;
use Closure;

class HtCheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $admin = session("admin");
        if(empty($admin)){
            return redirect('/'.htname.'/index');
        }
        $menu = session("menu");
        if(empty($menu)){
            $menu = new Menu();
            $menu = $menu->getMenu();
            $menu = json_decode($menu);
            $menu = $menu->data->result;
            session(["menu"=>$menu]);
        }
//        dd(session("menu"));
        foreach ($menu as $m){
//            var_dump($m->path);
//            var_dump($m);
            if(strpos($request->path(),$m->path) !== false){
                session(["menuName"=>$m->menuname]);
                session(["smallText"=>$m->smalltext]);
                session(["breadcrumb"=>json_decode($m->breadcrumb)]);
//                dd($m->breadcrumb);
//                break;
            }
        }
//        dd(session("breadcrumb"));
        return $next($request);
    }
}
