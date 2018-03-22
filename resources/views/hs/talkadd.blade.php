<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>预约回收</title>
    {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="/css/bootstrap.min.css"  rel="stylesheet">
    <link href="/css/picker.css" rel="stylesheet" />
    <link href="/css/hs/index.css"  rel="stylesheet">
    <link href="https://at.alicdn.com/t/font_479436_06yxtuqy397iizfr.css"  rel="stylesheet">
    {{--<link href="/css/mescroll.min.css"  rel="stylesheet">--}}
    <script src="/js/newarea/city.js"></script>
    <script src="/js/newarea/picker.min.js"></script>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    {{--试试看 mescroll--}}
</head>
<body>
    <div id="content">

        <div class="line_div line_pos" onclick="saveAddress()"><i class="iconfont icon-dizhi"></i><span id="showAddress">选择上门收取地址</span></div>
        <div class="line_div line_pos" onclick="showTime()"><i class="iconfont icon-shijiancopy"></i><span id="showTime">选择上门收取时间</span></div>
        <textarea  class="form-control line_pos" id="remark" style="resize : none;height: 8rem;" placeholder="有话说（下单要告诉我们其他的事情）"></textarea>

        <div class="yuyue_but2" onclick="talkAdd()" >立即预约</div>


        {{--手机号--}}
        {{--验证码--}}

    </div>

    <div id="address_div" style="display: none">
        <div>
            <input placeholder="姓名(根据相关法律法规请使用真实姓名)" id="username" name="username" class="form-control address_line" />
            <input type="number" placeholder="请输入电话号码" id="phone" name="phone" class="form-control address_line"  />
            <div onclick="picker()" class="picker_div address_line">
                <div id="province" class="sel" >选择省</div>
                <div id="city" class="sel" >选择市</div>
                <div id="area" class="sel" >选择区</div>
            </div>
            <textarea  class="form-control xxdz address_line" id="address" style="height: 8rem" placeholder="详细地址"></textarea>
        </div>
        <div onclick="saveCore()" class="yuyue_but2">保存</div>
    </div>
    <div id="address_select_div" style="display: none">
        <ul id="address_content" style="padding: 1rem;">
            {{--<li style="height: 6rem;">--}}
                {{--<span style="margin-right: 2rem">尧先生</span>--}}
                {{--<span>13539565631</span><br/>--}}
                {{--<span>湖北省武汉市江岸区这里是详细地址</span>--}}
            {{--</li>--}}
        </ul>
        <div onclick="insertCore()" class="yuyue_but3">新增地址</div>
    </div>

    <div id="time_select_div" style="display: none;text-align: center">
        <ul id="time_content" style="padding: 1rem;">
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">今天</span><span>09:00 - 10:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">今天</span><span>10:00 - 12:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">今天</span><span>13:00 - 15:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">今天</span><span>15:00 - 17:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">今天</span><span>17:00 - 19:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">今天</span><span>19:00 - 21:00</span></li>
            <li><hr/></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">明天</span><span>09:00 - 10:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">明天</span><span>10:00 - 12:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">明天</span><span>13:00 - 15:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">明天</span><span>15:00 - 17:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">明天</span><span>17:00 - 19:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">明天</span><span>19:00 - 21:00</span></li>
            <li><hr/></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">后天</span><span>09:00 - 10:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">后天</span><span>10:00 - 12:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">后天</span><span>13:00 - 15:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">后天</span><span>15:00 - 17:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">后天</span><span>17:00 - 19:00</span></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">后天</span><span>19:00 - 21:00</span></li>
            <li><hr/></li>
            <li style="height: 4rem;line-height: 4rem;"><span style="margin-right: 2rem">其他时间</span><span>留言“有话说”</span></li>
        </ul>
        {{--选择时间--}}
    </div>


@include("layouts._loading")
</body>
</html>
<script>

    var coreid = -1;
    var day = -1;
    var time = -1;
    function insertCore() {
        // 没有地址，则跳转新增
        $("#address_select_div").hide();
        $("#address_div").show();
    }

    function talkAdd() {
        if(day == -1 || time == -1){
            alert("请选择方便上门时间");return;
        }
        if(coreid == -1){
            alert("请选择上门回收地址");return;
        }
        $.ajax({
            type: "post",
            url: "/saveTalkAdd",
            data:{
                _token:"{{csrf_token()}}",
                coreid:coreid,
                day:day,
                time:time,
                types_id :"{{$types_id}}",
                remark : $("#remark").val()
            },
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){
                showLoading();
            },
            success:function(res){
                hideLoading();
                console.log(res);
                if(res.code == 200){
                    alert("预约成功");
                }
                window.location.href="/ucenter" ;  // 首页，或者个人中心

            },
            error: function (XMLHttpRequest) {
                console.log("ajax error: \n" + XMLHttpRequest.responseText);
            }
        });
    }

    function comcore(e) {
        coreid = $(e).attr("data-id");
        $("#address_select_div").hide();
        $("#content").show();
        $("#showAddress").html($(e).children().eq(3).html()); // 注意布局的地方，有个 <br />
    }

    function showTime() {
        $("#content").hide();
        $("#time_select_div").show();
    }

    function saveAddress(){
        $.ajax({
            url:"/address",
            type: "get",
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){
                showLoading();
            },
            success:function(res){
                console.log(res);
                hideLoading();
                // 有地址，可选择 coreid
                if(res.data.result.length>0){
                    var rs = res.data.result;
//                <li style="height: 6rem;">
//                        <span style="margin-right: 2rem">尧先生</span>
//                        <span>13539565631</span><br/>
//                        <span>湖北省武汉市江岸区这里是详细地址</span>
//                        </li>
                    $("#content").hide();
                    $("#address_select_div").show();
                    var _text = "";
                    $.each(rs,function(i,item){
                        _text+="<li style='height: 6rem' onclick='comcore(this)' data-id='"+item.id+"'>" +
                            "<span style='margin-right: 2rem'>"+item.username+"</span>" +
                            "<span>"+item.phone+"</span><br/>" +
                            "<span>"+item.province+item.city+item.area+item.address+"</span>" +
                            "</li>";
                    });
                    $("#address_content").html(_text);
                }else{
                    // 没有地址，则跳转新增
                    $("#content").hide();
                    $("#address_div").show();
                }
            },
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest.responseText);
            }
        });
    }

    function saveCore() {
        var username = $("#username").val();
        var province = $("#province").html();
        var city = $("#city").html();
        var area = $("#area").html();
        var phone = $("#phone").val();
        var address = $("#address").val();
//        console.log([username,province,city,phone,address]);return;
        if(username=="" || username.length<=0){
            alert("请输入名字");return;
        }
        if(province=="" || province.length<=0){
            alert("请选择省");return;
        }
        if(city=="" || city.length<=0){
            alert("请选择市");return;
        }
        if(phone=="" || phone.length<=0){
            alert("请输入电话号码");return;
        }
        if(address=="" || address.length<=0){
            alert("请输入详细上门地址");return;
        }
        if(province!="湖北省" || city!="武汉市"){
            alert("我们目前只服务于湖北省 武汉市内哦");return;
        }
        $.ajax({
            url:"/saveCore",
            type: "post",
            data:{
                "_token":"{{csrf_token()}}",
                username:username,
                province:province,
                city:city,
                area:area,
                phone:phone,
                address:address
            },
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){},
            success:function(res){
                console.log(res);
                if(res.code==200){
                    coreid = res.data.result.coreid;
                    $("#showAddress").html(res.data.result.address);
                    $("#address_div").hide();
                    $("#content").show();
                }else{
                    alert("保存地址失败!");
                }
            },
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest.responseText);
                alert("网络错误,请稍后再试!");
            }
        });
    }

    function picker() {
        new Picker({
            "title": '请选择',//标题(可选)
            "defaultValue": "湖北省 武汉市 ",//默认值-多个以空格分开（可选）
            //"type": 3,//需要联动级数[1、2、3]（可选、不传时默认获取数据的深度,最多3级）
            "data": cityData,//数据(必传)
            "keys": {
                "id": "Code",
                "value": "Name",
                "childData": "level"//最多3级联动
            },//数组内的键名称(必传，id、text、data)
            "callBack": function (val) {
                console.log(val);
                var ssq = val.split(" ");
                $("#province").html(ssq[0]);
                $("#city").html(ssq[1]);
                $("#area").html(ssq[2]);
                //回调函数（val为选择的值）
            }
        });
    }

    var types_id = "{{$types_id}}"; // 预约类型 。

    $(function () {
        $("#time_content li").on("click",function(){
            $("#time_select_div").hide();
            $("#content").show();
            day = $(this).children().eq(0).html();
            time = $(this).children().eq(1).html();
            $("#showTime").html(day+time);
        });

    });
</script>