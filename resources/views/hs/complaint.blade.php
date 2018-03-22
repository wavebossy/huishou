<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>投诉建议</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/ucenter/complaint/index.css" rel="stylesheet" >
    <script src="/js/jquery.min.js"></script>
</head>
<body>

<div id="content">
    <form role="form" id="form" style="padding: 1rem">
        <div class="form-group">
            <label for="name">投诉类型</label>
            <select class="form-control" name="titles">
                <option value="投诉">投诉</option>
                <option value="建议">建议</option>
            </select>
        </div>
        <div class="form-group">
            <textarea name="context" class="form-control" id="address" style="top: 2rem;resize : none;height: 8rem" placeholder="您可以用更多的文字来描述这个事情"></textarea>
        </div>
        <div class="form-group">
            <label for="name">留下联系方式</label>
            <input type="text" class="form-control" name="phone" placeholder="联系方式">
        </div>
        <div class="form-group">
            <label for="files">可选上传图片</label><br/>
            <img style="width: 6rem;" src="https://protal.szsldy.com/butoumingupload.png"  onclick="$('#files').click()" />
            <input type="file" id="files" name="imgfile" style="display: none">
        </div>
    </form>
    <div class="yuyue_but" onclick="submit__()" >提交</div>
</div>

@include("layouts._loading")
</body>
</html>


<script>
    function submit__() {
        var formData = new FormData($("#form")[0]);
        formData.append("_token","{{csrf_token()}}");
        $.ajax({
            type: "post",
            url: "/complaintData",
            data:formData,
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
                if(res.code == 200){
                    alert("提交成功，我们会尽快联系您");
                }
                window.location.href="/ucenter" ;  // 首页，或者个人中心
            },
            error: function (XMLHttpRequest) {
                console.log("ajax error: \n" + XMLHttpRequest.responseText);
                alert("网络错误，请您重新进入");
            }
        });
    }

</script>