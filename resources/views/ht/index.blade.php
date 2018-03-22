<!Doctype html>
<html>
    <head>
        <title>后台登入</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- Style -->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/ht/index.css" type="text/css" media="all">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
    </head>
    <body>
    <h1>欢迎登入</h1>
    @if (session('errorinfo'))
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert">
                &times;
            </a>
            <strong>你好！</strong>{{ session('errorinfo')}}
        </div>
    @endif
    <div class="container w3layouts agileits">
        <form action="/{{htname}}/login" method="post">
        <div class="login w3layouts agileits">
            <h2>登 录</h2>
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <input type="text" name="usname" placeholder="账号" value="" required='required' oninvalid="setCustomValidity('请输入账号')" oninput="setCustomValidity('')">
            <input type="password" name="uspwd" placeholder="密码" value="" required='required' oninvalid="setCustomValidity('请输入密码')" oninput="setCustomValidity('')">
            <ul class="tick w3layouts agileits">
                <li>
                    <input type="checkbox" id="brand1" value="">
                    <label for="brand1"><span></span>记住我</label>
                </li>
            </ul>
            <div class="send-button w3layouts agileits">
                <input type="submit" value="登 录">
            </div>
            <a href="#" onclick="alert('请联系管理员')">忘记密码?</a>

            <div class="clear"></div>
        </div>
        </form>
    </div>
    <div class="footer w3layouts agileits">
        <p>Copyright &copy; More Templates <a href="#" target="_blank" title="{{$webname}}">{{$webname}}</a> - Collect from </p>
    </div>
    </body>
</html>