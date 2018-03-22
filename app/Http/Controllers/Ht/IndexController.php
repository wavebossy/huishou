<?php

namespace App\Http\Controllers\Ht;

use App\Models\Ht\HtIndex;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller{

    private $ht = "";

    public function __construct(HtIndex $htIndex){
        $this->ht = $htIndex;
    }

    public function index(){
        $webht = $this->ht->getConfig();
        $webname = "回收系统";
        return view("ht.index",compact("webht","webname"));
    }

    /***
     * 登入
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request){
        $par = $request->all();
        $data = $this->ht->login($par);
        $d = json_decode($data);
        if(empty($d->errorcode)){
            session(["admin"=>$d->data->result]);
            return redirect("/".htname."/homepage")->with('errorinfo', '账号或密码错误');
        }else{
            return redirect("/".htname."/index")->with('errorinfo', '账号或密码错误');
        }
    }



}
