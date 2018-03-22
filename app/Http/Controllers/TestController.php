<?php

namespace App\Http\Controllers;

use App\Jobs\SendReminderEmail;
use App\Models\Tests;
use Illuminate\Http\Request;

/*         柴犬在手 BUG快走
                           7Rr.       .:7vuu7:rri.
                          vBj7PJ .v7EQdES21uri7LYKMQ2:
                         .Pr  iQPjBQZi            .:IBR:
                         si .jrYX...   ...........   .jBR.
        KUv:         iPZqi  .PZ..   .................  .EBg  :7:
       .gXvqBB2i:7JY7vr:   . ru..i....................   igBQ5uBg
       .J.  iUsr77i.             :.......................  iBr .Bi
        r1.            vKLiv7.  ..........................   vBgr
        rBs   .....   QBsMBBBBM  .:. .......................  PB
       7B7       .  YBK  UBBBBv   ..........................  .B:
      :B. jDP21i  . rMBPQMS7i       ......................... .PM
      :Z:U7rBBBBQ. .   i:            :..r..................... jB
       DBs  gBBP                     ...R: ..................  vB.
       SQvi2QB7  bBB:                .  XB.    .............   vBi
       .K7   .YBBQBB.  .KQ7         :..  gB.     .......... .  UB.
       7Bv  .  7ZRE:   iBB:        ..     YQIuPj  ........:RBriBB
       LBr  .YPi  KBQBBBL   ..    :. rKv    jBBB7  .    . .7SssUYrs7i
        RBJ   vLrQBBQBU    .    .::71u1BQv.   sBQqi  ..           .rBg
         iEBK.     .:       ..::rri.    vMBgPUPQIEBgbQBBRPdPPXX5KIJ2BK
           :Yb5JuJ:. .:iLds..:..           :ii.    i77rvLsYIIKIqIJ7r.
                 .rr.......
*/

class TestController extends Controller{


    public function test(Tests $tests,Request $request){

        dispatch(new SendReminderEmail());

        exit;
        $data = array(
            "a"=>array(
            "id"=>"001",
            "gtitle"=>"冬天天鹅绒四件套",
            "gname"=>"冬天天鹅绒四件套纯色加厚保暖珊瑚绒磨毛法莱绒绒毛床笠款床单式",
            "price1"=>"¥ 0.00",
            "price2"=>"¥ 248.00",
            "price3"=>"69",
            "Stock"=>"996",
            "imgUrl"=>"http://g-search3.alicdn.com/img/bao/uploaded/i4/i4/1747371608/TB2MYHyfl0kpuFjy1XaXXaFkVXa_!!1747371608.jpg_230x230.jpg",
        ),
            "b"=>array(
                "id"=>"002",
                "gtitle"=>"夏季床上用品",
                "gname"=>"夏季床上用品磨毛四件套",
                "price1"=>"¥ 0.00",
                "price2"=>"¥ 123.00",
                "price3"=>"39",
                "Stock"=>"223",
                "imgUrl"=>"https://g-search3.alicdn.com/img/bao/uploaded/i4/i3/T19.RCXCddXXXXXXXX_!!0-item_pic.jpg_230x230.jpg",
            ),
            "c"=>array(
                "id"=>"003",
                "gtitle"=>"房间电视柜",
                "gname"=>"摆件房间电视柜摆设",
                "price1"=>"¥ 39.90",
                "price2"=>"",
                "price3"=>"19",
                "Stock"=>"662",
                "imgUrl"=>"https://g-search2.alicdn.com/img/bao/uploaded/i4/i1/837878638/TB2UradaICO.eBjSZFzXXaRiVXa_!!837878638.jpg_230x230.jpg",
            ),
            "d"=>array(
                "id"=>"004",
                "gtitle"=>"威尔斯",
                "gname"=>"威尔斯大品牌，今日特价",
                "price1"=>"¥ 62.30",
                "price2"=>"¥ 623.00",
                "price3"=>"19",
                "Stock"=>"满仓",
                "imgUrl"=>"https://g-search1.alicdn.com/img/bao/uploaded/i4/i1/1968605428/TB2K9ENfXXXXXa.XpXXXXXXXXXX_!!1968605428.jpg_230x230.jpg",
            )
        );
        return view("test",$data);
    }

}
