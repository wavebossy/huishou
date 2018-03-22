<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>{{$webTitle}}</title>
    {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="/css/bootstrap.min.css"  rel="stylesheet">
    <link href="https://at.alicdn.com/t/font_404372_bjgc0xyrqyiltyb9.css" rel="stylesheet" >
    <link href="/css/index.css"  rel="stylesheet">
    <link href="/css/mescroll.min.css"  rel="stylesheet">
    <script src="/js/jquery.min.js"></script>
    {{--试试看 mescroll--}}
    <script src="/js/iscroll5.js"></script>
    <script src="/js/mescroll.min.js"></script>
</head>
<body>
<div id="content">
    <!--轮播图-->
    <div id="viewport">
        <div id="wrapper">
            <div id="scroller">
                @foreach($commodityBanners as $banner)
                    <div class="slide" >
                        <img onclick="gdetail('{{$banner->id}}')" class="painting lunbo" src="{{$banner->img}}" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{--<div id="indicator">--}}
        {{--<div id="dotty"></div>--}}
    {{--</div>--}}
    <div id="goodsList" class="mescroll" >
        <ul id="dataList" class="data-list">
            {{--<li>--}}
                {{--<img class="pd-img" src="https://laigaonew.laigao520.com/2017101014300163aee9d219bc8ac.jpeg"/>--}}
                {{--<p class="pd-name">商品标题商品标题商品标题商品标题商品标题商品</p>--}}
                {{--<p class="pd-price">200.00 元</p>--}}
                {{--<p class="pd-sold">已售50件</p>--}}
            {{--</li>--}}
            @foreach($commodityLists as $commodityList)
                <li onclick="gdetail('{{$commodityList->id}}')">
                    <img class="pd-img" src="{{$commodityList->img}}"/>
                    <p class="pd-name">{{str_limit($commodityList->remark,30)}}</p>
                    <p class="pd-price">{{$commodityList->price}}</p>
                    <p class="pd-sold"><s>{{$commodityList->oprice}}</s></p>
                </li>
            @endforeach
        </ul>
        {{--<ul class="data-list">--}}
            {{--<li style="height: 4rem;"></li>--}}
        {{--</ul>--}}
    </div>
    {{--<div id="goodsList" class="mescroll">--}}
        {{--<ul id="dataList">--}}
            {{--@foreach($commodityLists as $commodityList)--}}
                {{--<li onclick="gdetail('{{$commodityList->id}}')">--}}
                    {{--<img style="width: 100%;" src="{{$commodityList->img}}">--}}
                    {{--<div class="priceDiv">{{$commodityList->price}}<s>{{$commodityList->oprice}}</s></div>--}}
                    {{--<div class="priceText">{{str_limit($commodityList->remark,30)}}</div>--}}
                {{--</li>--}}
            {{--@endforeach--}}
        {{--</ul>--}}
        {{--<ul>--}}
            {{--<li style="height: 5rem;"></li>--}}
            {{--<li style="height: 5rem;"></li>--}}
        {{--</ul>--}}
    {{--</div>--}}
    @include("layouts._fottor")
</div>
</body>
</html>

<script>

    function gdetail(e) {
        window.location.href="/index/detail?id="+e;
    }

    var width = $(window).width();
    if(width>=640){
        width = 640;
    }
    $(function () {
        // 多少张图 * 几
        $("#scroller").width(width*parseInt("{{sizeof($commodityBanners)}}"));
        $("#viewport,#wrapper,.lunbo,.slide").width(width);
        new IScroll('#wrapper', {
            preventDefault:false,
            disablePointer: true,
            disableTouch:false,
            disableMouse:true,
            scrollX: true,
            scrollY: false,
            momentum: false,
            snap: true,
            snapSpeed: 400,
            keyBindings: true
//            indicators: {
//                el: document.getElementById('indicator'),
//                resize: false
//            }
        });
        var mescroll = new MeScroll("goodsList", {
            down: {
                auto: false, //是否在初始化完毕之后自动执行下拉回调callback; 默认true
                callback: downCallback //下拉刷新的回调
            },
            up: {
                auto:false,
                clearEmptyId: "dataList", //1.下拉刷新时会自动先清空此列表,再加入数据; 2.无任何数据时会在此列表自动提示空
                callback: getListData  //上拉回调,此处可简写; 相当于 callback: function (page) { getListData(page); }
            }
        });

        /*联网加载列表数据  page = {num:1, size:10}; num:当前页 从1开始, size:每页数据条数 */
        function getListData(page){
            console.log(page);
            $.ajax({
                type: "post",
                url: "/api/commodityList",
                data:{
                    page:page.num,
                    pageSize:page.size
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                },
                success:function(res){
                    console.log(res);
                    mescroll.endSuccess(res.data.result.length);
                    setListData(res.data.result);
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
                type: "post",
                url: "/api/commodityList",
                data:{
                    page:1,
                    pageSize:10
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                },
                success:function(res){
                    mescroll.endSuccess();
                    console.log(res);
                    var _temp = "";// dataList
                    data = res.data.result;
                    for (var i = 0; i < data.length; i++) {
                        _temp += "<li onclick='gdetail("+data[i].id+")'>" +
                            "<img class='pd-img' src='"+data[i].img+"' />" +
                            "<p class='pd-name'>"+data[i].remark+"</p>" +
                            "<p class='pd-price'>"+data[i].price+"</p>" +
                            "<p class='pd-sold'><s>"+data[i].oprice+"</s></p>" +
                            "</li>";
                    }
                    $("#dataList").html(_temp);
                },
                error: function (XMLHttpRequest) {
                    mescroll.endErr();
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });

        }

        /*设置列表数据*/
        function setListData(data){
            var _temp = "";// dataList
            for (var i = 0; i < data.length; i++) {
                _temp += "<li onclick='gdetail("+data[i].id+")'>" +
                    "<img class='pd-img' src='"+data[i].img+"' />" +
                    "<p class='pd-name'>"+data[i].remark+"</p>" +
                    "<p class='pd-price'>"+data[i].price+"</p>" +
                    "<p class='pd-sold'><s>"+data[i].oprice+"</s></p>" +
                    "</li>";
            }
            $("#dataList").append(_temp);
        }
        setTimeout(function () {
//            console.log(mescroll.optDown);
//            console.log(mescroll.optUp);
//            mescroll.triggerUpScroll();
//            mescroll.triggerUpScroll();
//            mescroll.triggerUpScroll();
        },1000);
        //禁止PC浏览器拖拽图片,避免与下拉刷新冲突;如果仅在移动端使用,可删除此代码
        document.ondragstart=function() {return false;}

    });
//    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
</script>