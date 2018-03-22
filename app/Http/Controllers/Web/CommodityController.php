<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commodity;
use Illuminate\Http\Request;

/***
 * 商品信息表
 * Class CommodityController
 * @package App\Http\Controllers
 */
class CommodityController extends Controller{

    private $commodity ;

    public function __construct(Commodity $commodity){
        $this->commodity = $commodity;
    }

    public function index(){
        // 默认10条数据
        $params["page"] = 1 ;
        $params["pageSize"] = 10 ;
        $commodityLists = webData($this->commodity->getGoodsList($params));
        $commodityBanners = webData($this->commodity->getBanner());
        $webTitle ="邮来一九";
        $fottor = "index";
        return view("index",compact('commodityLists','commodityBanners','webTitle','fottor'));
    }

    /***
     * 获取商品详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request){
        $params = $request->all();
        $commodityDetail = webData($this->commodity->getGoodsDetail($params));
        return view("detail",compact('commodityDetail'));
    }

    /***
     * 支付
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function payment(Request $request){
        $params = $request->all();
        $commodityDetail = webData($this->commodity->getGoodsDetail($params));
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
            // 如果是微信，获取openID
            $is_wx_llq = "2"; // 是微信浏览器就用公众号支付 否则就用h5 支付
        }else{
            $is_wx_llq = "1"; // 是微信浏览器就用公众号支付 否则就用h5 支付
        }
        return view("payment",compact('commodityDetail','is_wx_llq'));
    }

}
