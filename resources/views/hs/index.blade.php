<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>咸鱼SOGO预约回收</title>
    {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="/css/bootstrap.min.css"  rel="stylesheet">
    <link href="/css/hs/index.css"  rel="stylesheet">
    {{--<link href="/css/mescroll.min.css"  rel="stylesheet">--}}
    <script src="/js/jquery.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    {{--试试看 mescroll--}}
</head>
<body>
<div id="content">
    {{--宣传图 or 门店图--}}
    {{--?imageView2/1/w/200--}}
    <img style="width: 100%;height: 12.5rem" src="{{$mmlogo}}" />
    <ul>
        @foreach($types as $type)
            <li data-target-id="{{$type->id}}">{{$type->type_name}}</li>
        @endforeach
    </ul>
    {{--https://protal.szsldy.com/loading2.gif--}}
    <table class="table table-striped" id="showData" style="text-align: center"></table>
    <div class="yuyue_but" onclick="talkAdd()" >点击预约</div>
</div>
@include("layouts._loading")
</body>
</html>
<script>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '{{$__appid}}', // 必填，公众号的唯一标识
        timestamp: '{{$__timestamp}}', // 必填，生成签名的时间戳
        nonceStr: '{{$__noncestr}}', // 必填，生成签名的随机串
        signature: '{{$__signature}}',// 必填，签名，见附录1
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function(){
        wx.onMenuShareTimeline({
            title: '咸鱼SOGO', // 分享标题
            link: '{{url('/index')}}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{$mmlogo}}', // 分享图标
            success: function () {
                successShare();
            },
            cancel: function () {
                alert("取消分享,获取积分失败");
            }
        });
        wx.onMenuShareAppMessage({
            title: '咸鱼SOGO,预约回收', // 分享标题
            desc: '您身边的资源循环管理站，为您带走生活的闲余，给您带来简约、洁净的环境。废品秒变现，就在指尖。',
            //link: 'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzUyNTQ5MDk0NA==&scene=124#wechat_redirect',
            link: '{{url('/index')}}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '{{$mmlogo}}', // 分享图标
            success: function () {
                successShare();
            },
            cancel: function () {
                alert("取消分享,获取积分失败");
            }
        });
    });

</script>
<script>
    function successShare() {
        $.post("/successShare",{_token:"{{csrf_token()}}"});
    }

    var types_id = "{{$types[0]->id}}";

    // 下单页面
    function talkAdd() {
        window.location.href="/talkadd?types_id="+types_id
    }
    
    function getData(id) {
        $.ajax({
            type: "post",
            url: "/subscribe",
            data:{
                _token:"{{csrf_token()}}",
                id:id
            },
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){
                showLoading();
            },
            success:function(res){
                hideLoading();
                console.log(res);
                types_id = id;
                $("#content ul li").each(function(){
                    if($(this).attr("data-target-id") == id){
                        $(this).addClass("backred");
                    }
                });
                var tables = "";
                $.each(res.data.result,function (i,e) {
                    tables +="" +
                        "<tr>" +
                        "<td style='width: 10%;vertical-align: middle;' ><img class='rem4' src='"+res.data.result[i].imgs+"' /></td>" +
                        "<td style='width: 20%;vertical-align: middle;font-size: 1.6rem;'>"+res.data.result[i].type_name+"</td>" +
                        "<td style='width: 30%;vertical-align: middle;font-size: 1.6rem;'>"+res.data.result[i].prices+""+res.data.result[i].units+"</td>" +
                        "<td style='width: 40%;vertical-align: middle'>"+res.data.result[i].remark+"</td>" +
                    "</tr>";
                });
                $("#showData").html(tables);
            },
            error: function (XMLHttpRequest) {
                console.log("ajax error: \n" + XMLHttpRequest.responseText);
            }
        });
    }

    $(function () {

        $("#content ul li").on("click",function () {
            getData($(this).attr("data-target-id"));
            $("#content ul li").removeClass("backred");
            $(this).addClass("backred");
        });

        getData("{{$types[0]->id}}"); // 默认加载第一个

    });
</script>