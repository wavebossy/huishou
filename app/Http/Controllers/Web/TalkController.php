<?php

namespace App\Http\Controllers\Web;

use App\Models\Talk;
use App\Models\Users;
use App\Models\Api\Codes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class TalkController extends Controller{

    private $talk ;

    public function __construct(Talk $talk){
        $this->talk = $talk;
    }

    // 预约下单
    public function saveTalkAdd(Request $request){
        $par = $request->all();
        $par["day"] = $this->day($par["day"]);
        echo $this->talk->saveTalkAdd($par);
    }


    private function day($par){

        if($par=="明天"){
            return Date("Y-m-d",strtotime("1 day"));
        }else if($par=="后天"){
            return Date("Y-m-d",strtotime("2 day"));
        }else{
            return Date("Y-m-d");
        }

    }

}
