<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>邮币记录</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    {{--<link href="https://at.alicdn.com/t/font_404372_bjgc0xyrqyiltyb9.css" rel="stylesheet" >--}}
    <link href="/css/ucenter/youbilist/index.css" rel="stylesheet" >
    <link href="/css/mescroll.min.css"  rel="stylesheet">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/mescroll.min.js"></script>
</head>
<body>

<div id="content">
    <div id="goodsList" class="mescroll" >
        <ul id="dataList">
            {{--@foreach($youbilists as $youbilist)--}}
                {{--<li>--}}
{{--                    <span style="position: absolute;">{{____youbitype($youbilist->ybtype)}}  {{____youbinumber($youbilist->ybnumber)}}</span>--}}
                    {{--<span style="position: relative;top: 4rem;">{{$youbilist->times}}</span>--}}
                {{--</li>--}}
            {{--@endforeach--}}
        </ul>
    </div>
</div>
</body>
</html>

<script>
    $(function () {
        var mescroll = new MeScroll("goodsList", {
            down: {
                auto: true, //是否在初始化完毕之后自动执行下拉回调callback; 默认true
                callback: downCallback //下拉刷新的回调
            },
            up: {
                auto:false,
                clearEmptyId: "dataList", //1.下拉刷新时会自动先清空此列表,再加入数据; 2.无任何数据时会在此列表自动提示空
                callback: getListData  //上拉回调,此处可简写; 相当于 callback: function (page) { getListData(page); }
            }
        });

        /*联网加载列表数据  page = {num:1, size:10}; num:当前页 从1开始, size:每页数据条数 */
        function getListData(page){
            console.log(page);
            $.ajax({
                type: "get",
                url: "/youbilist",
                data:{
                    page:page.num,
                    pageSize:page.size
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                },
                success:function(res){
                    console.log(res);
                    mescroll.endSuccess(res.data.result.length);
                    setListData(res.data.result);
                    //mescroll.endSuccess( res.data.result.length, res.time );
                },
                error: function (XMLHttpRequest) {
                    mescroll.endErr();
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });

        }

        /*联网加载列表数据  page = {num:1, size:10}; num:当前页 从1开始, size:每页数据条数 */
        function downCallback(page){
            $.ajax({
                type: "get",
                url: "/youbilist",
                data:{
                    page:1,
                    pageSize:10
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                },
                success:function(res){
                    mescroll.endSuccess();
                    console.log(res);
                    var _temp = "";// dataList
                    var data = res.data.result;
                    for (var i = 0; i < data.length; i++) {
                        _temp += "<li>" +
                            "<p>"+____youbitype(data[i].ybtype) + ____youbinumber(data[i].ybnumber)+"</p>" +
                            "<p>"+data[i].times+"</p>" +
                            "</li>";
                    }
                    $("#dataList").html(_temp);
                },
                error: function (XMLHttpRequest) {
                    mescroll.endErr();
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });

        }

        /*设置列表数据*/
        function setListData(data){
            var _temp = "";// dataList
            for (var i = 0; i < data.length; i++) {
                _temp += "<li>" +
                    "<p>"+____youbitype(data[i].ybtype) + ____youbinumber(data[i].ybnumber)+"</p>" +
                    "<p>"+data[i].times+"</p>" +
                    "</li>";
            }
            $("#dataList").append(_temp);
        }
        //禁止PC浏览器拖拽图片,避免与下拉刷新冲突;如果仅在移动端使用,可删除此代码
        document.ondragstart=function() {return false;}

        function ____youbitype(data) {
            switch (data){
                case 1 : return "每日签到"; break;
                case 2 : return "系统赠送"; break;
                case 3 : return "商品赠送"; break;
                case 4 : return "商品抵扣"; break; // 消费
                case 5 : return "邀请赠送"; break; // 消费
                default : return "请增加类型";
            }
        }

        function ____youbinumber(data) {
            if(parseInt(data)>0){
                return "+" + data;
            }else{
                return data;
            }
        }

    });
</script>