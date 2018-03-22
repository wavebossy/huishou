
<div id="menu">
    @if($fottor == "index")
        <div id="menu_span1" onclick="index()">
            <div class="_red"><i class="iconfont icon-shopping" style="font-size: 2.5rem;"></i></div>
            <div class="_red">首页</div>
        </div>
        <div id="menu_span2" onclick="ucenter()">
            <div style=""><i class="iconfont icon-gerenzhongxin" style="font-size: 2.5rem;"></i></div>
            <div style="">个人中心</div>
        </div>
    @else
        <div id="menu_span1" onclick="index()">
            <div class=""><i class="iconfont icon-shopping" style="font-size: 2.5rem;"></i></div>
            <div class="">首页</div>
        </div>
        <div id="menu_span2" onclick="ucenter()">
            <div class="_red"><i class="iconfont icon-gerenzhongxin" style="font-size: 2.5rem;"></i></div>
            <div class="_red">个人中心</div>
        </div>
    @endif
</div>

<script>
    function index() {
        window.location.href="/index";
    }
    function ucenter() {
        window.location.href="/ucenter";
    }
</script>