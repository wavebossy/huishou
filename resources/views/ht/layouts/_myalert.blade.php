@section('_layout_style')
    <style>
        #myAlert{
            position: fixed;
            z-index: 9999;
            left: 50%;
            top: 40%;
        }
    </style>
@endsection

@if(session("success_info"))
    <div id="myAlert" class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>提示！</strong> 操作成功!
    </div>
@endif

@if(session("error_info"))
    <div id="myAlert" class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>提示！</strong> 操作失败!
    </div>
@endif

@section('_layout_script')
    <script>
        setTimeout(function () {
            $("#myAlert").fadeOut("slow");
        },3000);
    </script>
@endsection