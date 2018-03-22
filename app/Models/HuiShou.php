<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HuiShou extends Model{

    // 获取分类
    public function getTypes(){
        try {
            $data = DB::table('hs_types')
                ->select("type_name","id")
                ->get()->toArray();
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        } catch(\Illuminate\Database\QueryException $ex) {
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }

    //
    public function getSubscribe($id){
        try {
            $data = DB::table('hs_subscribe')
                ->select("type_name","prices","units","imgs","remark")
                ->where("types_id",$id)
                ->orderBy('sub_sort', 'desc')
                ->get()->toArray();
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        } catch(\Illuminate\Database\QueryException $ex) {
            return jsonEncodeData(getCodes()["CODE_205"],"","","20501","数据库查询错误");
        }
    }

    // men mian tu
    public function getLogo(){
        return DB::table("hs_config")->select("v")->where("id",6)->first();
    }

}
