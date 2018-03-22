<?php

namespace App\Models\Ht;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Types extends Model{

    // 获取分类
    public function getTypes(){
        try{
            $types = DB::table("hs_types")
                ->get()->toArray();
            return jsonEncodeData(getCodes()["CODE_200"],$types);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

//    // 商品列表 + 分页
    public function getTypesList($params){
        try{
            $commoditys = DB::table("hs_subscribe")
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();
            $count = DB::table('hs_subscribe')->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$commoditys,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }
//    // 商品列表 + 分页
//    public function testfenye(){
//        try{
//            $commoditys = DB::table('ylyj_commodity')->paginate(15);
//            return $commoditys;
//        }catch (QueryException $exception){
//            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
//        }
//    }
//
//    // 类型详情
    public function detailTypes($params){
        try{
            $commoditys = DB::table("hs_subscribe")->where("id",$params["id"])->first();
            return jsonEncodeData(getCodes()["CODE_200"],$commoditys);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 添加类型
    public function saveTypes($params){
        DB::beginTransaction();
        try{
            unset($params["_token"]);
            unset($params["imgfile"]);
            DB::table("hs_subscribe")->insert($params);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 删除
    public function typesDelete($params){
        DB::beginTransaction();
        try{
            DB::table("hs_subscribe")->where("id",$params["id"])->delete($params);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }
    // 更新
    public function updateTypes($params){
        DB::beginTransaction();
        try{
            $id = $params["id"];
            unset($params["_token"]);
            unset($params["id"]);
            unset($params["imgfile"]);
            DB::table("hs_subscribe")->where("id",$id)->update($params);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }
}
