<html><head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>确认订单</title>
    <link href="/css/bootstrap.min.css"  rel="stylesheet">
    <link href="/css/picker.css" rel="stylesheet" />
    <link href="/css/ucenter/payment/index.css" rel="stylesheet" >
    <script src="/js/newarea/city.js"></script>
    <script src="/js/newarea/picker.min.js"></script>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script>
        function picker() {
            new Picker({
                "title": '请选择',//标题(可选)
                "defaultValue": "",//默认值-多个以空格分开（可选）
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
    </script>
</head>
<body>

<div id="content">
    <div onclick="saveAddress()" style="width: 100%;height: 6rem;line-height: 6rem;background: white;position: relative;">
        <img src="/imgs/addr_to.png" style="position: absolute;width: 4rem;height: 4rem;left: 2rem;top: 1rem;">
        <span style="position: absolute;left: 6rem" id="showAddress">请选择收件人详细地址</span>
        <img src="/imgs/click_large.png" style="position: absolute;width: 2rem;height: 2rem;right: 1rem;top: 2rem;">
    </div>

    <div style="width: 100%;height: 8rem;line-height: 8rem;background: white;top: 7rem;padding: 1rem;">
        <img style="width: 6rem;" src="{{$commodityDetail->img}}">
        <span style="position: absolute;padding: 0.5rem 0.5rem 0 0;left: 8rem;line-height: 2rem;">{{$commodityDetail->remark}}</span>
        {{--<span style="position: absolute;font-size: 1.4rem;top: 2rem;left: 8rem;">男款12克,女款6克</span>--}}
    </div>

    <div style="width: 100%;height: 4rem;line-height: 4rem;background: white;top: 16rem;padding-left: 1rem;">
        <span style="left: 1rem;">数量</span>
        <span style="right: 2rem;color: rgb(255,36,0);">每人仅限领取一条</span>
    </div>

    <div style="width: 100%;height: 4rem;line-height: 4rem;background: white;top: 21rem;padding-left: 1rem;">
        <span style="left: 1rem;">价格</span>
        <span style="right: 2rem;color: rgb(255,36,0);">{{$commodityDetail->price}}</span>
    </div>

    <div style="width: 100%;height: 4rem;line-height: 4rem;background: white;top: 26rem;padding-left: 1rem;">
        <span style="left: 1rem;">物流+打包+人工费</span>
        <span style="right: 2rem;color: rgb(255,36,0);">{{$commodityDetail->freight}}</span>
    </div>

    <div style="width: 100%;height: 4rem;line-height: 4rem;top: 31rem;padding-left: 1rem;">
        温馨提示：收货时无需再给快递员支付费用
    </div>

    <div onclick="callpay()" style="font-size: 1.8rem;color: white;text-align: center;background: rgb(255, 100, 0);width: 100%;height: 5rem;line-height: 5rem;bottom: 0;position: fixed;max-width: 640px">支付费用</div>
</div>
<div id="address_div" style="display: none">
    <div>
        <div style="background: white;height: 5rem;line-height: 5rem;width: 100%;border-top: 1px solid rgba(128, 128, 128, 0.3);"><input placeholder="姓名(根据相关法律法规请使用真实姓名)" id="username" name="username" class="inp" /></div>
        <div style="background: white;height: 5rem;line-height: 5rem;width: 100%;top: 5rem;border-top: 1px solid rgba(128, 128, 128, 0.3);"><input type="number" placeholder="请输入电话号码" id="phone" name="phone" class="inp" /></div>
        <div onclick="picker()" style="width: 100%;background: white;height: 5rem;line-height: 5rem;top: 10rem;border-top: 1px solid rgba(128, 128, 128, 0.3);">
            <div id="province" class="sel" >选择省</div>
            <div id="city" class="sel" >选择市</div>
            <div id="area" class="sel" >选择区</div>
        </div>
        <div style="clear: left;background: white;height: 5rem;line-height: 5rem;width: 100%;top: 15rem;border-top: 1px solid rgba(128, 128, 128, 0.3);"><input placeholder="请输入详细地址信息" id="address" name="address" class="inp" /></div>
    </div>
    <div onclick="saveCore()" style="font-size: 1.8rem;color: white;text-align: center;background: rgb(255, 100, 0);width: 100%;height: 5rem;line-height: 5rem;bottom: 0;max-width: 640px;position: fixed;">保存</div>
</div>
<div id="address_select_zz" style="position: fixed;
    width: 100%;
    height: 100%;
    z-index: 98;
    display: none;
    background: rgba(128, 128, 128, 0.4);
    top: 0;"></div>
<div id="address_select_insert" onclick="showSaveAddress()" style="    position: absolute;
    width: 100%;
    height: 3rem;
    line-height: 3rem;
    color: red;
    bottom: 30%;
    text-align: right;
    padding-right: 1rem;
    background: white;
    display: none;
    z-index: 99;">新增</div>
<div id="address_select" style="position: absolute;
    width: 100%;
    height: 30%;
    overflow: scroll;
    bottom: 0;
    display: none;
    z-index: 99;">
    <ul id="address_select_ul">
        <li>
            <span class="name_phone" >尧涛  1353955613232</span>
            <span class="p_c_a_a">湖北省 武汉市 江汉区 青年路。。</span>
        </li>
    </ul>
</div>
<script>
    var coreid = -1;
    function saveAddress(){
        $.ajax({
            url:"/address",
            type: "get",
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){},
            success:function(res){
                console.log(res);
                // 有地址，可选择 coreid
                if(res.data.result.length>0){
                    var rs = res.data.result;
                    $("#address_select_zz,#address_select,#address_select_insert").show();
                    var _text = "";
                    $.each(rs,function(i,item){
                        _text+="<li onclick='comcore(this)' data-id='"+item.id+"'>" +
                            "<span class='name_phone'>"+item.realname+item.phone+"</span>" +
                            "<span class='p_c_a_a'>"+item.province+item.city+item.area+item.address+"</span>" +
                            "</li>";
                    });
                    $("#address_select_ul").html(_text);
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
    function showSaveAddress() {
        $("#address_select_zz,#address_select,#address_select_insert").hide();
        $("#content").hide();
        $("#address_div").show();
    }
    function comcore(e) {
        coreid = $(e).attr("data-id");
        $("#address_select_zz,#address_select,#address_select_insert").hide();
        $("#showAddress").html($(e).children().eq(1).html());
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
            alert("请输入详细收货地址");return;
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

    function callpay() {
        if(coreid==-1){
            alert("请选择地址");return;
        }
        $.ajax({
            url:"/api/recharge",
            type: "post",
            data:{
                uid:"{{getUid()}}",
                source:1,
                paytype:"{{$is_wx_llq}}",
                ycid:"{{$commodityDetail->id}}",
                coreid:coreid
            },
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){},
            success:function(json){
                if(json.code==200){
//                    window.location.href=json.data.result.out_trade_no;
                    jsApiCall(json.data.result.out_trade_no);
                }
            },
            error: function (XMLHttpRequest) {
                alert("网络错误,请稍后再试!");
            }
        });
    }
    //普通浏览器调起微信支付
    function jsApiCall(out_trade_no) {
        $.ajax({
            url:"/api/pay",
            type: "post",
            data:{
                out_trade_no:out_trade_no
            },
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){},
            success:function(json){
                console.log(json);
                if(json.code==200 && json.data.result.paytype==1){
                    window.location.href=json.data.result.mweb_url;
                }else if(json.code==200 && json.data.result.paytype==2){
                    WeixinJSBridge.invoke('getBrandWCPayRequest',json.data.result.stringData,function(res){
                        var rs = res.err_msg.indexOf("ok");
                        if(rs == -1){
                            alert("订单失败");
                        }else {
                            alert("支付成功");
                        }
                    });
                }
            },
            error: function (XMLHttpRequest) {
                alert("网络错误,请稍后再试!");
            }
        });
    }
</script>
</body>
</html>