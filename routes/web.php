<?php
header("Access-Control-Allow-Origin: *");
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 微信公众号基本配置
// csrf 有过滤
//Route::any('/weixin','Web\WeiXinController@index');
//Route::any('/wxtest','Web\WeiXinController@wxtest');


// 需要登入后才能使用的功能（web 添加了中间件，检测是否登入）
Route::group(['namespace' => 'Web'], function () {



    // 这些功能不需要登入

//    登出
//    Route::post('/login','UsersController@login');
    Route::any('/loginout',function (){
        session()->forget('uid');
        session()->forget('openid');
        session()->flush(); // 清空全部
        return "ok";
    });


    // 针对微信公众号获取openid使用
    Route::get('/wxlogin', 'UsersController@wxlogin');  // 访问网站根,只能在微信打开
//    Route::get('/wxlogin_', function(){
//        session(["openid"=>"oALki0ibNgowGM5CDHEjefF7litg"]);
//    });
    Route::get('/setOpenid', 'UsersController@setOpenid'); // 设置微信使用
//    Route::get('/echoopenid', 'UsersController@echoopenid'); // test 使用

    // =================checklogin================

    Route::get('/index', 'IndexController@index');
    // 获取分类下的配置
    Route::post('/subscribe', 'IndexController@getSubscribe');

    // 需要登入后才能使用的功能（web 添加了中间件，检测是否登入）
    Route::group(['middleware'=>'checklogin'], function () {

        // 下单
        Route::get('/talkadd', 'IndexController@saveTalkadd');
        // 获取收件地址
        Route::get('/address', 'UsersController@getAddress');
        // 保存收货地址
        Route::post('/saveCore','UsersController@saveCore');
        // 预约下单
        Route::post('/saveTalkAdd','TalkController@saveTalkAdd');
        // 个人中心
        Route::get('/ucenter', 'UcenterController@index');
        // 收货地址
        Route::get('/core', 'UcenterController@getCode');
        Route::get('/delcore', 'UcenterController@delCore');
        Route::get('/deltalkadd', 'UcenterController@delTalkAdd');

        Route::get('/usertalklistpage', function(){return view("hs.usertalklist");});// 页面
        Route::get('/usertalklist', 'UcenterController@userTalkList'); // 数据

        Route::get('/complaint',function(){return view("hs.complaint");}); // 投诉建议
        Route::post('/complaintData','UcenterController@complaint'); // 投诉建议

        Route::post('/successShare','UcenterController@successShare'); // 投诉建议

//        Route::get('/index/detail', 'CommodityController@detail');
//        Route::get('/payment', 'CommodityController@payment');

//        Route::get('/userinfo', 'UcenterController@getUserInfo');
//        Route::get('/youbilist', 'UcenterController@getYouBiList'); // 数据
//        Route::get('/youbilistpage', 'UcenterController@getYouBiListPage'); // 页面

//        Route::get('/address', 'UcenterController@getAddress');
//        Route::any('/updateUserInfo', 'UcenterController@updateUserInfo');


        // 我的二维码
//        Route::any('/ewm','UcenterController@inviterImg');

    });
});



// 可随时变动后台路径
define("htname","ht");

// http://fontawesome.dashgame.com/ 菜单图标，备份下，可能考虑换阿里

Route::group(['namespace' => 'Ht'], function () {

    // 这些功能不需要登入
    Route::get('/'.htname.'/index', 'IndexController@index');
    Route::post('/'.htname.'/login', 'IndexController@login');
    Route::get('/'.htname.'/loginout', function (){
        session()->forget('admin');
        session()->flush();
        return redirect('/'.htname.'/index');
    });

    // 需要登入后才能使用的功能（web 添加了中间件，检测是否登入）
    Route::group(['middleware'=>'htchecklogin'], function () {

        // Page
        // 后台系统主页
        Route::get("/".htname."/homepage", 'HomePageController@index');
        // 订单管理页面
        Route::get("/".htname."/talkadd", 'TalkAddController@index');
        // 回收分类添加页面
        Route::get("/".htname."/addtypes", 'TypesController@addTypes');
        // 回收分类列表
        Route::get("/".htname."/typeslist", 'TypesController@getTypesList');
        // 菜单页面
        Route::get("/".htname."/menu", 'HomePageController@menu');
        // 用户管理
        Route::get("/".htname."/userlist", 'UserController@userListPage');
        // url管理
        Route::get("/".htname."/orderpage", 'UserController@orderPage');
        // 投诉
        Route::get("/".htname."/complaint", 'UserController@complaintList');

        // 订单数据导出
        Route::get("/".htname."/etld", 'TalkAddController@exportTalkListData');



        // =============================================================================================================



        // 功能

        // 菜单获取&&保存
        Route::post("/".htname."/menuUpdate", 'HomePageController@menuUpdate');
        Route::post("/".htname."/menuSave", 'HomePageController@menuSave');

        // 添加类型（大分类下面添加子分类）
        Route::post("/".htname."/typesSave", 'TypesController@saveTypes');

        // 分类详情
        Route::post("/".htname."/typesDetail", 'TypesController@detailTypes');
        // 修改子分类
        Route::post("/".htname."/typesUpdate", 'TypesController@updateTypes');
        // 删除子分类
        Route::get("/".htname."/typesDel", 'TypesController@typesDelete');
        // 更新url
        Route::post("/".htname."/saveUrlPage", 'UserController@saveUrlPage');
        // 保存系统配置
        Route::post("/".htname."/saveConfig", 'UserController@saveConfig');

        // 用户管理

//        // 订单详情
        Route::post("/".htname."/talkDetail", 'TalkAddController@talkDetail');
//        // 修改订单状态
        Route::post("/".htname."/talkDelivery", 'TalkAddController@talkDelivery');



    });

});

