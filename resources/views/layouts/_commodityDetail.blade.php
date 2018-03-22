<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>商品 - @yield('gtitle')</title>
    <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-width: 320px;
            background: white;
            color: #333;
            font: 1.4rem/1.5 "STHeitiSC-Light","Microsoft YaHei",Helvitica,Verdana,Tohoma,Arial,san-serif;
            margin: 0;
            padding: 0;
            border: 0;
        }
        #content{  max-width: 640px;  }
    </style>
    @yield('style')
</head>
<body>

<div id="content">
    @yield('content')
</div>
</body>
</html>
@section('script')
    {{--script list--}}
@show
