<?php

namespace App\Http\Controllers\Web;

use Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
/***
 * 微信公众号
 * Class WeiXinController
 * @package App\Http\Controllers\Web
 */

define("TOKEN", "ylyj");
class WeiXinController extends Controller{

    public function index(){
        if(Request::input("echostr")){
            $this->valid();
        }else{
            $this->responseMsg();
        }
    }

    public function wxtest(){
//        $user1 = DB::table("ylyj_user")->select("id")->where("openid","o1Trmv3q3jGLbmgjTXYou_hoxiko")->first();
//        $user2 = DB::table("ylyj_user")->select("id")->where("openid","o1Trmv4fid9fihoGO9jmn8KoyO9A")->first();
//        var_dump(empty($user1));
//        var_dump(empty($user2));
        var_dump((base64_encode("🌹我还活着🌹")));

    }

    private function responseMsg(){
        $postStr = Request::getContent();
//        Log::info($postStr);
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $FromUserName = $postObj->FromUserName;
            switch ($postObj->MsgType) {
                case "text":
                    $this->replyText_($postObj);
                    break;
                case "event":
                    $this->replyEvent($postObj);
                    break;
                default:
                    echo "";
                    break;
            }
        }else {
            echo "";
            exit;
        }
    }

    /***
     * 回复文本消息
     * 入参 已经解析好的xml  已知推送过来的类型是text
     * 出参 返回拼接好的xml
     */
    private function replyText_($postObj){
        $fromUsername = $postObj->FromUserName; // 来自哪个公众号
        $toUsername = $postObj->ToUserName; // 我的名字字符串
        $keyword = trim($postObj->Content); // 得到我说了什么
        if(!empty( $keyword )){
            header("content-type:text/xml; charset=uft-8");
//            if(!empty($keyword)){
//                echo $this->replyText($fromUsername,$toUsername,"测试中");
//            }else{
//                echo $this->replyText($fromUsername,$toUsername,"");
//            }
            echo $this->replyText($fromUsername,$toUsername,"测试中");
        }else{
            echo "";
        }
    }

    private function replyEvent($postObj){
        $FromUserName = $postObj->FromUserName; // openid
        DB::beginTransaction();
        try{
            switch($postObj->Event){
                case "subscribe" :
                    Log::info("subscribe $FromUserName");
                    if(""!=$postObj->EventKey){
                        // 表示扫带参二维码关注的
                        $eventKey = substr($postObj->EventKey,8);
                    }else{
                        $eventKey = "other"; // 扫码标记
                    }
                    Log::info("eventKey $eventKey");
                    $user = DB::table("ylyj_user")->select("id")->where("openid",$FromUserName)->first();
                    if(empty($user)){
                        Log::info("$FromUserName 为空，拉取基本信息");
                        // 拉取基本信息
                        $access = getAccessToken();
                        $wx_user = requestAncient("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$FromUserName&lang=zh_CN");
                        Log::info("拉取信息如下 \n ".$wx_user);
                        $wx_user = json_decode($wx_user);
                        $inviter = function () use (&$inviter){
                            $inviter_ = str_random(6);
                            $rs = DB::table("ylyj_user")->where("inviter",$inviter_)->first();
                            if(empty($rs)){
                                return $inviter_;
                            }else{
                                $inviter();
                            }
                        };

                        // 注册
                        if(!isset($wx_user->errcode)){
                            Log::info("拉取ok");
                            $id = DB::table("ylyj_user")->insertGetId([
                                "username"=>base64_encode($wx_user->nickname),
                                "openid"=>$FromUserName,
                                "phone"=>"",
                                "userimg"=>$wx_user->headimgurl,
                                "youbi"=>0,
                                "isfollow"=>1,
                                "superinviter"=>$eventKey,
                                "inviter"=>$inviter(),
                                "times"=>Date("Y-m-d H:i:s"),
                            ]);
                            Log::info($id);
                            if($eventKey!="other" && $id){
                                // 配置赠送数量
                                $ylyj_config = DB::table("ylyj_config")->select("values")->where("id",1)->first();
                                // 回馈邀请者邮币
                                DB::table("ylyj_user")->where("inviter",$eventKey)->increment('youbi', $ylyj_config->values);
                                $super_user = DB::table("ylyj_user")->select("id")->where("inviter",$eventKey)->first();
                                // 添加邮币记录
                                DB::table("ylyj_youbilist")->insert([
                                    "uid"=>$super_user->id,
                                    "ybnumber"=>$ylyj_config->values,
                                    "ybtype"=>5,
                                    "times"=>Date("Y-m-d H:i:s")
                                ]);
                            }
                            echo $this->replyText($FromUserName,$postObj->ToUserName,"欢迎关注 m.tuike520.com");
                        }else{
                            echo $this->replyText($FromUserName,$postObj->ToUserName,"欢迎关注，微信的锅" . $inviter() ."openid:  $FromUserName \n access: $access");
                        }
                    }else{
                        // openid 不为空才更新啊
                        DB::table("ylyj_user")->where("openid",$FromUserName)->update([
                            "isfollow"=>1
                        ]);
                        // 欢迎语句
                        echo $this->replyText($FromUserName,$postObj->ToUserName,"欢迎关注，测试ID" . $user->id);
                    }
                    break;
                case "SCAN" :
                    Log::info("SCAN $FromUserName");
                    $eventKey = $postObj->EventKey; // 扫码标记
                    Log::info("eventKey $eventKey");
                    $user = DB::table("ylyj_user")->select("id")->where("openid",$FromUserName)->first();
                    if(empty($user)){
                        Log::info("$FromUserName 为空，拉取基本信息");
                        // 拉取基本信息
                        $access = getAccessToken();
                        $wx_user = requestAncient("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$FromUserName&lang=zh_CN");
                        Log::info("拉取信息如下 \n ".$wx_user);
                        $wx_user = json_decode($wx_user);
                        $inviter = function () use (&$inviter){
                            $inviter_ = str_random(6);
                            $rs = DB::table("ylyj_user")->where("inviter",$inviter_)->first();
                            if(empty($rs)){
                                return $inviter_;
                            }else{
                                $inviter();
                            }
                        };
                        // 注册
                        if(!isset($wx_user->errcode)){
                            Log::info("拉取ok");
                            $id = DB::table("ylyj_user")->insertGetId([
                                "username"=>base64_encode($wx_user->nickname),
                                "openid"=>$FromUserName,
                                "phone"=>"",
                                "userimg"=>$wx_user->headimgurl,
                                "youbi"=>0,
                                "isfollow"=>1,
                                "superinviter"=>$eventKey,
                                "inviter"=>$inviter(),
                                "times"=>Date("Y-m-d H:i:s"),
                            ]);
                            Log::info($id);
                            if($eventKey!="other" && $id){
                                // 配置赠送数量
                                $ylyj_config = DB::table("ylyj_config")->select("values")->where("id",1)->first();
                                // 回馈邀请者邮币
                                DB::table("ylyj_user")->where("inviter",$eventKey)->increment('youbi', $ylyj_config->values);
                                $super_user = DB::table("ylyj_user")->select("id")->where("inviter",$eventKey)->first();
                                // 添加邮币记录
                                DB::table("ylyj_youbilist")->insert([
                                    "uid"=>$super_user->id,
                                    "ybnumber"=>$ylyj_config->values,
                                    "ybtype"=>5,
                                    "times"=>Date("Y-m-d H:i:s")
                                ]);
                            }
                            echo $this->replyText($FromUserName,$postObj->ToUserName,"欢迎关注 m.tuike520.com");
                        }else{
                            echo $this->replyText($FromUserName,$postObj->ToUserName,"欢迎关注，微信的锅" . $inviter() ."openid:  $FromUserName \n access: $access");
                        }
                    }else{
                        DB::table("ylyj_user")->where("openid",$FromUserName)->update([
                            "isfollow"=>1
                        ]);
                        // 欢迎语句
                        echo $this->replyText($FromUserName,$postObj->ToUserName,"欢迎关注，测试ID" . $user->id);
                    }
                    break;
                case "unsubscribe" :
                    Log::info("unsubscribe $FromUserName");
                    DB::table("ylyj_user")->where("openid",$FromUserName)->update([
                        "isfollow"=>2
                    ]);
                    break;
                default :
                    echo $this->replyText($FromUserName,$postObj->ToUserName,"认识你,挺开心的,虽然这一会不知道说什么好"); ;
            }
            Log::info("=====================$FromUserName======================");
            DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            echo $this->replyText($FromUserName,$postObj->ToUserName,"欢迎关注，测试出错");
            DB::rollback();
        }
    }


    /**
     *  $fromUsername 公众号UserName
     *  $toUsername 用户UserName
     *  $content 回复文字的内容
     */
    private function replyText($fromUsername,$toUsername,$content){
        $time = time();
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $content);
        return $resultStr; // 回复文本格式的格式的格式
    }



    private function valid(){
        $echoStr = Request::input("echostr");
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature(){
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new \Exception('TOKEN is not defined!');
        }
        $signature = Request::input("signature");
        $timestamp = Request::input("timestamp");
        $nonce = Request::input("nonce");

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

}
