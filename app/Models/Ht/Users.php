<?php

namespace App\Models\Ht;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Users extends Model{
    //

    public function getUsers($params){
        try{
            $menu = DB::table("hs_user")
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();
            $count = DB::table('hs_user')->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$menu,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getConfig(){
        try{
            return DB::table("hs_config")->get()->toArray();
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function orderPage(){
        try{
            $config = DB::table("hs_url_config")
                ->get()->toArray();
            return jsonEncodeData(getCodes()["CODE_200"],$config);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function saveUrlPage($params){
        DB::beginTransaction();
        try{
            $id = $params["id"];
            unset($params["_token"]);
            unset($params["id"]);

            DB::table("hs_url_config")
                ->where("id",$id)
                ->update($params);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function saveConfig($params){
        DB::beginTransaction();
        try{
            $id = $params["config_id"];
            unset($params["_token"]);
            unset($params["config_id"]);

            DB::table("hs_config")
                ->where("id",$id)
                ->update($params);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function complaintList($params){
        try{
            $menu = DB::table("hs_complaint")
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();
            $count = DB::table('hs_complaint')->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$menu,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

}
