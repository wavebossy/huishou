@extends('ht.layouts._ht_layout')

@section('style')
    <style>
        img{
            width: 4rem;
        }
    </style>
@endsection

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <table class="table table-bordered table-hover table-striped">
                <caption>系统配置</caption>
                <tr>
                    <td>key （描述作用）</td>
                    <td>value （程序内真实使用的数据） </td>
                    <td>调整</td>
                </tr>
                @foreach($configs as $config)
                    <tr>
                        <td>{{$config->k}}</td>
                        @if($config->id == 6)
                            <td><img src="{{$config->v}}" title="门面图" style="width: 80px;" /></td>
                            @else
                            <td>{{$config->v}}</td>
                        @endif
                        <td>
                            <button class="btn btn-primary btn-md" onclick="configUpdate({{$config->id}})" >修改</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <table class="table table-bordered table-hover table-striped">
                <caption>回收列表</caption>
                <tr>
                    <td>ID</td>
                    <td>路径名称</td>
                    <td>url 页面</td>
                    <td>操作</td>
                </tr>
                @foreach($orderPage as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->url_name}}</td>
                        <td>{{$item->url_page}}</td>
                        <td>
                            <button class="btn btn-primary btn-md" onclick="orderPageUpdate({{$item->id}})" >修改</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <ul id="foot_ul" class="pagination" style="margin-bottom: 0;"></ul>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="/{{htname}}/saveUrlPage" method="post" >
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            修改菜单
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" >
                        <div class="form-group">
                            <label for="name">路径名称</label>
                            <input type="text" name="url_name" class="form-control" placeholder="路径名称" />
                        </div>
                        <div class="form-group">
                            <label for="name">url 页面</label>
                            <input type="text" name="url_page" class="form-control" placeholder="url 页面" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <input type="submit" class="btn btn-success" value="更改"/>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </form>
    </div>

    <div class="modal fade" id="configUpdateModal" tabindex="-1" role="dialog" aria-labelledby="configUpdateModalLabel" aria-hidden="true">
        <form action="/{{htname}}/saveConfig" method="post" >
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="configUpdateModalLabel">
                            系统配置
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="config_id" >
                        {{--<div class="form-group">--}}
                            {{--<label for="name">key </label>--}}
                            {{--<input type="text" name="url_name" class="form-control" placeholder="key" />--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <label for="name">value </label>
                            <input type="text" name="v" class="form-control" placeholder="输入对应配置的值（比如一天分享3次有积分，则输入3）" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <input type="submit" class="btn btn-success" value="确认"/>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </form>
    </div>

    <div class="modal fade" id="configUpdateModal_mmt" tabindex="-1" role="dialog" aria-labelledby="configUpdateModalLabel_mmt" aria-hidden="true">
        <form action="/{{htname}}/saveConfig" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="configUpdateModalLabel_mmt">
                            系统配置
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="config_id" >
                        {{--<div class="form-group">--}}
                            {{--<label for="name">key </label>--}}
                            {{--<input type="text" name="url_name" class="form-control" placeholder="key" />--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <label for="name">选择文件，替换门面图 </label>
                            <input type="file" name="mm_logo" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <input type="submit" class="btn btn-success" value="确认"/>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </form>
    </div>

@endsection

@section('script')
    <script>
        function orderPageUpdate(e) {

            $("input[name='id']").val(e);
            $("#myModal").modal("show");

        }
        function configUpdate(e) {
            if(e!=6){
                $("input[name='config_id']").val(e);
                $("#configUpdateModal").modal("show");
            }else{
                // 门面图
                $("input[name='config_id']").val(e);
                $("#configUpdateModal_mmt").modal("show");
            }

        }
    </script>
@endsection
