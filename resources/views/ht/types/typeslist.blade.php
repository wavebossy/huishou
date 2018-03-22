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
                <caption>回收列表</caption>
                <tr>
                    <td>ID</td>
                    <td>回收子类名称</td>
                    <td>图片</td>
                    <td>回收价格</td>
                    <td>回收单位</td>
                    <td>备注</td>
                    <td>排序</td>
                    <td>操作</td>
                </tr>
                @if(!empty($types))
                    @foreach($types as $type)
                        <tr>
                            <td>{{$type->id}}</td>
                            <td>{{$type->type_name}}</td>
                            <td><img src="{{$type->imgs}}"></td>
                            <td>{{$type->prices}}</td>
                            <td>{{$type->units}}</td>
                            <td>{{$type->remark}}</td>
                            <td>{{$type->sub_sort}}</td>
                            <td>
                                <button class="btn btn-primary btn-md" onclick="commodityDetail({{$type->id}})" >修改</button>
                                <button class="btn btn-danger btn-md" onclick="delDetail({{$type->id}})" >删除</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>无</td>
                        <td>无</td>
                        <td>无</td>
                        <td>无</td>
                        <td>无</td>
                        <td>无</td>
                        <td>无</td>
                        <td>无</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <ul id="foot_ul" class="pagination" style="margin-bottom: 0;"></ul>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="/{{htname}}/typesUpdate" method="post" enctype="multipart/form-data">
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
                            <label for="name">回收子类名称</label>
                            <input type="text" name="type_name" class="form-control" placeholder="回收子类名称" />
                        </div>
                        <div class="form-group">
                            <label for="name">回收价格</label>
                            <input type="text" name="prices" class="form-control" placeholder="回收价格" />
                        </div>
                        <div class="form-group">
                            <label for="name">商品图片</label>
                            <input type="file" name="imgfile" class="form-control"  />
                        </div>
                        <div class="form-group">
                            <label for="name">回收单位</label>
                            <input type="text" name="units" class="form-control" placeholder="回收单位" />
                        </div>
                        <div class="form-group">
                            <label for="name">备注</label>
                            <input type="text" name="remark" class="form-control" placeholder="备注" />
                        </div>
                        <div class="form-group">
                            <label for="name">排序</label>
                            <input type="text" name="sub_sort" class="form-control" placeholder="排序" />
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

@include("ht.layouts._pagination")
@section('script')
    <script>
        $(function () {
            setPaging(parseInt({{$last}}),"typeslist","?");
        });
    </script>
    <script>
        function delDetail(e) {
            window.location.href="/{{htname}}/typesDel?id="+e;
        }
        function commodityDetail(e) {
            $.ajax({
                type: "post",
                url: "/{{htname}}/typesDetail",
                data:{
                    id:e,
                    _token:"{{csrf_token()}}"
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){},
                success:function(res){
                    var result = res.data.result;// .breadcrumb
                    console.log(result);
                    $("input[name='id']").val(result.id);
                    $("input[name='prices']").val(result.prices);
                    $("input[name='remark']").val(result.remark);
                    $("input[name='sub_sort']").val(result.sub_sort);
                    $("input[name='type_name']").val(result.type_name);
                    $("input[name='types_id']").val(result.types_id);
                    $("input[name='units']").val(result.units);
                    $("#myModal").modal("show");
                },
                error: function (XMLHttpRequest) {
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }
    </script>
@endsection
