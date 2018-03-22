<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::any('/test', 'TestController@test');

// h5 开发文档 https://pay.weixin.qq.com/wiki/doc/api/H5.php?chapter=9_20&index=1


$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
//    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {
////namespace声明路由组的命名空间，因为上面设置了"prefix"=>"api",所以以下路由都要加一个api前缀，比如请求/api/users_list才能访问到用户列表接口
//        $api->any("/test","TestController@test");
//        // 获取商品列表
//        $api->any("/commodityList","CommodityController@getGoodsList");
//        // 获取商品列表
//        $api->any("/commodityDetail","CommodityController@getGoodsDetail");
//
//
//        $api->post("/test7","Vtest\TestController@test7");
//        $api->post("/test5","Vtest\TestController@test5");
//    });

    $api->group(['namespace' => 'App\Http\Controllers\Vtest'], function ($api) {
        $api->any("/export","TestController@export");
    });

    $api->group(['namespace' => 'App\Http\Controllers\Api'], function ($api) {
//namespace声明路由组的命名空间，因为上面设置了"prefix"=>"api",所以以下路由都要加一个api前缀，比如请求/api/users_list才能访问到用户列表接口
        // 发送验证码
        $api->post("/sendMsgCode","SendMsgCodeController@sendMsgCode");

        // 统一下单
        $api->any("/recharge","PaymentController@recharge");
        // 统一支付
        $api->any("/pay","PaymentController@pay");
        // 微信回调
        $api->any("/weixinNodify","PaymentController@weixinNodify");

        $api->any("/commodityList","CommodityController@getGoodsList");

        $api->any("/test","PaymentController@test");



    });
});



/***
 * 新项目搭建
 * 1、www根目录执行 composer安装 5.4版本 -> composer create-project laravel/laravel 项目名字一般英文 --prefer-dist "5.4.*"
 * 2、任意目录执行  composer安装镜像（可选） ->  composer config -g repo.packagist composer https://packagist.phpcomposer.com
 * 3、项目根目录执行 安装OSS   -> composer require aliyuncs/oss-sdk-php  -> 官方安装 自己写的[ ACommon.php ]
 * 4、安装Dingo  ->
 *      4.1、修改composer.json  require 添加  "dingo/api": "1.0.*@dev"  [ 因为直接composer 暂时不行 ]
 *      4.2、项目根目录运行composer update
 *      4.3、注册服务 在 config/app.php 中注册服务提供者到providers ->  添加配置 Dingo\Api\Provider\LaravelServiceProvider::class,
 *      4.4、生成配置文件 项目根目录运行 -> php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"
 *      4.5、修改生成的配置文件的config/api.php ->  API_PREFIX => api [ 默认为null，改成api  ]
 *      4.6、routes/api.php 文件配置
 *          $api = app('Dingo\Api\Routing\Router');
            $api->version('v1', function ($api) {
                $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {
                //namespace声明路由组的命名空间，因为上面设置了"prefix"=>"api",所以以下路由都要加一个api前缀，比如请求/api/users_list才能访问到用户列表接口
                    $api->any("/test","testController@test");
                });
            });
            // 注意：访问的时候带上api  如： http://xxx.com/api/test
 * 5、安装Redis 根目录下执行 composer require predis/predis （但是要先装Redis软件，以及启动服务）
 * 6、app目录下新建 Model 文件夹  -> 通过 php artisan make:model Models/Article 创建Model
 * 7、安装七牛  composer require qiniu/php-sdk
 *
 *
 */