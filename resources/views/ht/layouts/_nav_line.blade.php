{{--竖着的菜单--}}
<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">

            @foreach(session("menu") as $k=>$v)
                {{--至少都有上级和当前位置--}}
                {{--只有一级菜单--}}
                @if($v->parentid==0 && !empty($v->breadcrumb))
                    @if(session("menuName") == $v->menuname)
                        <li>
                            <a class="active-menu" href="/{{htname}}{{$v->path}}">@php echo $v->icon; @endphp {{$v->menuname}} </a>
                        </li>
                    @else
                        <li>
                            <a  href="/{{htname}}{{$v->path}}">@php echo $v->icon; @endphp {{$v->menuname}} </a>
                        </li>
                    @endif
                @else
                    @if($v->parentid==0 && empty($v->breadcrumb))
                        <li class="active">
                            <a href="#"><i class="fa fa-sitemap"></i> {{$v->menuname}} <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                @foreach(session("menu") as $kparent=>$vparent)
                                    @if($v->id == $vparent->parentid)
                                        @foreach(json_decode($vparent->breadcrumb) as $kz=>$vz)
                                            @if($kz!=0 && $vz->text!=$v->menuname)
                                                @if(session("menuName") == $vparent->menuname)
                                                    <li>
                                                        <a class="active-menu"  href="{{$vz->href}}"> {{$vz->text}} </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="{{$vz->href}}"> {{$vz->text}} </a>
                                                    </li>
                                                @endif

                                                @continue;
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endif
            @endforeach
            {{--<li>--}}
                {{--<a class="active-menu" href="/{{htname}}/homepage"><i class="fa fa-dashboard"></i> 系统配置 </a>--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--<a href="#"><i class="fa fa-sitemap"></i> 二级菜单 <span class="fa arrow"></span></a>--}}
                {{--<ul class="nav nav-second-level">--}}
                    {{--<li>--}}
                        {{--<a href="#"> 二级子一菜单 </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#"> 二级子二菜单 </a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            {{--<li>--}}
                {{--<a href="/{{htname}}/sys" ><i class="fa fa-qrcode"></i> 系统管理 </a>--}}
            {{--</li>--}}

            {{--<li>--}}
                {{--<a href="/{{htname}}/talkadd"><i class="fa fa-table"></i> 订单管理 </a>--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--<a href="/{{htname}}/commodity" ><i class="fa fa-edit"></i> 商品管理 </a>--}}
            {{--</li>--}}

            {{--<li>--}}
                {{--<a href="#"><i class="fa fa-sitemap"></i> 三级菜单 <span class="fa arrow"></span></a>--}}
                {{--<ul class="nav nav-second-level">--}}
                    {{--<li>--}}
                        {{--<a href="#"> 三级子二 </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#"> 三级子二 </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#"> 三级子二菜单 <span class="fa arrow"></span></a>--}}
                        {{--<ul class="nav nav-third-level">--}}
                            {{--<li>--}}
                                {{--<a href="#"> 三级子二子一 </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#"> 三级子二子二 </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#"> 三级子二子三 </a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
        </ul>
    </div>
</nav>