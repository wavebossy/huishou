<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>个人中心</title>
    <link href="/css/bootstrap.min.css"  rel="stylesheet">
    <link href="https://at.alicdn.com/t/font_479436_06yxtuqy397iizfr.css" rel="stylesheet" >
    <link href="/css/ucenter/index.css" rel="stylesheet" >
    @yield('style')
    <style>
        #ewm{
            display: none;
            position: fixed;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(128, 128, 128, 0.3);
            z-index: 99;
        }
        #ewm img{
            width: 80%;
            margin: 10%;
            top: 20%;
            position: absolute;
        }
    </style>
    <script>
        function usertalklistpage() {
            window.location.href="/usertalklistpage";
        }
        function core() {
            window.location.href="/core";
        }
        function userinfo() {
            window.location.href="/userinfo";
        }
    </script>
</head>
<body>

<div id="content">
    <div id="ucInfoDiv" onclick="userinfo()">
        <img id="usImg" src="{{$userinfos->user_imgs}}" />
        <span id="usName">{{$userinfos->user_name}}</span>
        <span id="usPhone">积分： {{$userinfos->score}}</span>
    </div>
    <div id="baobei" onclick="usertalklistpage()" >
        <span class="spanIcon"><i class="iconfont icon-baobei _yellow"></i></span>
        <span class="spanText">预约订单列表</span>
        <span class="rightImg"><i class="iconfont icon-gengduo gray08"></i></span>
    </div>
    <div id="dizhi_" onclick="core()">
        <span class="spanIcon"><i class="iconfont icon-dizhi _bule"></i></span>
        <span class="spanText">我的收货地址</span>
        <span class="rightImg"><i class="iconfont icon-gengduo gray08"></i></span>
    </div>
    <div id="erweuma" onclick="window.location.href='{{$orderPage[0]->url_page}}'">
        <span class="spanIcon"><i class="iconfont icon-lianmeng _green"></i></span>
        <span class="spanText">{{$orderPage[0]->url_name}}</span>
        <span class="rightImg"><i class="iconfont icon-gengduo gray08"></i></span>
    </div>
    <div id="erweuma" onclick="window.location.href='{{$orderPage[1]->url_page}}'">
        <span class="spanIcon"><i class="iconfont icon-zhaoxiannashi _red"></i></span>
        <span class="spanText">{{$orderPage[1]->url_name}}</span>
        <span class="rightImg"><i class="iconfont icon-gengduo gray08"></i></span>
    </div>
    <div id="erweuma" onclick="window.location.href='{{$orderPage[2]->url_page}}'">
        <span class="spanIcon"><i class="iconfont icon-bangzhu _red"></i></span>
        <span class="spanText">{{$orderPage[2]->url_name}}</span>
        <span class="rightImg"><i class="iconfont icon-gengduo gray08"></i></span>
    </div>
</div>
</body>
</html>

