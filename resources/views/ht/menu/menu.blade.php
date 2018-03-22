@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    菜单管理<br/>
                    系统注:系统路径列，若有展开菜单，务必置空
                </caption>
                <tr>
                    <td>id</td>
                    <td>菜单名字</td>
                    <td>描述</td>
                    <td>路径</td>
                    <td>系统路径</td>
                    <td>图标</td>
                    <td>上级菜单ID</td>
                    <td>是否显示</td>
                    <td>操作</td>
                </tr>
                @foreach($menu as $m)
                    <tr>
                        <td>{{$m->id}}</td>
                        <td>{{$m->menuname}}</td>
                        <td>{{$m->smalltext}}</td>
                        <td>{{$m->path}}</td>
                        <td>{{$m->breadcrumb}}</td>
                        <td title="{{$m->icon}}">{!! $m->icon !!}</td>
                        <td>{{$m->parentid}}</td>
                        <td>{{$m->isshow==1?"是":"否"}}</td>
                        <td><button class="btn btn-primary btn-md" onclick="menuUpdate({{$m->id}})" >修改</button></td>
                    </tr>
                @endforeach
            </table>

        </div>
    </div>

    {{--<a class="btn btn-primary btn-md" data-toggle="modal" data-target="#myModal">修改</a>--}}
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="/{{htname}}/menuSave" method="post">
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
                        <input type="hidden" name="id" class="form-control" >
                        <div class="form-group">
                            <label for="name">菜单名字</label>
                            <input type="text" name="menuname" class="form-control" placeholder="菜单名字" />
                        </div>
                        <div class="form-group">
                            <label for="name">描述</label>
                            <input type="text" name="smalltext" class="form-control" placeholder="描述" />
                        </div>
                        <div class="form-group">
                            <label for="name">路径</label>
                            <input type="text" name="path" class="form-control" placeholder="路径" />
                        </div>
                        <div class="form-group">
                            <label for="name">系统路径</label>
                            <input type="text" name="breadcrumb" class="form-control" placeholder="系统路径" />
                        </div>
                        <div class="form-group">
                            <label for="name">图标代码</label>
                            <input type="text" name="icon" class="form-control" placeholder="图标代码" />
                        </div>
                        <div class="form-group">
                            <label for="name">上级菜单ID</label>
                            <input type="text" name="parentid" class="form-control" placeholder="上级菜单ID" />
                        </div>
                        <div class="form-group">
                            <label for="name">是否显示(1显示 0隐藏)</label>
                            <input type="text" name="isshow" class="form-control" placeholder="是否显示(1显示 0隐藏)" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                        </button>
                        <input type="submit" class="btn btn-success" value="更改"/>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </form>
    </div>

@endsection


@section('script')
    <script>
        function menuUpdate(e) {
            $.ajax({
                type: "post",
                url: "/{{htname}}/menuUpdate",
                data:{
                    id:e,
                    _token:"{{csrf_token()}}"
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){},
                success:function(res){
                    var result = res.data.result["0"];// .breadcrumb
                    console.log(result);
                    $("input[name='breadcrumb']").val(result.breadcrumb);
                    $("input[name='icon']").val(result.icon);
                    $("input[name='id']").val(result.id);
                    $("input[name='isshow']").val(result.isshow);
                    $("input[name='menuname']").val(result.menuname);
                    $("input[name='parentid']").val(result.parentid);
                    $("input[name='path']").val(result.path);
                    $("input[name='smalltext']").val(result.smalltext);
                    $("#myModal").modal("show");
                },
                error: function (XMLHttpRequest) {
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }
    </script>
@endsection
