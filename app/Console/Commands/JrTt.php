<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class JrTt extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jrtt:zz'; // 抓取作者user_id

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取作者 user_id 存入数据库';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * https://www.toutiao.com/api/pc/feed/?category=news_entertainment&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A135E98F8FDCA1B&cp=59FFBCBA316B4E1
     */

    public function handle(){
        // 执行的方法

        $types = $this->getType();
        foreach ($types as $k=>$v){
            echo $k.$v . PHP_EOL;
        }

        // 首页抓取，data对象内的 tag 属性不等于 “ad” 就不是广告
        while (true){
            sleep(rand(1,5));
            break;
        }
        // 正则去抓取 user_id => 存入数据库
        //

    }

    // 头条 as cp 获取
    private function getAsCp(){
        $getMillisecond =  getMillisecond();
        $t = floor($getMillisecond/ 1e3);//Math.floor((new Date).getTime() / 1e3);
        $e = strtoupper(base_convert($t,10,16));//$t.toString(16).toUpperCase();base_convert
        $n = strtoupper(md5($t));
        if (8 != strlen($e)) return ["as"=>"479BB4B7254C150","cp"=>"7E0AC8874BB0985"];
        for ($o = substr($n,0, 5), $i = substr($n,0 - 5), $a = "", $r = 0; 5 > $r; $r++) $a .= $o[$r] . $e[$r];
        for ($l = "", $s = 0; 5 > $s; $s++) $l .= $e[$s + 3] . $i[$s];
        return ["as"=>"A1" . $a . substr($e,0 - 3),"cp"=>substr($e,0,3) . $l . "E1"] ;
    }

    // 头条大分类
    private function getType(){
        return [
            "news_hot"=>"热点",//https://www.toutiao.com/api/pc/feed/?category=news_hot&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A175C92FCFCCCDF&cp=59FF8C4CDD5F1E1
            "news_entertainment"=>"娱乐",//https://www.toutiao.com/api/pc/feed/?category=news_entertainment&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A15599EFDFECCB7&cp=59FFAC7C5BE78E1
            "news_society"=>"社会",//https://www.toutiao.com/api/pc/feed/?category=news_society&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1A5993F1FCCCC6&cp=59FF0C4C8C86EE1
            "news_tech"=>"科技",//https://www.toutiao.com/api/pc/feed/?category=news_tech&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A19539BFAFCCCD4&cp=59FFDCEC4D744E1
            "news_game"=>"游戏",//https://www.toutiao.com/api/pc/feed/?category=news_game&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1C5A9AF1FBCCB1&cp=59FF8CECABA1AE1
            "news_sports"=>"体育",//https://www.toutiao.com/api/pc/feed/?category=news_sports&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1A5890F4FBCCA8&cp=59FFFC6CBA48DE1
            "news_car"=>"汽车",//https://www.toutiao.com/api/pc/feed/?category=news_car&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1C5D95F1F9CCA1&cp=59FF1C4CDA21CE1
            "news_finance"=>"财经",//https://www.toutiao.com/api/pc/feed/?category=news_finance&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A12549CF0F0CC96&cp=59FF9C3CD9F61E1
            "funny"=>"搞笑",//https://www.toutiao.com/api/pc/feed/?category=funny&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1F519FF6F9CC88&cp=59FFCC7C08D8EE1
            "essay_joke"=>"段子",//https://www.toutiao.com/api/article/feed/?category=essay_joke&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A17519CFFF2CC79&cp=59FFECCCB7792E1
            "news_military"=>"军事",//https://www.toutiao.com/api/pc/feed/?category=news_military&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1B5C98F4F3CC4A&cp=59FFFCECB4CAEE1
            "news_world"=>"国际",//https://www.toutiao.com/api/pc/feed/?category=news_world&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1B5797FAFACC36&cp=59FFBCBC1376EE1
            "news_fashion"=>"时尚",//https://www.toutiao.com/api/pc/feed/?category=news_fashion&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1D5B94F0F7CC14&cp=59FF5CCCE1D46E1
            "news_travel"=>"旅游",//https://www.toutiao.com/api/pc/feed/?category=news_travel&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A155899F6F5CBF1&cp=59FF7CFBAFE19E1
            "news_discovery"=>"探索",//https://www.toutiao.com/api/pc/feed/?category=news_discovery&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A195F9BF0FECBE4&cp=59FF4CAB6ED4CE1
            "news_baby"=>"育儿",//https://www.toutiao.com/api/pc/feed/?category=news_baby&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A18569CF9F9CBD9&cp=59FF4CCB8D797E1
            "news_regimen"=>"养生",//https://www.toutiao.com/api/pc/feed/?category=news_regimen&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A135299FFFDCBCA&cp=59FF8C8B5CEAAE1
            "news_essay"=>"美文",//https://www.toutiao.com/api/pc/feed/?category=news_essay&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A11549BFBF0CBBF&cp=59FFDC5B1B3F1E1
            "news_history"=>"历史", //https://www.toutiao.com/api/pc/feed/?category=news_history&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A18559AF5F1CB9A&cp=59FFDCABA96ABE1
            "news_food"=>"美食",//https://www.toutiao.com/api/pc/feed/?category=news_food&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A185792F0F9CBB3&cp=59FF3CFBCB235E1
        ];
    }


}
