<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller{

    private $payment = null;

    public function __construct(Payment $payment){
        $this->payment = $payment;
    }

    // 预支付订单
    public function recharge(Request $request){
        checkEmpty($request["uid"],"uid","用户ID 1 ");
        checkEmpty($request["ycid"],"ycid","商品ID 1 ");
        checkEmpty($request["coreid"],"coreid","地址ID 1 ");
        checkEmpty($request["source"],"source","1 网页  3 iOS  4 安卓");
        checkEmpty($request["paytype"],"paytype","1 h5 2 微信公众号  3 iOS  4 安卓");
        $params = $request->all();
        echo $this->payment->recharge($params);
    }

    // 订单号调起支付
    public function pay(Request $request){
        checkEmpty($request["out_trade_no"],"out_trade_no","预支付订单号");
        $params = $request->all();
        echo $this->payment->pay($params);
    }

    // 微信异步
    public function weixinNodify(){
        echo $this->payment->weixinNodify();
    }

}
