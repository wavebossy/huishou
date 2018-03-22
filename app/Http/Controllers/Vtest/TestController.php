<?php

namespace App\Http\Controllers\Vtest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;
use OSS\ACommon;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/***
 * Class TestController
 * @package App\Http\Controllers\Vtest
 */
class TestController extends Controller{

//导出excel api地址
//http://www.maatwebsite.nl/laravel-excel/docs/export
//导入excel api地址
//http://www.maatwebsite.nl/laravel-excel/docs/import

//    String Type  || 11 个命令
//    set key value 强制存，如果存在则会覆盖，无视类型
//    setnx key value 如果值存在，则不会覆盖返回 0  , 如果不存在 则如同 set 操作
//    mset | 强制设置，如果存在则会覆盖
//    msetnx  | 一口气设置多个唯一的键值对 空格隔开  msetnx k1 v1 k2 v2 k3 v3 如果其中一个k* 已经存在，则此次设置失败
//    get key  返回
//    decr  | incr    de 做减法 不能指定减多少，但固定减 1  同理 incr  * 2
//    decrby | incrby    de 做减法 decrby key value （key ，value减多少） 同理 incrby  * 2
//    getset key value 设置新的值，但返回上一次的值，如果上一次的值为空，则返回空
//    mget | 接受多个 key  返回 一个数组类型的值 mget k1 k2 k3

//    List Type  || 11 个命令
//    rpush lpush  , => right_push  ，left_push  全称  往数组内最左边或者最右边插入数据  rpush key  value1 value2 ...  * 2
//    ltrim  key START STOP 裁剪|  保留哪里到哪里  下标 0 开始
//    lrange key 0 10   | 返回集合列表 类似MySQL的 limit
//    llen key 查看list集合大小
//    lindex key INDEX |  其中下标可以为负数, 返回该下标对应的value
//    lset key INDEX value  | 通过索引来改变已有元素的值，否则出错
//    lrem key COUNT value | COUNT = 0 的时候，删除该key 里面所有的value ; COUNT > 0 从列表头开始删， COUNT < 0 从列表尾开始删  。删的个数为COUNT的绝对值   ，
//    lpop key | rpop key  删除左边第一个，删除右边第一个，并且返回删除的值    * 2
//    rpoplpush key1 key2 | 将key1的最后一个元素，转移到key2列表并返回。

//    Set Type || 无序
//    sadd key  value1 value2..
//    scard key  返回集合大小
//    srem key value1 value2..   删除 指定的多个 value
//    spop key 随机删除一个，并且返回删除的值
//    smove key1 key2  value ， 移动key1 的value到 key2 中 , 如果 key2 中存在 此 value ，则仅仅只key1 的值删除
//    sismember key value | 判断 value 是否在 key 中
//    sinter key1 key2 | 返回2个集合的交集
//    sinterstore newkey key1 key2 | 将2个集合的交集 存储在newkey 中
//    sunion key1 key2 | 返回所有的key并集
//    sunionstore newkey key1 key2 | 返回所有的key并集  存储在newkey 中
//    sdiff first_key1 other_key2| 返回 key1 相对于 key2 的差集
//    sdiffstore newkey first_key1 other_key2| 返回 key1 相对于 key2 的差集 并存储在newkey 中
//    smembers key  | 返回集合中的所有成员
//    srandmember  key [count]  | 返回集合中的随机一个成员 默认1个(不传入count的时候) 如果返回null 如果传入count 则返回空数组

//    Set Type || 有序列表
//    zadd key INDEX value [INDEXD value] ...| 有序列表 显示的很奇怪,键值对竟然是反着来的
//    zincrby key INDEX value | 对有序集合中指定成员的分数加上增量 ，可以为负数
//    zrem key value | value 可多个，删除元素
//    zrange key 0 -1  [WITHSCORES] | 查看列表 == 递增排列  [WITHSCORES] 可选，返回INDEX
//    zrevrange key 0 -1 | 查看列表 == 递减排列
//    zcrd key 获取列表大小
//    zscore key value |  获取下标
//    zremrangebyscore key min max  |  删除指定区间的数据


    //Excel文件导出功能 By Laravel学院
    public function export(){

        $cellData = [
            ['学号','姓名','成绩'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
        Excel::create('学生成绩',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls'); // ->download('xls');
        // Excel5 : xls
        // Excel2007  : xlsx
        // pdf
    }

    // 上传七牛
    public function test7(Request $request){
        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()){
                // 上传成功
                // 随机名字 . 后缀
                $fileName = Date("YmdHis").substr(md5(time()),5,15).".".$request->file("photo")->extension();// 需要 开启php_fileinfo 扩展 否则会报错
                // 获取临时上传的路径（如果不存在本地，则方法调用完之后，会被删除）
                $fileUrl = $request->file('photo')->path();
                // 可选存储在本地
//                $fileUrl = $request->file("photo")->move(__DIR__."/",$fileName);
                $accessKey = "WLNI2zsyIDKO1MTf5hCXajt7gX5Il6pp4xJIxk9k";
                $secretKey = "GC548AMYn09j45EhHvuOnSTZacGfiyIoM4Dd8NzE";
                $bucket = "laigaonew";
                $auth = new Auth($accessKey, $secretKey);
//                $policy = array(
//                    // 回调删除服务器存储的原始文件
//                    'callbackUrl' => 'https://webapi.jirisudi.com/CourierNew/uploadVoiceCallback',
//                    'callbackBodyType'=>"application/x-www-form-urlencoded",
//                    'callbackBody' => $name.$type // filename.jpg
//                );
                // 上传七牛
//                $uptoken = $auth->uploadToken($bucket, null, 3600, $policy);
                $uptoken = $auth->uploadToken($bucket);
                $uploadMgr = new UploadManager();
                list($ret, $err) = $uploadMgr->putFile($uptoken, $fileName, $fileUrl);
                if ($err !== null) {
                    $image="";
                } else {
                    $image="https://laigaonew.laigao520.com/".$fileName;
                }
                echo $image;
            }else{
                // 文件上传失败
                echo jsonEncodeData(getCodes()["CODE_200"],"","","1005","文件上传失败");
            }
        }else{
            // 文件不存在
            echo jsonEncodeData(getCodes()["CODE_200"],"","","1004","文件不存在");
        }
    }

//  上传图片
    public function test5(ACommon $ACommon,Request $request){
        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()){
                // 上传成功
                // 随机名字 . 后缀
                $fileName = Date("YmdHis").substr(md5(time()),5,15).".".$request->file("photo")->extension();// 需要 开启php_fileinfo 扩展 否则会报错
                // 获取临时上传的路径（如果不存在本地，则方法调用完之后，会被删除）
//                $fileUrl = $request->file('photo')->path();
                // 可选存储在本地
                $fileUrl = $request->file("photo")->move(__DIR__."/",$fileName);
                // 上传oss
                $ossrs = $ACommon->fileUpload($fileName,$fileUrl,true);
                if($ossrs["code"]==200){
                    echo $ossrs["url"];
                }else{
                    var_dump($ossrs);
                }
            }else{
                // 文件上传失败
                echo jsonEncodeData(getCodes()["CODE_200"],"","","1005","文件上传失败");
            }
        }else{
            // 文件不存在
            echo jsonEncodeData(getCodes()["CODE_200"],"","","1004","文件不存在");
        }
    }

    public function test3(){
//        echo json_encode(array("今年5月4第一次去了医院，七点钟到的只到  下午一白九十多号，下午分的诊，医生只是问了下经历就让助理开化验单处方单，做tct彩超，得五个工作曰才能取结果"),JSON_UNESCAPED_UNICODE);
//        $rs = Redis::zrange("yt","0","11111","withscores");
//        print_r($rs);
        phpinfo();
    }


    public function test4(){
        Redis::mset("lm","value");
        Redis::expire("lm",10);
    }

    public function test1(){
        // No 1 String
        $rs = Redis::getset("lozls2of",rand(0,9999));
//        var_dump($rs);exit;
        // set 会直接覆盖原有的值
        Redis::set("stringKey","stringValue");
        // setnx 不会覆盖
        Redis::setnx("stringKey","stringValue_nx");
        // 获取
        echo Redis::get("stringKey") . PHP_EOL;

        Redis::set("count_id",100);
        exit;
        echo Redis::get("count_id"). PHP_EOL;
        // 减少(但是减少会到负数)
        Redis::decrby("count_id",20);
        echo Redis::get("count_id"). PHP_EOL;
//        var_dump(Redis::get("stringKey"));

//        Redis::append("stringKey","1-");
//        Redis::append("stringKey","2-");
//        Redis::append("stringKey","3-");
//        $rs = Redis::get("stringKey");
//        echo $rs;

    }


    public function test2(){

        // No 2 Set 无序列表
        $setType = ["setKey1"=>"stringValue2","setKey2"=>"stringValue3","setKey3"=>"stringValue4"];
        // smembers 返回空数组 || 该key的数组
        $setKey = Redis::smembers("setKey");
        if(empty($setKey)){
            foreach ($setType as $item){
                //为某一个键，设置一个数组
                Redis::sadd("setKey",$item);
            }
            // 无效
            $setType = ["setKey1"=>"stringValue4","setKey2"=>"stringValue5","setKey3"=>"stringValue6"];
            foreach ($setType as $item){
                //为某一个键，设置一个数组
                Redis::sadd("setKey",$item);
            }
        }
//        $setKey = Redis::smembers("setKey");
        // 可以不用再次获取，就能直接返回了

        dd($setKey);

        if(!empty($setKey)){
            echo 2;
            var_dump($setKey);
            Redis::sdiff("setKey");
            $setKey = Redis::smembers("setKey");
            var_dump($setKey);
        }else{
            echo 2222;
        }

    }


    public function test6(){
        $data = array(
            array(
            "id"=>"001",
            "gtitle"=>"冬天天鹅绒四件套",
            "gname"=>"冬天天鹅绒四件套纯色加厚保暖珊瑚绒磨毛法莱绒绒毛床笠款床单式",
            "price1"=>"¥ 0.00",
            "price2"=>"¥ 248.00",
            "price3"=>"69",
            "Stock"=>"996",
            "imgUrl"=>"http://g-search3.alicdn.com/img/bao/uploaded/i4/i4/1747371608/TB2MYHyfl0kpuFjy1XaXXaFkVXa_!!1747371608.jpg_230x230.jpg

",
        ),
            array(
                "id"=>"002",
                "gtitle"=>"夏季床上用品",
                "gname"=>"夏季床上用品磨毛四件套",
                "price1"=>"¥ 0.00",
                "price2"=>"¥ 123.00",
                "price3"=>"39",
                "Stock"=>"223",
                "imgUrl"=>"https://g-search3.alicdn.com/img/bao/uploaded/i4/i3/T19.RCXCddXXXXXXXX_!!0-item_pic.jpg_230x230.jpg

",
            ),
            array(
                "id"=>"003",
                "gtitle"=>"房间电视柜",
                "gname"=>"摆件房间电视柜摆设",
                "price1"=>"¥ 39.90",
                "price2"=>"",
                "price3"=>"19",
                "Stock"=>"662",
                "imgUrl"=>"https://g-search2.alicdn.com/img/bao/uploaded/i4/i1/837878638/TB2UradaICO.eBjSZFzXXaRiVXa_!!837878638.jpg_230x230.jpg

",
            ),
            array(
                "id"=>"004",
                "gtitle"=>"威尔斯",
                "gname"=>"威尔斯大品牌，今日特价",
                "price1"=>"¥ 62.30",
                "price2"=>"¥ 623.00",
                "price3"=>"19",
                "Stock"=>"满仓",
                "imgUrl"=>"https://g-search1.alicdn.com/img/bao/uploaded/i4/i1/1968605428/TB2K9ENfXXXXXa.XpXXXXXXXXXX_!!1968605428.jpg_230x230.jpg

",
            ));

        return view('uploadimage',["asdasd"=>$data]);
    }

}
