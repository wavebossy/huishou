<?php

namespace App\Http\Controllers\Api;

use App\Models\Commodity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    /***
     * 获取商品列表
     */
    public function getGoodsList(Request $request){
        checkEmpty($request["page"],"page",1);
        checkEmpty($request["pageSize"],"pageSize",10);
        $params = $request->all();
        echo $this->commodity->getGoodsList($params);
    }

    /***
     * 获取商品详情
     */
    public function getGoodsDetail(Request $request){
        checkEmpty($request["id"],"id",1);
        $params = $request->all();
        echo $this->commodity->getGoodsDetail($params);
    }

}
