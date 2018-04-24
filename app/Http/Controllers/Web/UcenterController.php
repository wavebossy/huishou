<?php

namespace App\Http\Controllers\Web;

use App\Models\Users;
use App\Models\YouBiList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class UcenterController extends Controller{

    private $Users;

    function __construct(Users $users){
        $this->Users = $users;
    }

    public function index(Request $request){
        $userinfos = webData($this->Users->getUserInfo());
        $htusers = new \App\Models\Ht\Users();
        $orderPageData = $htusers->orderPage();
        $orderPageData = json_decode($orderPageData);
        if(!empty($orderPageData->data->result)){
            $orderPage = $orderPageData->data->result;
        }else{
            $orderPage = "";
        }
        return view("hs.ucenter",compact('userinfos','orderPage'));
    }

    /***
     * 获取用户信息，跳转修改页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserInfo(){
        $userinfos = webData($this->Users->getUserInfo());
        return view("userinfo",compact('userinfos'));
    }

    /***
     * 修改用户信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateUserInfo(Request $request){
        $username = $request->input("username");
        $phone = $request->input("phone");
        $imagefront = $request->input("img");
        $imgurl=""; // 本地路径，上传使用
        $fileName = ""; // a.jpg 文件名，回调删除
        $image=""; // 最终七牛返回的文件名 + 写死的路径
        $params = ["username"=>base64_encode($username),"phone"=>$phone];
        if(!empty($imagefront)){
            $imagefront = str_replace("data:image/jpeg;base64,","",$imagefront);
            $img = base64_decode($imagefront);
            $fileName = Date("YmdHis").substr(md5(time()),5,15).".jpeg";
            $filepath = public_path()."/tempImg/".$fileName;
            $rs = file_put_contents($filepath,$img);
            if (!$rs) {
                $imgurl="";
            }else{
                $imgurl=$filepath; // 本地路径
            }
        }
        if(is_file($imgurl) && !empty($fileName)){
            $fileUrl = $imgurl; // 本地路径
            $bucket = "laigaonew";
            $auth = new Auth(___accessKey, ___secretKey);
//            $policy = array(
//                // 回调删除服务器存储的原始文件
//                'callbackUrl' => 'https://webapi.jirisudi.com/CourierNew/uploadVoiceCallback',
//                'callbackBodyType'=>"application/x-www-form-urlencoded",
//                'callbackBody' => $fileName // filename.jpg
//            );
            // 上传七牛
//                $uptoken = $auth->uploadToken($bucket, null, 3600, $policy);
            $uptoken = $auth->uploadToken($bucket);
            $uploadMgr = new UploadManager();
            list($ret, $err) = $uploadMgr->putFile($uptoken, $fileName, $fileUrl);
            if ($err !== null) {
                $image="";
            } else {
                $image="https://laigaonew.laigao520.com/".$fileName;
                // 上传完成后删除本地
                $file = public_path()."/tempImg/".$fileName;
                unlink($file);
            }
            $params = array_merge($params,["userimg"=>$image]);
        }

        $rs = webData($this->Users->updateUserInfo($params));
        if($rs){
            $fottor = "ucenter";
            $userinfos = webData($this->Users->getUserInfo());
            return view("ucenter",compact('fottor','userinfos'));
        }else{
            $userinfos = webData($this->Users->getUserInfo());
            return view("userinfo",compact('userinfos'));
        }
    }

    /***
     * 邮币列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getYouBiListPage(){
        return view("youbilist");
    }

    /***
     * 邮币列表数据
     * @param Request $request
     */
    public function getYouBiList(Request $request){
        $params = $request->all();
        echo $this->Users->getYouBiList($params);
    }

    /***
     * 收货地址
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCode(){
        $cores = webData($this->Users->getCode());
        return view("hs.core",compact('cores'));
    }

    // 下单的时候，获取数据选择
    public function getAddress(){
        $params["page"] = 1 ;
        $params["pageSize"] = 10 ;
        echo $this->Users->getCode($params);
    }

    /***
     * 收货地址
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delCore(Request $request){
        $params = $request->all();
        $this->Users->delCore($params);
        return redirect("/ucenter");
    }

    /***
     * 我领取的宝贝数据
     * @param Request $request
     */
    public function userTalkList(Request $request){
        $params = $request->all();
        echo $this->Users->userTalkList($params);
    }


    /***
     * 我领取的宝贝数据
     * @param Request $request
     */
    public function delTalkAdd(Request $request){
        $params = $request->all();
        $this->Users->delTalkAdd($params);
        return redirect("/usertalklistpage");
    }

    // fenxiang chenggong
    public function successShare(){
        $this->Users->successShare();
    }


//    /***
//     * 我领取的宝贝
//     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
//     */
//    public function userTalkListPage(){
//        return view("hs.usertalklist");
//    }

    // 投诉
    public function complaint(Request $request){
        $params = $request->all();
        if ($request->hasFile('imgfile')) {
            if ($request->file('imgfile')->isValid()){
                // 上传成功
                // 随机名字 . 后缀
                $fileName = Date("YmdHis").substr(md5(time()),5,15).".".$request->file("imgfile")->extension();// 需要 开启php_fileinfo 扩展 否则会报错
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
        echo $this->Users->complaint($params);
    }

    // 邀请二维码
    public function inviterImg(){
        $userinfos = webData($this->Users->getUserInfo());
        // 生成二维码即可，返回数据库设置的strkey
        // 拼接上生成二维码的参数
        $target = $userinfos->inviter; // 用户唯一的邀请码
        if(!empty($target)){
//            $filepath = base_path()."/public/tempImg/".$target.".jpg"; // 文件路径
//            if(file_exists($filepath)){
//                Header("Content-type: image/png");
//                ImagePng(imagecreatefromstring(file_get_contents($filepath)));
//            }else{
                $access_token = getAccessToken();
                //二维码
                $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
                $post_data = array(
                    "expire_seconds"=>2592000,   // 30 天有效
                    "action_name"=>"QR_STR_SCENE",   // QR_LIMIT_STR_SCENE  QR_STR_SCENE
                    "action_info"=>array(
                        "scene"=>array(
                            "scene_str"=>$target
                        )
                    )
                );
                // QR_LIMIT_SCENE 类型 EventKey =  scene_id
                // QR_LIMIT_STR_SCENE 类型 EventKey =  scene_str
                $data = http($url,json_encode($post_data),"post",["Accept-Charset: utf-8"],true);
//                $ch = curl_init();
//                $header[] = "Accept-Charset: utf-8";
//                curl_setopt($ch, CURLOPT_URL, $url);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//                curl_setopt($ch, CURLOPT_POST, 1);
//                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
//                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//                curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
//                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
//                $data = curl_exec($ch);
//                curl_close($ch);
                $ewm = json_decode($data);
                header("Content-Type:image/jpg");
                $rsImgFile = requestAncient("https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ewm->ticket));
                echo $rsImgFile;exit;
//                file_put_contents($filepath,$rsImgFile); // 保存在服务器
//                $logo = __DIR__."/tempImg/FGHJKFGHJK56789NMGHJK222.jpg";//准备好的logo图片，底图
//                $QR = $filepath;//已经生成的原始二维码图
//                $userImgFile = $courier["img"]."?imageView2/1/w/50/h/50/interlace1";  // 用户头像
//                $userImgPath = __DIR__."/tempImg/".$courier["wx_openid"].".jpg";  // 以用户的wx_openid 做文件名，并把用户头像保存在服务器
//                file_put_contents($userImgPath,file_get_contents($userImgFile));
//                if ($logo !== FALSE) {
//                    $QR = imagecreatefromstring(file_get_contents($QR));
//                    $userimg = imagecreatefromstring(file_get_contents($userImgPath));   // 还可放用户头像
//                    $logo = imagecreatefromstring(file_get_contents($logo));
//                    $QR_width = imagesx($QR);//二维码图片宽度
//                    $QR_height = imagesy($QR);//二维码图片高度
//                    $userimg_width = imagesx($userimg);//头像宽度
//                    $userimg_height = imagesy($userimg);//头像高度
//                    $border = 4;
//                    $whiteborderimg = imagecreatetruecolor($userimg_width+$border,$userimg_height+$border);// 默认黑色底,宽高比原图大一点点
//                    $color = imagecolorAllocate($whiteborderimg,255,255,255);//分配一个灰色，边框颜色
//                    imagefill($whiteborderimg,0,0,$color); // 从左上角开始填充灰色
//                    // 生成图，盖上生成图的图，盖上去的位置x,y 盖上去图本身的偏移x,y ?? ，盖上去图的宽,高大小 , 原图的宽,高大小
//                    imagecopyresampled($whiteborderimg, $userimg,$border/2,$border/2, 0, 0,$userimg_width, $userimg_height, $userimg_width, $userimg_height);
//                    imagecopyresampled($QR, $whiteborderimg,150,150, 0, 0,  130+$border, 130+$border, $userimg_width+$border, $userimg_height+$border);
//                    imagecopyresampled($logo, $QR, 140,340 , 0, 0,  220, 220, $QR_width, $QR_height);
//                }
//                imagepng($logo, $filepath);
//                Header("Content-type: image/png");
//                ImagePng($logo);
//            }
        }
    }

}
