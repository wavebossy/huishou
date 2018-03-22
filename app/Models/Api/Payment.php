<?php

namespace App\Models\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Payment extends Model{

    /***
     * 1488382392@1488382392
     * 643524
     * wxa7f88494bef30015
     *
     * YLYJAPPH5JGANDYT6666666666666666
     * @var string
     */

    private $appid = "wxa7f88494bef30015"; //
    private $mch_id= "1488382392";
    private $key = "YLYJAPPH5JGANDYT6666666666666666";
    private $nodifyurl = "http://m.tuike520.com/api/weixinNodify";
    private $wap_url = "http://m.tuike520.com"; // y.ylyj.com ~~~
    private $wap_name = "邮来一九";

    /***
     * 生成预支付订单
     * @param $params
     * @return string
     */
    public function recharge($params){
        DB::beginTransaction();
        try {
            $ylyj_commodity = DB::table("ylyj_commodity")
                ->select("price","name","freight")
                ->where("id",$params["ycid"])
                ->where("isshelf",1)
                ->first();
            if(!empty($ylyj_commodity)){
                $out_trade_no = Date("YmdHis").str_random(14).rand(1111,9999); // 商户订单号
                DB::table("ylyj_payment")->insert([
                    "uid"=>$params["uid"],
                    "ycid"=>$params["ycid"],
                    "out_trade_no"=>$out_trade_no,
                    "total_fee"=>doubleval($ylyj_commodity->price+$ylyj_commodity->freight),// 1 订单总金额(元)，微信单位为分  支付宝单位为元
                    "body"=>"邮来已久-".$ylyj_commodity->name,// "商品名称";
                    "paytype"=>$params["paytype"],  // 1 h5 2 微信公众号  3 iOS  4 安卓
                    "payment"=>1, // 1 未支付 2 已支付 4 支付异常
                    "source"=>$params["source"],  // 1 网页  2 iOS  3 安卓
                    "times"=>Date("Y-m-d H:i:s"),
                ]);
                DB::table("ylyj_talkadd")->insert([
                    "uid"=>$params["uid"],
                    "ycid"=>$params["ycid"],
                    "ycoreid"=>$params["coreid"],
                    "ycnumber"=>1,
                    "ycprice"=>doubleval($ylyj_commodity->price+$ylyj_commodity->freight),
                    "ycyoubi"=>0,
                    "is_logistics"=>0,//是否发货
                    "paytype"=>1, // 1 未支付 2 已支付 4 支付异常
                    "out_trade_no"=>$out_trade_no,
                    "times"=>Date("Y-m-d H:i:s"),
                ]);
                DB::commit();
                $data = array("out_trade_no"=>$out_trade_no);
                return jsonEncodeData(getCodes()["CODE_200"],$data);
            }else{
                return jsonEncodeData(getCodes()["CODE_200"],"","","2006","商品已下架");
            }
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","生成预订单失败，请尝试");
        }
    }

    /***
     * 统一支付
     * total_fee 微信单位为分  支付宝单位为元 所以微信 *100
     * @param $params
     * @return string
     */
    public function pay($params){
        try {
            $ylyj_payment = DB::table("ylyj_payment")->select("body","uid","total_fee","paytype")->where("out_trade_no","=",$params["out_trade_no"])->first();

            switch ($ylyj_payment->paytype){
                // 1 h5 2 微信公众号  3 iOS  4 安卓
                case 1 :
                    $nonce_str = str_random(32);
                    $out_trade_no = $params["out_trade_no"]; // 商户订单号
                    $spbill_create_ip = getIP();
                    $notify_url = $this->nodifyurl; // 异步通知地址
                    $trade_type = "MWEB";
                    $data = array(
                        "appid"=>$this->appid,
                        "mch_id"=>$this->mch_id,
                            "device_info"=>"WEB",// 试一试PC
                        "nonce_str"=>$nonce_str,
                        "body"=>$ylyj_payment->body,
                        "out_trade_no"=>$out_trade_no,
                        "total_fee"=>doubleval($ylyj_payment->total_fee*100), // 微信单位为分
                        "spbill_create_ip"=>$spbill_create_ip,
                        "notify_url"=>$notify_url,
                        "trade_type"=>$trade_type,
                        "sign_type"=>"MD5",
                        "scene_info"=>json_encode(array(
                            "h5_info"=>array(
                                "type"=>"Wap",
                                "wap_url"=>$this->wap_url,
                                "wap_name"=> $this->wap_name
                            )
                        ))
                    );
                    ksort($data);
                    $sign = strtoupper(md5(ToUrlParams($data)."&key=".$this->key));
                    $data = array_merge($data,array("sign"=>$sign));
                    $data = ToXml($data);
                    $curl = curl_init();
                    curl_setopt($curl,CURLOPT_URL,"https://api.mch.weixin.qq.com/pay/unifiedorder");
                    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($curl,CURLOPT_POST,true);
                    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
                    $string = curl_exec($curl); // 得到第一次请求微信的结果
                    $strpostData = simplexml_load_string($string);
                    $stringData = [];
                    foreach ($strpostData->children() as $child){
                        $stringData = array_merge($stringData,array($child->getName()=>(string)$child));
                    }
                    ksort($stringData);
                    $wstring = ToUrlParams($stringData)."&key=".$this->key;
                    $wxsign = strtoupper(md5($wstring));
                    // 验证是否是微信发来的数据
                    if($wxsign == $stringData["sign"]){
                        $return_code = 0;
                        $result_code = 0;
                        foreach ($stringData as $k => $v){
                            if($k == "return_code" && $v == "SUCCESS"){
                                $return_code = 1;
                            }elseif($k == "result_code" && $v == "SUCCESS"){
                                $result_code = 1;
                            }
                        }
                        if($return_code == 1 && $result_code == 1){
                            $data = array(
                                "mweb_url"=>$stringData["mweb_url"]."&redirect_url=".urlencode("http://m.tuike520.com/ucenter"),// 跳转支付url
                                "paytype"=>intval($ylyj_payment->paytype)
                            );
                            return jsonEncodeData(getCodes()["CODE_200"],$data);
                        }else{
                            return jsonEncodeData(getCodes()["CODE_200"],"","2001","预支付失败,sign验证错误");
                        }
                    }
                    return jsonEncodeData(getCodes()["CODE_200"],"","2001","预支付失败,sign验证错误");
                    break;
                case 2 :
                    $openid = DB::table("ylyj_user")->select("openid")->where("id",$ylyj_payment->uid)->first()->openid;
                    if(!empty($openid)){
                        $nonce_str = str_random(32);
                        $out_trade_no = $params["out_trade_no"]; // 商户订单号
                        $spbill_create_ip = getIP();
                        $notify_url = $this->nodifyurl; // 异步通知地址
                        $trade_type = "JSAPI";
                        $data = array(
                            "appid"=>$this->appid,
                            "mch_id"=>$this->mch_id,
                            "device_info"=>"WEB",// 试一试PC
                            "nonce_str"=>$nonce_str,
                            "body"=>$ylyj_payment->body,
                            "out_trade_no"=>$out_trade_no,
                            "total_fee"=>doubleval($ylyj_payment->total_fee*100), // 微信单位为分
                            "spbill_create_ip"=>$spbill_create_ip,
                            "notify_url"=>$notify_url,
                            "trade_type"=>$trade_type,
                            "sign_type"=>"MD5",
                            "openid"=>$openid,
                            "scene_info"=>json_encode(array(
                                "h5_info"=>array(
                                    "type"=>"Wap",
                                    "wap_url"=>$this->wap_url,
                                    "wap_name"=> $this->wap_name
                                )
                            ))
                        );
                        ksort($data);
                        $sign = strtoupper(md5(ToUrlParams($data)."&key=".$this->key));
                        $data = array_merge($data,array("sign"=>$sign));
                        $data = ToXml($data);
                        $curl = curl_init();
                        curl_setopt($curl,CURLOPT_URL,"https://api.mch.weixin.qq.com/pay/unifiedorder");
                        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($curl,CURLOPT_POST,true);
                        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
                        $string = curl_exec($curl); // 得到第一次请求微信的结果
                        $strpostData = simplexml_load_string($string);
                        $stringData = [];
                        foreach ($strpostData->children() as $child){
                            $stringData = array_merge($stringData,array($child->getName()=>(string)$child));
                        }
                        ksort($stringData);
                        $wstring = ToUrlParams($stringData)."&key=".$this->key;
                        $wxsign = strtoupper(md5($wstring));
                        // 验证是否是微信发来的数据
                        if($wxsign == $stringData["sign"]){
                            $return_code = 0;
                            $result_code = 0;
                            foreach ($stringData as $k => $v){
                                if($k == "return_code" && $v == "SUCCESS"){
                                    $return_code = 1;
                                }elseif($k == "result_code" && $v == "SUCCESS"){
                                    $result_code = 1;
                                }
                            }
                            if($return_code == 1 && $result_code == 1){
                                $timeStamp = time();
                                $nonceStr = str_random(32);
                                $payData = array(
                                    "appId"=>$stringData["appid"],
                                    "timeStamp"=>"$timeStamp",
                                    "nonceStr"=>$nonceStr,
                                    "package"=>"prepay_id=".$stringData["prepay_id"],
                                    "signType"=>"MD5",
                                );
                                ksort($payData);
                                $paySign = strtoupper(md5(ToUrlParams($payData)."&key=".$this->key));
                                $payData = array_merge($payData,array("paySign"=>$paySign));
                                $data = array(
                                    "stringData"=>$payData,
                                    "paytype"=>intval($ylyj_payment->paytype)
                                );
                                return jsonEncodeData(getCodes()["CODE_200"],$data);
                            }else{
                                return jsonEncodeData(getCodes()["CODE_200"],"","2001","预支付失败,sign验证错误");
                            }
                        }
                        return jsonEncodeData(getCodes()["CODE_200"],"","2001","预支付失败,sign验证错误");
                    }else{
                        return jsonEncodeData(getCodes()["CODE_200"],"","2002","openid 为空,请尝试退出");
                    }
                    break;
                default :
                    return jsonEncodeData(getCodes()["CODE_205"],"","20501","订单不存在，或者查询异常");

            }
        } catch(\Illuminate\Database\QueryException $ex) {
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","订单不存在，或者查询异常");
        }
    }

    /***
     * 微信支付异步验证接口（公众号,h5）
     * @param $params
     */
    public function weixinNodify(){
        $fileContent = file_get_contents("php://input");
        $rs_data = function () use ($fileContent) {
            if ($fileContent == null) {
                return false;
            } else {
                $xmlResult = simplexml_load_string($fileContent);
                $data = array();
                foreach ($xmlResult->children() as $childItem) {
                    if ("result_code" == $childItem->getName() && $childItem != 'SUCCESS') {
                        return false;
                    }
                    if ("sign" != $childItem->getName()) {
                        // sign 不参与签名验证
                        $data = array_merge($data, array($childItem->getName() => "$childItem"));
                    }
                }
                $verifySign = ToUrlParams($data) . "&key=" . $this->key;
                $sign = strtoupper(md5($verifySign));
                foreach ($xmlResult->children() as $childItem) {
                    if ("sign" == $childItem->getName()) {
                        if ($childItem == $sign) {
                            return $data;
                        }
                    }
                }
                return false;
            }
        };
        // 验证通过
        if($rs_data()){
            $rs_data = $rs_data();
            $out_trade_no = $rs_data['out_trade_no'];
            $ylyj_payment = DB::table("ylyj_payment")->select("ycid","payment","total_fee","paytype","uid")->where("out_trade_no","=",$out_trade_no)->first();
            if($ylyj_payment->total_fee != doubleval($rs_data['total_fee']/100)){
                echo 'fail';
            }else{
                // paytype  1 => 微信 h5
                if(($ylyj_payment->paytype == 1 || $ylyj_payment->paytype == 2) && $ylyj_payment->payment == 1 ){
                    DB::beginTransaction();
                    try {
                        // 支付状态完成
                        $rp = DB::table("ylyj_payment")
                            ->where("out_trade_no",$out_trade_no)
                            ->update([
                                "payment"=>2,
                            ]);

                        // 订单已支付
                        $rt = DB::table("ylyj_talkadd")
                            ->where("uid",$ylyj_payment->uid)
                            ->where("out_trade_no",$out_trade_no)
                            ->update([
                                "paytype"=>2
                            ]);

                        // 推送 ..

                        DB::commit();
                    } catch(\Illuminate\Database\QueryException $ex) {
                        DB::rollback();
                        echo 'fail';exit;
                    }
                }
                echo 'SUCCESS';
            }
        }else{
            echo 'fail';
        }
    }

}
