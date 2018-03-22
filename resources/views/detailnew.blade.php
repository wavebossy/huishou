<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>商品 - {{$commodityDetail->name}}</title>
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
        #imgDiv{
            position: relative;width: 100%
        }
        #remark{
            padding: 2rem 1rem 2rem 1rem;font-size: 1.8rem;
        }
        #__line{
            padding: 0 1rem 0 0.5rem;font-size: 1.6rem;
        }
        #kc{padding: 0 1rem 2rem 1rem;font-size: 1.8rem;}
        .font14{font-size: 1.4rem;}
        .font18{font-size: 1.8rem;}
        .red{  color:rgba(255, 100, 0,1);  }
        .tuiguang{padding: 1rem 1rem 2rem 1rem;font-size: 1.6rem;height: 5rem;display: none}
        .zb{color:rgba(255, 100, 0,1);position: absolute;right: 2rem;}
        #imgUrl{width: 100%;  }
        #paymentDiv{position: fixed;font-size: 1.8rem;color: white;text-align: center;background: rgb(255, 100, 0);width: 100%;height: 5rem;line-height: 5rem;bottom: 0;max-width: 640px;}
    </style>
</head>
<body>

<div id="content">
    <div id="imgDiv" style=""><img id="imgUrl" src="{{$commodityDetail->img}}"></div>
    <div id="remark" style="">{{$commodityDetail->remark}}</div>
    <div id="kc">剩余库存：123</div>
    <div id="__line">
        <span class="red font18">{{$commodityDetail->price}}</span>
        <s>{{$commodityDetail->oprice}}</s>
        <span class="font14">物流+打包+人工费=<span class="font18">{{$commodityDetail->freight}}元</span></span>
    </div>
    <div class="tuiguang">
        <span>10邮币可抵扣1元邮费</span>
        <span onclick="" class="zb">我要赚邮币</span>
    </div>
    <div style="height: 5rem;"></div>
    <div onclick="payment()" id="paymentDiv">支付费用领取</div>
</div>
</body>
</html>
<script>
//    $("#imgUrl").width($(window).width()).height($(window).width());
    function payment() {
        window.location.href="/payment?id={{$commodityDetail->id}}";
    }
</script>

