<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Talk extends Model{

//    // 获取用户信息
//    public function getUserInfo(){
//        try {
//            $openid = session("openid");
//            $data = DB::table('hs_user')
//                ->select("user_name","user_imgs","user_sex")
//                ->where("openid",$openid)
//                ->first();
//            $data->username = base64_decode($data->username);
//            return jsonEncodeData(getCodes()["CODE_200"],$data);
//        } catch(\Illuminate\Database\QueryException $ex) {
//            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
//        }
//    }
//
//    // 修改用户信息
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
//
//
//    // 订单列表
//    public function userTalkList($params){
//        try {
//            $uid = getUid();
//            $data = DB::table('ylyj_talkadd')
//                ->select("id","ycid","ycprice","paytype","times")
//                ->skip($params["pageSize"]*($params['page']-1))
//                ->limit($params["pageSize"])
//                ->where("uid",$uid)
//                ->where("paytype",$params["payType"])
//                ->get();
//            foreach ($data as &$datum){
//                $datum->commodity = DB::table("ylyj_commodity")
//                    ->select("name","img","isshelf")->where("id",$datum->ycid)->first();
//            }
//            $count = DB::table('ylyj_youbilist')->count();
//            $last = ceil($count/$params["pageSize"]);
//            return jsonEncodeData(getCodes()["CODE_200"],$data,$last);
//        } catch(\Illuminate\Database\QueryException $ex) {
//            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
//        }
//    }
//
//
//    // 我的收货地址
//    public function getCode(){
//        try {
//            $data = DB::table('hs_user_core')
//                ->where("openid",session("openid"))
//                ->where("status",1)
//                ->get()->toArray();
//            return jsonEncodeData(getCodes()["CODE_200"],$data);
//        } catch(\Illuminate\Database\QueryException $ex) {
//            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
//        }
//    }
//
//
//    // 删除我的收货地址
//    public function delCore($params){
//        try {
//            $uid = getUid();
//            DB::table('ylyj_core')
//                ->where("id","=",$params["id"])
//                ->where("uid","=",$uid)
//                ->update([
//                "status"=>2
//            ]);
//            return jsonEncodeData(getCodes()["CODE_200"]);
//        } catch(\Illuminate\Database\QueryException $ex) {
//            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
//        }
//    }
//
//    // 保存收货地址
//    public function saveCore($params){
//        // DB 修改用户资料..
//        DB::beginTransaction();
//        try {
//            Log::info(json_encode($params));
//            $id = DB::table('hs_user_core')->insertGetId([
//                "openid"=>session("openid"),
//                "username"=>$params["username"],
//                "phone"=>$params["phone"],
//                "province"=>$params["province"],
//                "city"=>$params["city"],
//                "area"=>$params["area"],
//                "address"=>$params["address"],
//                "times"=>Date("Y-m-d H:i:s"),
//            ]);
//            $data = array(
//                "coreid"=>$id,
//                "address"=>$params["province"].$params["city"].$params["area"].$params["address"],
//            );
//            DB::commit();
//            return jsonEncodeData(getCodes()["CODE_200"],$data);
//        } catch(\Illuminate\Database\QueryException $ex) {
//            DB::rollback();
//            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
//        }
//    }
//

    public function saveTalkAdd($par){
        DB::beginTransaction();
        try {
            $data = [
                "openid"=>session("openid"),
                "types_id"=>$par["types_id"],
                "core_id"=>$par["coreid"],
                "day_time"=>$par["day"]." , ".$par["time"],
                "remark"=>$par["remark"],
                "status"=>1,
                "times"=>Date("Y-m-d H:i:s")
            ];
            DB::table('hs_talkadd')->insert($data);

            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"]);
        }
    }
}
