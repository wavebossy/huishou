<?php

namespace App\Models\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Codes extends Model{


    /***
     * 检查验证码是否正确
     * 新用户则注册，老用户则返回
     * @param $params
     * @return string
     */
    public function checkCodes($params){
        try {
            $codes = DB::table('ylyj_codes')
                ->where("userip","=",getIP())
                ->where("phone","=",$params["phone"])
                ->where("codes","=",$params["codes"])
                ->first();
            $data = [];
            // 验证码ok
            if(!empty($codes)){
                $inviter = function () use (&$inviter){
                    $inviter_ = str_random(6);
                    $rs = DB::table("ylyj_user")->where("inviter",$inviter_)->first();
                    if(empty($rs)){
                        return $inviter_;
                    }else{
                        $inviter();
                    }
                };
                $openid = session("openid");
                if(!empty($openid)){
                    // update user info
                    $data = DB::table("ylyj_user")->where("openid",$openid)->first();
                    if(!empty($data)){
                        // 存在则，绑定手机号
                        DB::table("ylyj_user")->where("openid",$openid)->update([
                            "phone"=>$params["phone"],
                        ]);
                        $data = DB::table("ylyj_user")->where("openid",$openid)->first();
                    }else{
                        $data = DB::table("ylyj_user")->where("phone",$params["phone"])->first();
                        if(!empty($data)){
                            // 非微信浏览器之前就存在账户，则update  openid
                            DB::table("ylyj_user")->where("phone",$params["phone"])->update([
                                "openid"=>$openid,
                            ]);
                            $data = DB::table("ylyj_user")->where("openid",$openid)->first();
                        }else{
                            $access = getAccessToken();
                            $wx_user = requestAncient("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$openid&lang=zh_CN");
                            $wx_user = json_decode($wx_user);
                            if(!isset($wx_user->errcode)){
                                $id = DB::table("ylyj_user")->insertGetId([
                                    "username"=>base64_encode($wx_user->nickname),
                                    "openid"=>$openid,
                                    "phone"=>"",
                                    "userimg"=>$wx_user->headimgurl,
                                    "youbi"=>0,
                                    "isfollow"=>1,
                                    "superinviter"=>"error", // 关注的时候没有自动注册，反而在登入的时候才获取
                                    "inviter"=>$inviter(),
                                    "times"=>Date("Y-m-d H:i:s"),
                                ]);
                                $data = DB::table("ylyj_user")->where("id",$id)->first();
                            }else{
                                $id = DB::table("ylyj_user")->insertGetId([
                                    "username"=>"",
                                    "phone"=>$params["phone"],
                                    "userimg"=>"",
                                    "youbi"=>0,
                                    "superinviter"=>"",
                                    "inviter"=>$inviter(),
                                    "times"=>Date("Y-m-d H:i:s"),
                                ]);
                                $data = DB::table("ylyj_user")->where("id",$id)->first();
                            }
                        }
                    }
                }else{
                    $data = DB::table("ylyj_user")->where("phone",$params["phone"])->first();
                    if(empty($data)){
                        $id = DB::table("ylyj_user")->insertGetId([
                            "username"=>"",
                            "phone"=>$params["phone"],
                            "userimg"=>"",
                            "youbi"=>0,
                            "superinviter"=>"",
                            "inviter"=>$inviter(),
                            "times"=>Date("Y-m-d H:i:s"),
                        ]);
                        $data = DB::table("ylyj_user")->where("id",$id)->first();
                    }
                }

                return jsonEncodeData(getCodes()["CODE_200"],$data);
            }else{
                return jsonEncodeData(getCodes()["CODE_200"],$data,"","1003","验证码错误,请重新登入");
            }
        } catch(\Illuminate\Database\QueryException $ex) {
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }

    // 保存验证码
    public function saveCodes($params){
        DB::beginTransaction();
        try {
            $data = DB::table('ylyj_codes')
                ->where("userip","=",$params["userip"])
                ->where("phone","=",$params["phone"])
                ->where("times",">",Date("Y-m-d H:i:s",time()-60))
                ->first();
            if(!empty($data)){
                return jsonEncodeData(getCodes()["CODE_200"],$data,"","1002","请等待一分钟内再次获取!");
            }else{
                DB::table("ylyj_codes")->insert([
                    "userip"=>$params["userip"],
                    "phone"=>$params["phone"],
                    "times"=>$params["times"],
                    "codes"=>$params["codes"],
                ]);
                DB::commit();
                return jsonEncodeData(getCodes()["CODE_200"]);
            }
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }
}
