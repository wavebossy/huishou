
@section('style')
    <style>
        /* WebKit browsers */
        input::-webkit-input-placeholder { padding-left: 1rem;font-size: 1.4rem  }
        /* Mozilla Firefox 4 to 18 */
        input:-moz-placeholder {  padding-left: 1rem;font-size: 1.4rem  }
        /* Internet Explorer 10 */
        input:-ms-input-placeholder { padding-left: 1rem;font-size: 1.4rem  }
        input::placeholder {  padding-left: 1rem; font-size: 1.4rem }
        #login{
            display: none;
            position: fixed;
            width: 80%;
            left: 10%;
            background: white;
            top: 15%;
            z-index: 2;
            border-radius: 15px;
            padding: 1.5rem;
        }
        #zz{
            display: none;
            position: fixed;width: 100%;height: 100%;background: rgba(128, 128, 128, 0.4);top: 0;z-index: 1;
        }
        .input_phone{
            width: 100%;
            height: 3rem;
            border-radius: 15px;
            border: 1px solid rgba(128, 128, 128, 0.4);
            margin: 1rem 0;
        }
        .input_v{
            width: 50%;
            height: 3rem;
            float: left;
            border-radius: 15px;
            border: 1px solid rgba(128, 128, 128, 0.4);
            margin: 1rem 5% 1rem 0;
        }
        .v_div{
            height: 3rem;
            line-height: 3rem;
            border-radius: 15px;
            margin: 1rem 0;
            background: #ff3600;
            color: white;
            width: 45%;
            float: left;
            font-size: 1.6rem;
        }
        .imgs{
            width: 30%;
            margin: 1rem 0;
            border-radius: 50%;
        }
        .sub_div{
            clear: left;
            width: 100%;
            height: 4rem;
            background: #ff3600;
            color: white;
            border-radius: 15px;
            line-height: 4rem;
            margin-top: 7rem;
        }
    </style>
@show
@include("layouts._loading")
<div id="login" style="">
    <div style="text-align: center">
        <img class="imgs" src="http://wx.qlogo.cn/mmopen/G36dsoBiapGUVeg6lw5sI73LrYnATYkrV9nibO0wC4CQ5X4qJuJDOdqKQ5aicG0Ueibujx6pUico79FjDZNBtNTWEknX469aBkj8g/0" />
        <div>邮来一九，由来已久</div>
        <form id="form">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <input class="input_phone" type="text" name="phone" value="" placeholder="请输入手机号" />
            <input class="input_v" type="text" name="codes" value="" placeholder="验证码" />
            <div class="v_div" onclick="getUserMsgCode(this)">获取验证码</div>
            <div class="sub_div" onclick="submitStoreType()">立即领取</div>
        </form>
    </div>
</div>
<div id="zz"></div>

<script src="/js/jquery.min.js"></script>
<script>
    $(function () {
        if(!(Boolean)("{{getUid()}}")){
            $("#login,#zz").show()
        }
    });
    function login(){
        window.location.href="/sessionLogin";
    }

    // 验证码
    var countdown=30, is_send=true ;
    function settime(e){
        if (countdown == 0) {
            $(e).text("重新获取");
//            $(e).css("background-color","transparent");
            countdown=30;
            is_send =true;
            return;
        }else {
//            $(e).css("background-color","rgb(162, 162, 162);");
            $(e).text("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function() {
            settime(e);
        },1000)
    }
    function getUserMsgCode(e){
        if(!is_send){return;}
        var phone = $("input[name='phone']").val();
        if(phone.length<11){
            alert("请输入手机号!");return;
        }
        $.ajax({
            type: "post",
            url: "/api/sendMsgCode",
            data:{phone:phone},// 手机号传过去
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){
                showLoading();
            },
            success:function(res){
                hideLoading();
                is_send = false;
                console.log(res);
                if(res.code==200 && res.errorcode=="" ){
                    alert("短信发送成功!请注意接收!");
                    settime(e);
                }else{
                    alert(res.errormsg);
                }
            },
            error: function (XMLHttpRequest) {
                hideLoading();
                console.log("ajax error: \n" + XMLHttpRequest.responseText);
            }
        });
    }
    function submitStoreType() {
        var _input_ver = $("input[name='codes']").val();
        if(_input_ver=="" || _input_ver.length<=0 ){
            alert("请输入正确的验证码");
        }else{
            var form = new FormData($("#form")[0]);
            $.ajax({
                type: "post",
                url: "/login",
                data:form,
                cache:false,
                contentType : false,
                processData : false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                    showLoading();
                },
                success:function(res){
                    hideLoading();
                    console.log(res);
                    if(res.code==200 && res.errorcode=="" ){
                        login();
                    }else{
                        alert(res.errormsg);
                    }
                },
                error: function (XMLHttpRequest) {
                    hideLoading();
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }
    }
</script>