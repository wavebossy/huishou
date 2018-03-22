<?php

namespace App\Http\Controllers\Ht;

use App\Models\Ht\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class UserController extends Controller{
    // ht.user
    private $user ;

    public function __construct(Users $user){
        $this->user = $user;
    }

    // 跳转页面
    public function userListPage(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $usersData = $this->user->getUsers($par);
        $usersData = json_decode($usersData);
        if(!empty($usersData->data->result)){
            $users = $usersData->data->result;
        }else{
            $users = "";
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $last = $usersData->data->last;
        return view("ht.users.user",compact("users","page","pageSize","last"));
    }

    public function orderPage(){
        $orderPageData = $this->user->orderPage();
        $configs = $this->user->getConfig();
        $orderPageData = json_decode($orderPageData);
        if(!empty($orderPageData->data->result)){
            $orderPage = $orderPageData->data->result;
        }else{
            $orderPage = "";
        }
        return view("ht.types.order_page",compact("orderPage","configs"));
    }

    public function saveUrlPage(Request $request){
        $par = $request->all();
        $this->user->saveUrlPage($par);
        return redirect("/".htname."/orderpage");
    }

    public function saveConfig(Request $request){
        $par = $request->all();
        // 门面图
        if($par["config_id"] == 6){
            if ($request->hasFile('mm_logo')) {
                if ($request->file('mm_logo')->isValid()){
                    // 上传成功
                    // 随机名字 . 后缀
                    $fileName = "/huishou/".Date("YmdHis").substr(md5(time()),5,15).".".$request->file("mm_logo")->extension();// 需要 开启php_fileinfo 扩展 否则会报错
                    // 获取临时上传的路径
                    $fileUrl = $request->file('mm_logo')->path();
                    $bucket = "heikejis";
                    $auth = new Auth(___accessKey, ___secretKey);
                    // 上传七牛
                    $uptoken = $auth->uploadToken($bucket);
                    $uploadMgr = new UploadManager();
                    list($ret, $err) = $uploadMgr->putFile($uptoken, $fileName, $fileUrl);
                    if ($err !== null) {
                        $image="";
                    } else {
                        $image="https://protal.szsldy.com/".$fileName;
//                        $image="http://ovr6bpugl.bkt.clouddn.com/".$fileName;
                        $par["v"] = $image;
                        unset($par["mm_logo"]);
                    }
                }
            }
        }
        $this->user->saveConfig($par);
        return redirect("/".htname."/orderpage");
    }

    public function complaintList(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $usersData = $this->user->complaintList($par);
        $usersData = json_decode($usersData);
        if(!empty($usersData->data->result)){
            $complaints = $usersData->data->result;
        }else{
            $complaints = "";
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $last = empty($usersData->data->last)?1:$usersData->data->last;
        return view("ht.users.complaint",compact("complaints","page","pageSize","last"));
    }

}
