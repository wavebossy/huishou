<?php

namespace App\Http\Controllers\Ht;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ht\TalkAdd;
use Maatwebsite\Excel\Facades\Excel;

/***
 * 订单管理
 * Class CommodityController
 * @package App\Http\Controllers\Ht
 */
class TalkAddController extends Controller{

    private $talkAdd = "";

    function __construct(TalkAdd $talkAdd){
        $this->talkAdd = $talkAdd;
    }

    /***
     * 商品首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $commoditysData = $this->talkAdd->getAllTalkAdd($par);
        $commoditysData = json_decode($commoditysData);
        if(!empty($commoditysData)){
            $commoditys = $commoditysData->data->result;
        }else{
            $commoditys = "";
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $last = empty($commoditysData->data->last)?1:$commoditysData->data->last;
        return view("ht.talk.talkadd",compact("commoditys","page","pageSize","last"));
    }

    /**
     * 订单详情
     */
    public function talkDetail(Request $request){
        echo $this->talkAdd->talkDetail($request["id"]);
    }

    /**
     * 订单修改
     */
    public function talkDelivery(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $talkDelivery = $this->talkAdd->talkDelivery($par);
        $code = json_decode($talkDelivery);
        if($code->code==200){
            return redirect("/".htname."/talkadd?page=$page&pageSize=$pageSize")->with("success_info",1);
        }else{
            return redirect("/".htname."/talkadd?page=$page&pageSize=$pageSize")->with("error_info",1);
        }
//        $par = $request->all();
//        $par["page"] = checkEmpty__($request["page"],1);
//        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
//        $page = $par["page"];
//        $pageSize = $par["pageSize"];
//        $talkDelivery = $this->talkAdd->talkDelivery($par);
//        $code = json_decode($talkDelivery);
//        $commoditysData = $this->talkAdd->getAllTalkAdd($par);
//        $commoditysData = json_decode($commoditysData);
//        if(!empty($commoditysData)){
//            $commoditys = $commoditysData->data->result;
//        }else{
//            $commoditys = "";
//        }
//        $last = $commoditysData->data->last;
//        if($code->code==200){
//            return view("ht.talk.talkadd",compact("commoditys","page","pageSize","last"))->with("success_info",1);
//        }else{
//            return view("ht.talk.talkadd",compact("commoditys","page","pageSize","last"))->with("error_info",1);
//        }
    }

    public function exportTalkListData(Request $request){
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $commoditysData = $this->talkAdd->getAllTalkAdd($par);
        $commoditysData = json_decode($commoditysData);
        if(!empty($commoditysData)){
            $commoditys = $commoditysData->data->result;
        }else{
            $commoditys = "";
        }
        $fileName = "订单数据";
        //$tableHeader = ["用户名","电话", "上门地址","预约时间", "订单金额","订单状态"];
        $tableHeader = ["订单id","用户名","电话", "上门地址","预约时间","完成时间","下单时间","订单金额","订单状态","有话说"];
        $tableBody = [];
        $status = function($s){
            switch ($s){
                case 1 : return "待完成";break;
                case 2 : return "完成";break;
                case 3 : return "取消";break;
                default : return "订单异常";
            }
        };
        foreach ($commoditys as $commodity){
            $tableBody[] = [
                $commodity->id,
                base64_decode($commodity->user->user_name),
                $commodity->core->phone,
                $commodity->core->province.$commodity->core->city.$commodity->core->area.$commodity->core->address,
                $commodity->day_time,
                $commodity->toptimes,
                $commodity->times,
                $commodity->summoney,
                $status($commodity->status),
                $commodity->remark,
            ];
        }
        // {{($commodity->status==1?"订单待完成":($commodity->status==2?"订单完成":($commodity->status==3?"订单取消":"订单异常")))}}
        return $this->exportData($fileName,$tableHeader,$tableBody);

    }

    private function exportData($fileName,$tableHeader,$tableBody){
//        $cellData = [
//            ['学号','姓名','成绩'],
//            ['10001','AAAAA','99'],
//            ['10002','BBBBB','92'],
//            ['10003','CCCCC','95'],
//            ['10004','DDDDD','89'],
//            ['10005','EEEEE','96'],
//        ];
//        $cellData = [$tableHeader,$tableBody];
        $cellData = array_merge(array($tableHeader),$tableBody);
//        dd($cellData);
        return Excel::create($fileName,function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls'); // ->download('xls');
    }

}
