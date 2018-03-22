<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>个人资料</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    {{--<link href="https://at.alicdn.com/t/font_404372_bjgc0xyrqyiltyb9.css" rel="stylesheet" >--}}
    <link href="/css/ucenter/userinfo/index.css" rel="stylesheet" >
    <script src="/js/jquery.min.js"></script>

</head>
<body>

<div id="content">
    <form action="/updateUserInfo" method="post" id="form" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div style="text-align: center;height: 20%;position: relative;top: 5rem;">
            <img style="height: 100%;" id="showuserimg" src="{{$userinfos->userimg}}" onclick="$('input[name=userimg]').click()" />
            <div id="infile" style="display: none">
                <input type="file" id="userimg" name="userimg" onchange="javascript:setImagePreview();" />
            </div>
            <input type="hidden" value="" id="img" name="img" />
        </div>
        <div style="position: relative;top: 8rem;">
            <input type="text" name="username" value="{{$userinfos->username}}" />
        </div>
        <div style="position: relative;top: 10rem;">
            <input type="tel" name="phone" value="{{$userinfos->phone}}" />
        </div>

        <div id="subdiv" onclick="submits()">保存</div>

    </form>
    <article class="container">
        <div id="clipArea"></div>
        <button id="clipBtn">截取</button>
        <div id="view"></div>
    </article>
</div>
</body>
</html>
{{--头像裁剪--}}
<script src="/js/photoclip/iscroll-zoom.js"></script>
<script src="/js/photoclip/hammer.js"></script>
<script src="/js/photoclip/jquery.photoClip.js"></script>
<script>
    $(function () {
        $("#clipArea").photoClip({ // clipArea []  虚线框框
            width: 200,  // 截取的宽高
            height: 200,
            file: "#userimg", // 真实选择图片的input
            view: "#view", // 截取后，需要显示的地方
            ok: "#clipBtn",// 剪裁的按钮
            loadStart: function() {
                // 可以放置正在加载的图片
                console.log("照片读取中");
            },
            loadComplete: function() {
                console.log("照片读取完成");
            },
            clipFinish: function(dataURL) {
                $(".photo-clip-rotateLayer").empty();
            }
        });
        $("#clipBtn").click(function(){
            $("#showuserimg").attr("src",imgsource); // 显示
            $("#img").val(imgsource); // 当做text 提交
            $(".container").hide();
        });
//        $.ajax({
//            type: "post",
//            url: "",
//            data:{},
//            cache:false,
//            dataType: "json",
//            beforeSend:function(XMLHttpRequest){
//            },
//            success:function(res){
//
//            },
//            error: function (XMLHttpRequest) {
//                console.log("ajax error: \n" + XMLHttpRequest.responseText);
//            }
//        });
    });

    //下面用于图片上传预览功能
    function setImagePreview() {
        $(".container").show();// 显示裁剪
        var docObj=document.getElementById("userimg");
        var imgObjPreview=document.getElementById("showuserimg");
        if(docObj.files[0] && docObj.files){
            //火狐下，直接设img属性
            imgObjPreview.style.display = 'inline';
//            var newWindowWidth = $(window).width();
//            if(newWindowWidth >= 375){
//                imgObjPreview.style.width = "200px";
//                imgObjPreview.style.height = "180px";
//            }else{
//            imgObjPreview.style.width = "30%";
//            imgObjPreview.style.height = "30%";
//            }
            //imgObjPreview.src = docObj.files[0].getAsDataURL();
            //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式
            imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);

//            docObj.style.display = 'inline';
        }else{
            //IE下，使用滤镜
            docObj.select();
            var imgSrc = document.selection.createRange().text;
            var localImagId = document.getElementById("localImag");
            //必须设置初始大小
            imgObjPreview.style.width = $(window).width()*0.8;
            imgObjPreview.style.height = $(window).height()*0.6;
            //图片异常的捕捉，防止用户修改后缀来伪造图片
            try{
                localImagId.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
            }
            catch(e)
            {
                alert("您上传的图片格式不正确，请重新选择!");
                return false;
            }
            imgObjPreview.style.display = 'none';
            document.selection.empty();
        }
        return true;
    }

    function submits() {
        var imgs = $("#img").val().length;
        if(imgs>=200000){
            alert("图片过大,请裁剪小一点哦!");
            return ;
        }
        $('#form').submit();
    }

</script>