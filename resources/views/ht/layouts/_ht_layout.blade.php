<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="" name="description" />
    <meta content="webthemez" name="author" />
    <title>网站名称</title>
    <!-- Bootstrap Styles-->
    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="/css/ht/font-awesome.css" rel="stylesheet" />
    <link href="/css/ht/custom-styles.css" rel="stylesheet" />
    <link href="/css/ht/checkbox3.min.css" rel="stylesheet" >
    <!-- Jquery -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="/js/jquery.metisMenu.js"></script>
    <!-- Custom Js -->
    <script src="/js/custom-scripts.js"></script>
    @yield('_layout_style')
    @yield('style')
</head>

<body>
<div id="wrapper">
    @include("ht.layouts._nav_row")
    <!--/. NAV TOP  -->
    @include("ht.layouts._nav_line")
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div class="header">
            @include("ht.layouts._data_header")
        </div>
        <div id="page-inner">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
{{--模板里面的脚本--}}
@yield('_layout_script')
@yield('_layout_pagination')
@yield('script')