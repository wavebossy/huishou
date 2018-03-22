<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>收货地址</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/ucenter/core/index.css" rel="stylesheet" >
</head>
<body>

<div id="content">
    <ul>
        @foreach($cores as $core)
            <li>
                <span style="position: absolute;">{{$core->username}}   {{$core->phone}}  </span>
                <span style="position: absolute;right: 2rem;" ><a href="/delcore?id={{$core->id}}">删除</a></span>
                <span style="position: relative;top: 4rem;">{{$core->province}}{{$core->city}}{{$core->area}}{{$core->address}}</span>
            </li>
        @endforeach
    </ul>
</div>
</body>
</html>
