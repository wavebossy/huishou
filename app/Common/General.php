<?php

if(!function_exists('getCodes')){
    function getCodes(){
        return [
            "CODE_200"=>array("code"=>200,"msg"=>"ok"),
            "CODE_201"=>array("code"=>201,"msg"=>"token验证失败"),
            "CODE_202"=>array("code"=>202,"msg"=>"请求被拒绝"),
            "CODE_203"=>array("code"=>203,"msg"=>"请求超频"),
            "CODE_204"=>array("code"=>204,"msg"=>"系统频繁"),
            "CODE_205"=>array("code"=>205,"msg"=>"数据库错误，请以204错误信息返回用户"),
            "CODE_206"=>array("code"=>206,"msg"=>"需要GET请求"),
            "CODE_207"=>array("code"=>207,"msg"=>"需要POST请求"),
            "CODE_208"=>array("code"=>208,"msg"=>"需要HTTPS请求"),
            "CODE_209"=>array("code"=>209,"msg"=>"请求接口不存在")
        ];
    }
}

if(!function_exists('jsonEncodeData')){
    function jsonEncodeData($code,$data=array(),$last="",$errorcode="",$errormsg=""){
        if($code["code"]==200){
            if(empty($errorcode)){
                if(!empty($last)){
                    $re = array_merge($code,array("errorcode"=>$errorcode,"errormsg"=>$errormsg,"time"=>date("Y-m-d H:i:s"),"data"=>array("result"=>$data,"last"=>$last)));
                }else{
                    $re = array_merge($code,array("errorcode"=>$errorcode,"errormsg"=>$errormsg,"time"=>date("Y-m-d H:i:s"),"data"=>array("result"=>$data)));
                }
            }else{
                $re = array_merge($code,array("errorcode"=>$errorcode,"errormsg"=>$errormsg,"time"=>date("Y-m-d H:i:s")));
            }
        }else{
            $re = array_merge($code,array("time"=>date("Y-m-d H:i:s")));
        }
        return  json_encode($re,JSON_UNESCAPED_UNICODE);
    }
}


if(!function_exists('timeFormat')){
    function timeFormat($data){
        $now_time  = time();
        $postedTime = ($now_time - $data);
        if ($postedTime < 60) {
            $date = $postedTime . '秒前';
        } elseif ($postedTime < 3600) { // 1小时内
            $date = ceil($postedTime / 60) . '分钟前';
        } elseif ($postedTime < 86400) { // 1天内
            $date = date("m.d H:i", $data);
        } elseif ($postedTime < 259200) { // 3天内
            $date = ceil($postedTime / 863400) . '天前';
        } else { // 3天前显示日期
            $date = date("m.d H:i", $data);
        }
        return $date;
    }
}


if(!function_exists('getIP')){
    //得到用户IP地址
    function getIP() {
        if (isset ( $_SERVER )) {
            if (isset ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
                $IPaddress = $_SERVER ["HTTP_X_FORWARDED_FOR"];
            } else if (isset ( $_SERVER ["HTTP_CLIENT_IP"] )) {
                $IPaddress = $_SERVER ["HTTP_CLIENT_IP"];
            } else {
                $IPaddress = $_SERVER ["REMOTE_ADDR"];
            }
        } else {
            if (getenv ( "HTTP_X_FORWARDED_FOR" )) {
                $IPaddress = getenv ( "HTTP_X_FORWARDED_FOR" );
            } else if (getenv ( "HTTP_CLIENT_IP" )) {
                $IPaddress = getenv ( "HTTP_CLIENT_IP" );
            } else {
                $IPaddress = getenv ( "REMOTE_ADDR" );
            }
        }
        return $IPaddress;
    }
}


/***
 * ->toArray(); 使用
 */
if(!function_exists('changeArrayCode')){
    function changeArrayCode($datas,$condition){
        $temp = [];
        foreach ($datas as $data){
            $temp2 = [];
            foreach ($data as $key=>$value){
                if($condition == "utf8"){
                    $data  = array($key=>mb_convert_encoding($value,"UTF-8","GBK"));
                }elseif($condition == "gbk"){
                    $data  = array($key=>mb_convert_encoding($value,"GBK","UTF-8"));
                }
                $temp2 = array_merge($temp2,$data);
            }
            $temp[] = $temp2;
        }
        return $temp ;
    }
}


if(!function_exists('checkEmpty')){
    /***
     * @param $data 需要检查是否为空的参数
     * @param string $parameter 缺少什么参数
     * @param string $test 测试提示，可不传
     */
    function checkEmpty($data,$parameter="请查看文档",$test=""){
        if(!isset($data) && empty($data)){
            echo jsonEncodeData(getCodes()["CODE_200"],"","","1001","缺少必要参数 $parameter . $test");exit;
        }
    }
}

if(!function_exists('checkEmpty__')){
    /***
     * @param $data 需要检查是否为空的参数
     * @param string $parameter 缺少什么参数
     * @param string $test 返回默认值
     */
    function checkEmpty__($data,$default=""){
        if(isset($data) && !empty($data)){
            return $data;
        }else{
            return $default;
        }
    }
}


if(!function_exists('http')){
    function http($url, $params, $method = 'GET', $header = array(), $multi = false){
        $opts = array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $header);
        switch(strtoupper($method)){
            case 'GET':$opts[CURLOPT_URL] = $url . '?' . http_build_query($params);break;
            case 'POST':$params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;break;
            default: E('不支持的请求方式！');
        }
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error)
            exit('请求发生错误：' . $error);
        return $data;
    }
}



