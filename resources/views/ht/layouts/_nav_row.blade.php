{{--横着的菜单--}}

<nav class="navbar navbar-default top-navbar" role="navigation">
    {{--网站标题部分--}}
    <div class="navbar-header">
        <!--
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar">1</span>
            <span class="icon-bar">2</span>
            <span class="icon-bar"></span>
        </button>
        -->
        <a class="navbar-brand" href="#"><strong><i class="icon fa fa-plane"></i> 回收系统 </strong></a>
        <div id="sideNav">
            <i class="fa fa-bars icon"></i>
        </div>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-messages">
                <li>
                    <a href="#">
                        <div>
                            <strong>张三客户</strong>
                            <span class="pull-right text-muted">
                                        <em>今天</em>
                                    </span>
                        </div>
                        <div>购买了一个xxx 下单时间 2017-9-27 15:24:34</div>
                    </a>
                </li>
                <li class="divider"></li>
                {{--后面复制上面一层 , 2 个 li --}}
                <li>
                    <a class="text-center" href="#">
                        <strong>查看更多</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
            <!-- /.dropdown-messages -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fa fa-tasks fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-tasks">
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>下单量/总量</strong>
                                <span class="pull-right text-muted">60%</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                    <span class="sr-only">60%</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <p>
                                <strong>取消单量/总量</strong>
                                <span class="pull-right text-muted">40%</span>
                            </p>
                            <div class="progress progress-striped active">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                    <span class="sr-only">40%</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>查看更多订单</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
            <!-- /.dropdown-tasks -->
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#"><i class="fa fa-user fa-fw"></i> 当前账户:admin</a></li>
                <li><a href="#"><i class="fa fa-gear fa-fw"></i> 账户设置</a></li>
                <li class="divider"></li>
                <li><a href="/{{htname}}/loginout"><i class="fa fa-sign-out fa-fw"></i> 退出登入</a></li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
</nav>