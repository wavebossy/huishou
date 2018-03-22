<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Codes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class SendMsgCodeController extends Controller{

    /***
     * 发送验证码
     */
    public function sendMsgCode(Request $request){
        checkEmpty($request["phone"],"请输入手机号");
//        $code = rand(1,9999);
        $code = 6666;
//        Log::info('User failed to login.', ['code' => $code]);
        $params = array(
            "phone"=>$request->input("phone"),
            "userip"=>getIP(),
            "codes"=>$code,
            "times"=>Date("Y-m-d H:i:s")
        );

        $codes = new Codes();
        echo $codes->saveCodes($params);

    }

}
