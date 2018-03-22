<?php

namespace App\Http\Controllers\Web;

use App\Models\Users;
use App\Models\Api\Codes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class UsersController extends Controller{

    private $Users;

    function __construct(Users $users){
        $this->Users = $users;
    }


    public function wxlogin(){
//        Log::info(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && empty(session("openid")));
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && empty(session("openid"))){
            // 如果是微信，获取openID
            $redirect_uri = urlencode("".___host."/setOpenid");
            $code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".___wx_appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=".___scope."&state=".___state."#wechat_redirect";
//            header("Location:$code");exit;
            return redirect($code);
        }else{
            return redirect("/index");
        }
    }

    public function setOpenid(Request $request){
//        Log::info("code :" . $request["code"]);
        if(isset($request["code"]) && !empty($request["code"])){
            $getAccess_token = http("https://api.weixin.qq.com/sns/oauth2/access_token",array(
                "appid"=>___wx_appid,
                "secret"=>___wx_appsecret,
                "code"=>$request["code"],
                "grant_type"=>"authorization_code",
            ));
            Log::info($getAccess_token);
            $access = json_decode($getAccess_token);
            if(!empty($access->openid)){
                session(["openid"=>$access->openid]);
            }
            Log::info(session("openid"));
            Log::info("is_weixin_end");
            // https://api.weixin.qq.com/sns/userinfo?=ACCESS_TOKEN&=OPENID&=
            $userinfo = http("https://api.weixin.qq.com/sns/userinfo",array(
                "access_token"=>$access->access_token,
                "openid"=>$access->openid,
                "lang"=>"zh_CN"
            ));
            $userinfo = json_decode($userinfo);
            $info = array(
                "openid"=>$userinfo->openid,
                "user_name"=>base64_encode($userinfo->nickname),
                "user_imgs"=>$userinfo->headimgurl,
                "user_sex"=>$userinfo->sex,
            );

            $this->Users->saveOpenid($info);
            // 跳转主页，预约回收
            return redirect("/index");
        }else{
            return redirect("/wxlogin");
        }
    }

    public function echoopenid(){
        $openid = session("openid");
        echo $openid;
    }

    //
    public function getAddress(){
        echo $this->Users->getCode();
    }

    // 保存用户收货地址
    public function saveCore(Request $request){
        checkEmpty($request["username"],"username","请输入名称");
        checkEmpty($request["province"],"province","选择省");
        checkEmpty($request["city"],"city","选择市");
        //checkEmpty($request["area"],"area","选择区");
        checkEmpty($request["phone"],"phone","输入电话号码");
        checkEmpty($request["address"],"address","输入详细地址");
        $params = $request->all();
        echo $this->Users->saveCore($params);
    }


}
