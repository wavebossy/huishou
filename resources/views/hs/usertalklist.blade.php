<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>订单记录</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    {{--<link href="https://at.alicdn.com/t/font_404372_bjgc0xyrqyiltyb9.css" rel="stylesheet" >--}}
    <link href="/css/ucenter/usertalklist/index.css" rel="stylesheet" >
    <link href="/css/mescroll.min.css"  rel="stylesheet">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/mescroll.min.js"></script>
</head>
<style>
    .navspan{
        position: relative;
        top: 0;
        width: 50%;
        height: 3rem;
        line-height: 3rem;
        text-align: center;
        display: block;
        float: left;
    }
    .navcolor{
        background: #ff3600;
        color: white;
    }
</style>
<body>
@include("layouts._loading")
<div id="content">
    <nav style="height: 3rem;line-height: 3rem;" id="navs">
        <span class="navspan navcolor" onclick="status(this)" data-id="1">待完成</span>
        <span class="navspan" onclick="status(this)" data-id="2">已完成</span>
    </nav>
    <div id="goodsList" class="mescroll" >
        <ul id="dataList">
            {{--<li>--}}
                {{--<span>生活类</span><br/>--}}
                {{--<span>预约时间 2017-11-21 13:33 - 15:33</span>--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--<span>生活类</span>--}}
                {{--<span>回收获得 20 元</span><br/>--}}
                {{--<span>完成时间 2017-11-22 15:16:42</span>--}}
            {{--</li>--}}
        </ul>
    </div>
</div>
</body>
</html>

<script>

    var status__ = 1;
    var mescroll;
    $(function () {
        mescroll = new MeScroll("goodsList", {
            down: {
                auto: true, //是否在初始化完毕之后自动执行下拉回调callback; 默认true
                callback: downCallback //下拉刷新的回调
            },
            up: {
                auto:false,
                clearEmptyId: "dataList", //1.下拉刷新时会自动先清空此列表,再加入数据; 2.无任何数据时会在此列表自动提示空
                callback: getListData  //上拉回调,此处可简写; 相当于 callback: function (page) { getListData(page); }
            }
        });
        //禁止PC浏览器拖拽图片,避免与下拉刷新冲突;如果仅在移动端使用,可删除此代码
        document.ondragstart=function() {return false;};
    });
    /*联网加载列表数据  page = {num:1, size:10}; num:当前页 从1开始, size:每页数据条数 */
    function getListData(page){
        console.log(page);
        $.ajax({
            type: "get",
            url: "/usertalklist",
            data:{
                status:status__,
                page:page.num,
                pageSize:page.size
            },
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){
                showLoading();
            },
            success:function(res){
                hideLoading();
                console.log(res);
                mescroll.endSuccess(res.data.result.length);
                setListData(res.data.result,"append");
                //mescroll.endSuccess( res.data.result.length, res.time );
            },
            error: function (XMLHttpRequest) {
                mescroll.endErr();
                console.log("ajax error: \n" + XMLHttpRequest.responseText);
            }
        });
    }

    /*联网加载列表数据  page = {num:1, size:10}; num:当前页 从1开始, size:每页数据条数 */
    function downCallback(page){
        $.ajax({
            type: "get",
            url: "/usertalklist",
            data:{
                status:status__,
                page:1,
                pageSize:10
            },
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){
                showLoading();
            },
            success:function(res){
                mescroll.endSuccess();
                hideLoading();
                console.log(res);
                setListData(res.data.result,"html");
            },
            error: function (XMLHttpRequest) {
                mescroll.endErr();
                console.log("ajax error: \n" + XMLHttpRequest.responseText);
            }
        });

    }
    /*设置列表数据*/
    function setListData(data,append){
        var _temp = "";// dataList
        if(status__==1){
            for (var i = 0; i < data.length; i++) {
                _temp += "<li>" +
                    "<span>"+data[i].type.type_name+"</span>" +
                    "<span style='position: absolute;right: 2rem;'><a href='/deltalkadd?id="+data[i].id+"'>删除</a></span>" +
                    "<br/>" +
                    "<span>预约时间 "+data[i].day_time+"</span>" +
                    "</li>";
            }
            {{--<span>生活类</span>--}}
            {{--<span>回收获得 20 元</span><br/>--}}
            {{--<span>完成时间 2017-11-22 15:16:42</span>--}}
        }else{
            for (var i = 0; i < data.length; i++) {
                _temp += "<li>" +
                    "<span>"+data[i].type.type_name+"</span>" +
                    "<span style='position: absolute;right: 2rem;'><a href='/deltalkadd?id="+data[i].id+"'>删除</a></span>" +
                    "<span>回收获得 "+data[i].summoney+" 元</span><br/>" +
                    "<span>完成时间 "+data[i].times+"</span>" +
                    "</li>";
            }
        }
        if(append=="append"){
            $("#dataList").append(_temp);
        }else{
            $("#dataList").html(_temp);
        }
    }
    function gdetail(e) {
        window.location.href="/index/detail?id="+e;
    }
    function status(e) {
        $("#navs > span").removeClass("navcolor");
        $(e).addClass("navcolor");
        status__ = $(e).attr("data-id");
        console.log(status__);
//        mescroll.triggerDownScroll();
//        加载动画
        showLoading();
        downCallback();
    }


</script>