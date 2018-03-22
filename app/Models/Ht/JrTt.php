<?php

namespace App\Models\Ht;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class JrTt extends Model{

    private $db;

    public function __construct(array $attributes = []){
        parent::__construct($attributes);
        $this->db = DB::connection("mysql_toutiao");
    }

    public function updateToutiaoAccount($params){
        DB::beginTransaction();
        try{
            unset($params["_token"]);
            $this->db->update('update tt_admin set serial=?,ttid=?,account=?,field=?,phone=?,mailboxs=?,pwd=?,subject=?,operator=?,remark=? where id=?',
                [$params["serial"],$params["ttid"],$params["account"],$params["field"],$params["phone"],$params["mailboxs"],$params["pwd"],$params["subject"],$params["operator"],$params["remark"],$params["id"]]);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function saveToutiaoAccount($params){
        DB::beginTransaction();
        try{
            unset($params["_token"]);
            $par = [
                "times" =>Date("Y-m-d H:i:s")
            ];
            $params = array_merge($params,$par);
            $pars = [];
            foreach ($params as $k=>$v){
                array_push($pars,$v);
            }
            $this->db->insert("insert into tt_admin(serial,ttid,account,field,phone,mailboxs,pwd,subject,operator,remark,times) values(?,?,?,?,?,?,?,?,?,?,?)",$pars);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getDetailToutiaoAccount($params){
        try{
            $results = $this->db->select('select * from tt_admin where id = ?', [$params["id"]]);
            return jsonEncodeData(getCodes()["CODE_200"],$results[0]);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getToutiaoAccountList($params){
        try{

            $orderBy = "order by id desc";
            $where = " where 1=1";
            $limit = " limit ".($params["page"]-1) . "," . ($params["page"] * $params["pageSize"]);

            $articles = $this->db->select("select * from tt_admin {$where} {$orderBy} {$limit}");
            $count = $this->db->select("select count(id) co from tt_admin {$where}");
            $last = ceil($count[0]->co/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$articles,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getToutiaoArticle($params){
        try{
            $orderBy = "order by id desc";
            $where = " where 1=1";
            $limit = " limit ".($params["page"]-1) . "," . ($params["page"] * $params["pageSize"]);

            $articles = $this->db->select("select * from tt_article {$where} {$orderBy} {$limit}");
            $count = $this->db->select("select count(id) co from tt_article {$where}");
            $last = ceil($count[0]->co/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$articles,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

}
