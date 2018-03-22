<?php

namespace App\Models\Ht;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class TalkAdd extends Model{

    // 订单
    public function getAllTalkAdd($params){
        try{
            $data = DB::table("hs_talkadd")
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->orderBy("id","desc")
                ->get()->toArray();

            foreach ($data as &$datum){
                $datum->type = DB::table("hs_types")->where("id",$datum->types_id)->first();
                $datum->user = DB::table("hs_user")->where("openid",$datum->openid)->first();
                $datum->core = DB::table("hs_user_core")->where("id",$datum->core_id)->first();
//                unset($datum->types_id);
//                unset($datum->core_id);
            }
            $count = DB::table('hs_talkadd')->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$data,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 发货
    public function talkDelivery($params){
        DB::beginTransaction();
        try {
            DB::table("hs_talkadd")->where("id",$params['talkid'])->update([
                "status"=>$params['status'],
                "summoney"=>$params['summoney'],
                "toptimes"=>Date("Y-m-d H:i:s"),
            ]);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 订单详情
    public function talkDetail($id){
        try{
            $talkadd = DB::table("hs_talkadd")
                ->where("id",$id)
                ->first();
            $types = DB::table("hs_types")->where("id",$talkadd->types_id)->first();
            $core = DB::table("hs_user_core")->where("id",$talkadd->core_id)->first();

            $data = [
                "types"=>$types, // types
                "core"=>$core,// 收货地址
                "talkadd"=>$talkadd  // 订单详情
            ];
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

}
