<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Users extends Model{

    // 获取用户信息
    public function getUserInfo(){
        try {
            $openid = session("openid");
            $data = DB::table('hs_user')
                ->select("user_name","user_imgs","user_sex","phone","score")
                ->where("openid","=",$openid)
                ->first();
            if(!empty($data)){
                $data->user_name = base64_decode($data->user_name);
            }
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        } catch(\Illuminate\Database\QueryException $ex) {
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }

    // 修改用户信息
//    public function updateUserInfo($params){
//        // DB 修改用户资料..
//        DB::beginTransaction();
//        try {
//            $uid = getUid();
//            $data = DB::table('ylyj_user')
//                ->where("id","=",$uid)
//                ->update($params);
//            DB::commit();
//            return jsonEncodeData(getCodes()["CODE_200"],$data);
//        } catch(\Illuminate\Database\QueryException $ex) {
//            DB::rollback();
//            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
//        }
//    }


//    // 订单列表
    public function userTalkList($params){
        try {
            $data = DB::table('hs_talkadd')
                ->select("id","types_id","core_id","day_time","remark","status","summoney","toptimes","times")
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->where("openid",session("openid"))
                ->where("status",$params["status"])
                ->where("status","<>",5)
                ->get();
            foreach ($data as &$datum){
                $datum->type = DB::table("hs_types")->select("type_name")->where("id",$datum->types_id)->first();
                $datum->core = DB::table("hs_user_core")->select("username","province","city","area","address")->where("id",$datum->core_id)->first();
                unset($datum->types_id);
                unset($datum->core_id);
            }
            $count = DB::table('hs_talkadd')
                ->where("openid",session("openid"))
                ->where("status",$params["status"])
                ->where("status","<>",5)
                ->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$data,$last);
        } catch(\Illuminate\Database\QueryException $ex) {
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }



//    // 删除我的dingdan
    public function delTalkAdd($params){
        DB::beginTransaction();
        try {
            DB::table('hs_talkadd')
                ->where("id","=",$params["id"])
                ->where("openid",session("openid"))
                ->update([
                    "status"=>5
                ]);
            DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
        }
    }


    // get我的收货地址
    public function getCode(){
        try {
            $data = DB::table('hs_user_core')
                ->where("openid",session("openid"))
                ->where("status",1)
                ->get()->toArray();
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        } catch(\Illuminate\Database\QueryException $ex) {
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }


//    // 删除我的收货地址
    public function delCore($params){
        DB::beginTransaction();
        try {
            DB::table('hs_user_core')
                ->where("id","=",$params["id"])
                ->where("openid",session("openid"))
                ->update([
                "status"=>2
            ]);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }

    // 保存收货地址
    public function saveCore($params){
        // DB 修改用户资料..
        DB::beginTransaction();
        try {
            Log::info(json_encode($params));
            $id = DB::table('hs_user_core')->insertGetId([
                "openid"=>session("openid"),
                "username"=>$params["username"],
                "phone"=>$params["phone"],
                "province"=>$params["province"],
                "city"=>$params["city"],
                "area"=>$params["area"],
                "address"=>$params["address"],
                "times"=>Date("Y-m-d H:i:s"),
            ]);
            $data = array(
                "coreid"=>$id,
                "address"=>$params["province"].$params["city"].$params["area"].$params["address"],
            );
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }

    // $userinfo
    public function saveOpenid($userinfo){
        DB::beginTransaction();
        try {

            $user = DB::table('hs_user')->select("id")->where("openid",$userinfo["openid"])->first();
            if(empty($user)){
                $hs_config = DB::table("hs_config")->select("v")->where("id",1)->first();
                $status = 1;
                $score = intval(strval($hs_config->v));
                // 注册
                DB::table('hs_user')->insert([
                    "openid"=>$userinfo["openid"],
                    "user_name"=>$userinfo["user_name"],
                    "user_imgs"=>$userinfo["user_imgs"],
                    "user_sex"=>$userinfo["user_sex"],
                    "score"=>$score
                ]);
            }else{
                // 每天登入
                $hs_config = DB::table("hs_config")->select("v")->where("id",5)->first();
                $status = 5;
                $score = intval(strval($hs_config->v));
                DB::table('hs_user')->where("openid",$userinfo["openid"])->increment('score', $score);
            }

            // 积分记录
            DB::table('hs_user_score')->insert([
                "openid"=>$userinfo["openid"],
                "score"=>$score,
                "status"=>$status,
                "times"=>Date("Y-m-d H:i:s")
            ]);
            DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
        }
    }

    // 分享成功
    public function successShare(){
        DB::beginTransaction();
        try {
            $zsjifen = DB::table("hs_config")->select("v")->where("id",2)->first();
            $zscishu = DB::table("hs_config")->select("v")->where("id",3)->first();
            $zsjifen = intval(strval($zsjifen->v));
            $zscishu = intval(strval($zscishu->v));

            $openid = session("openid");
            $count = DB::table('hs_user_score')
                ->where("openid",$openid)
                ->where("status",3)
                ->where("times",">",Date("Y-m-d"))->count();
            if($count < $zscishu){
                DB::table('hs_user')->where("openid",$openid)->increment('score', $zsjifen);
                // 积分记录
                DB::table('hs_user_score')->insert([
                    "openid"=>$openid,
                    "score"=>$zsjifen,
                    "status"=>3,
                    "times"=>Date("Y-m-d H:i:s")
                ]);
            }
            DB::commit();
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
        }
    }

    public function complaint($params){
        DB::beginTransaction();
        try {
            Log::info($params);
            unset($params["imgfile"]);
            unset($params["_token"]);
            $params["times"] = Date("Y-m-d H:i:s");
            DB::table('hs_complaint')->insert($params);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"],$params);
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }



}
