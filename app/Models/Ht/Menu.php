<?php

namespace App\Models\Ht;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;


class Menu extends Model{

    /***
     * 允许带 id 查询
     * @param string $par
     * @return string
     */
    public function getMenu($par=""){
        try{
            $menu = DB::table("hs_menu")
                ->where("isshow",1);
            if(!empty($par)){
                $menu = $menu->where("id",$par);
            }
            $menu = $menu->get()->toArray();
            return jsonEncodeData(getCodes()["CODE_200"],$menu);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }
    /***
     * 保存更改
     * @param string $par
     * @return string
     */
    public function saveMenu($all=[]){
        DB::beginTransaction();
        try{
            DB::table("hs_menu")
                ->where("id",$all["id"])
                ->update([
                    "breadcrumb"=>$all["breadcrumb"],
                    "icon"=>$all["icon"],
                    "isshow"=>$all["isshow"],
                    "menuname"=>$all["menuname"],
                    "parentid"=>$all["parentid"],
                    "path"=>$all["path"],
                    "smalltext"=>$all["smalltext"]
                ]);//$all
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 首页报表数据
    public function formReport(){
        try{

            // 当月完成单量
            //$talkaddsum = DB::table("ylyj_talkadd")->where("times",">",Date("Y-m-d ",strtotime("-30 day")))->where("paytype",2)->where("is_logistics",1)->count();
            // 当月流水总额
            //$talkaddycprice = DB::table("ylyj_talkadd")->where("times",">",Date("Y-m-d ",strtotime("-30 day")))->where("paytype",2)->where("is_logistics",1)->sum("ycprice");

            // 未处理订单数
//            $talkaddcs0 = DB::table("ylyj_talkadd")->where("paytype",2)->where("is_logistics",0)->count();
//            $talkaddcs1 = DB::table("ylyj_talkadd")->where("paytype",2)->where("is_logistics",1)->count();
//            $talkaddycprice = DB::table("ylyj_talkadd")->where("paytype",2)->where("is_logistics",1)->sum("ycprice");
            // 总单量和总营业额
//            $talkaddycprice = DB::select("select count(id) talkaddcs,sum(ycprice) ycprices from ylyj_talkadd where paytype=2");
//            $data = [
//                "talkaddycprice"=>$talkaddycprice[0],
//                "talkaddcs0"=>$talkaddcs0, // 没发货
//                "talkaddcs1"=>$talkaddcs1, // 已发货
//            ];
            return jsonEncodeData(getCodes()["CODE_200"]);
//            return jsonEncodeData(getCodes()["CODE_200"],$data);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

}
