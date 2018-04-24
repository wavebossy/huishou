<?php

namespace App\Http\Controllers\Ht;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ht\Types;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
/***
 * 回收管理
 * Class CommodityController
 * @package App\Http\Controllers\Ht
 */
class TypesController extends Controller{

    private $types = "";

    function __construct(Types $types){
        $this->types = $types;
    }

    /***
     * 商品列表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTypesList(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        //$par["types"] = checkEmpty__($request["types"],1);
        $commoditysData = $this->types->getTypesList($par);
        $commoditysData = json_decode($commoditysData);
        $types = $commoditysData->data->result;
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $last = $commoditysData->data->last;
        return view("ht.types.typeslist",compact("types","page","pageSize","last"));
    }

    /***
     * 添加商品页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addTypes(){
        $types = webData($this->types->getTypes());
        return view("ht.types.addtypes",compact("types"));
    }

    /***
     * 删除数据
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function typesDelete(Request $request){
        $params = $request->all();
        $rs = $this->types->typesDelete($params);
        $code = json_decode($rs);
        if($code->code==200){
            return redirect("/".htname."/typeslist")->with("success_info",1);
        }else{
            return redirect("/".htname."/typeslist")->with("error_info",1);
        }
    }


    // ======================================================================================

    /***
     * 添加回收数据
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveTypes(Request $request){
        $params = $request->all();
        if ($request->hasFile('imgfile')) {
            if ($request->file('imgfile')->isValid()){
                // 上传成功
                // 随机名字 . 后缀
                $fileName = "/huishou/".Date("YmdHis").substr(md5(time()),5,15).".".$request->file("imgfile")->extension();// 需要 开启php_fileinfo 扩展 否则会报错
                // 获取临时上传的路径
                $fileUrl = $request->file('imgfile')->path();
                $bucket = "heikejis";
                $auth = new Auth(___accessKey, ___secretKey);
                // 上传七牛
                $uptoken = $auth->uploadToken($bucket);
                $uploadMgr = new UploadManager();
                list($ret, $err) = $uploadMgr->putFile($uptoken, $fileName, $fileUrl);
                if ($err !== null) {
                    $image="";
                } else {
                    //$image="https://protal.szsldy.com/".$fileName;
                    $image="http://ovr6bpugl.bkt.clouddn.com/".$fileName;
                    $params = array_merge($params,array("imgs"=>$image));
                }
            }
        }
        $rs = $this->types->saveTypes($params);
        $code = json_decode($rs);
        if($code->code==200){
            return redirect("/".htname."/addtypes")->with("success_info",1);
        }else{
            return redirect("/".htname."/addtypes")->with("error_info",1);
        }
    }

    /***
     * 获取商品详情
     */
    public function detailTypes(Request $request){
        echo $this->types->detailTypes($request->all());
    }

    /***
     * 修改商品
     */
    public function updateTypes(Request $request){
        $params = $request->all();
        if ($request->hasFile('imgfile')) {
            if ($request->file('imgfile')->isValid()){
                // 上传成功
                // 随机名字 . 后缀
                $fileName = "huishou/".Date("YmdHis").substr(md5(time()),5,15).".".$request->file("imgfile")->extension();// 需要 开启php_fileinfo 扩展 否则会报错
                // 获取临时上传的路径
                $fileUrl = $request->file('imgfile')->path();
                $bucket = "heikejis";
                $auth = new Auth(___accessKey, ___secretKey);
                // 上传七牛
                $uptoken = $auth->uploadToken($bucket);
                $uploadMgr = new UploadManager();
                list($ret, $err) = $uploadMgr->putFile($uptoken, $fileName, $fileUrl);
                if ($err !== null) {
                    $image="";
                } else {
                    //$image="https://protal.szsldy.com/".$fileName;
                    $image="http://ovr6bpugl.bkt.clouddn.com/".$fileName;
                    $params = array_merge($params,array("imgs"=>$image));
                }
            }
        }
        $rs = $this->types->updateTypes($params);
        $code = json_decode($rs);
        if($code->code==200){
            return redirect("/".htname."/typeslist")->with("success_info",1);
        }else{
            return redirect("/".htname."/typeslist")->with("error_info",1);
        }
    }

}
