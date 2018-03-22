<?php

namespace App\Http\Controllers\Web;

use App\Models\HuiShou;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 首页，预约
class IndexController extends Controller{


    private $huishou ;

    public function __construct(HuiShou $huiShou){
        $this->huishou = $huiShou;
    }

    public function index(){
        //------------------------------------------------------------------------------------------------------
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ticket = getJsApiTicket();
        $noncestr = str_random(10); // 10位随机数
        $timestamp = time();
        $string1 = "jsapi_ticket=$ticket&noncestr=$noncestr&timestamp=$timestamp&url=$url";
        $signature = sha1($string1);
        $types = webData($this->huishou->getTypes());
        $mmlogo = $this->huishou->getLogo()->v;// $mmlogo
        $__appid = ___wx_appid;
        $__timestamp = $timestamp;
        $__noncestr = $noncestr;
        $__signature = $signature;
        return view("hs.index",compact("types","__appid","__timestamp","__noncestr","__signature","mmlogo"));
    }

    // 获取该分类列表
    public function getSubscribe(Request $request){
        echo $this->huishou->getSubscribe($request["id"]);
    }

    // 下单
    public function saveTalkadd(Request $request){
        $types_id = $request["types_id"];
        return view("hs.talkadd",compact("types_id"));
    }


}
