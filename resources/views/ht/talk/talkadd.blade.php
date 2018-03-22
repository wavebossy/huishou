@extends('ht.layouts._ht_layout')
@section('style')
    <style>
        .dingdan__01{  color: gray;  }
        .dingdan__02{  color: #00e200;  }
        .dingdan__03{  color: gray;  }
        .dingdan__05{  color: blue;  }
        .dingdan__00{  color: #ffca00; }
    </style>
@endsection
@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    订单列表
                    <a href="/{{htname}}/etld" target="_blank">数据导出</a>
                </caption>
                <tr>
                    <td>订单ID</td>
                    <td>姓名</td>
                    <td>电话</td>
                    <td>预约类型</td>
                    <td>地址/省/市/区</td>
                    <td>备注(有话说)</td>
                    <td>支付金额</td>
                    <td>订单状态</td>
                    <td>预约时间</td>
                    <td>完成时间</td>
                    <td>下单时间</td>
                    <td>操作</td>
                </tr>
                @foreach($commoditys as $commodity)
                    <tr>
                        <td>{{$commodity->id}}</td>
                        <td>{{base64_decode($commodity->user->user_name)}}</td>
                        <td>{{$commodity->core->phone}}</td>
                        <td>{{$commodity->type->type_name}}</td>
                        <td>{{$commodity->core->province}}/{{$commodity->core->city}}/{{$commodity->core->area}}/{{$commodity->core->address}}</td>
                        <td>{{$commodity->remark}}</td>
                        <td>{{$commodity->summoney}}</td>
                        <td class="{{($commodity->status==1?"dingdan__01":($commodity->status==2?"dingdan__02":($commodity->status==3?"dingdan__03":($commodity->status==5?"dingdan__05":"dingdan__00"))))}}">
                            {{($commodity->status==1?"订单待完成":($commodity->status==2?"订单完成":($commodity->status==3?"订单取消":($commodity->status==5?"订单删除":"订单异常"))))}}</td>
                        <td>{{$commodity->day_time}}</td>
                        <td>{{$commodity->toptimes}}</td>
                        <td>{{$commodity->times}}</td>
                        <td><button class="btn btn-primary btn-md" onclick="talkDetail({{$commodity->id}})">操作</button></td>
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
        <form action="/{{htname}}/talkDelivery" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <input type="hidden" name="page" value="{{$page}}" />
            <input type="hidden" name="pageSize" value="{{$pageSize}}" />
            <input type="hidden" name="talkid" id="talkid" class="form-control" placeholder="订单编号" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            订单详情
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <table class="table">
                                <caption>用户信息</caption>
                                <tr>
                                    <td>姓名</td>
                                    <td>手机号</td>
                                    <td>省</td>
                                    <td>市</td>
                                    <td>区</td>
                                    <td>详细地址</td>
                                </tr>
                                <tr>
                                    <td id="corerealname"></td>
                                    <td id="corephone"></td>
                                    <td id="coreprovince"></td>
                                    <td id="corecity"></td>
                                    <td id="corearea"></td>
                                    <td id="coreaddress"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group">
                            <table class="table">
                                <caption>订单信息</caption>
                                <tr>
                                    <td>类别</td>
                                    <td>预约时间</td>
                                    <td>下单时间</td>
                                    <td>完成时间</td>
                                    <td>订单状态</td>
                                    <td>有话说</td>
                                </tr>
                                <tr>
                                    <td id="type_name"></td>
                                    <td id="day_time"></td>
                                    <td id="times"></td>
                                    <td id="toptimes"></td>
                                    <td id="status"></td>
                                    <td id="remark"></td>
                                </tr>
                            </table>
                        </div>
                        <hr/>

                        <div class="form-group">
                            <div><h5>订单状态</h5></div>
                            <select class="form-control" name="status">
                                <option value="1">订单待完成</option>
                                <option value="2">订单完成</option>
                                <option value="3">订单取消</option>
                                <option value="5">订单删除</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div> <h5>结算金额</h5></div>
                            <input type="text" name="summoney" class="form-control" placeholder="管理员请自行输入最终成交金额" />
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

@include("ht.layouts._pagination")
@section('script')
    <script>
        $(function () {
            // 加载分页
            setPaging(parseInt({{$last}}),"talkadd","?");

        });

        function talkDetail(e) {
            $.ajax({
                type: "post",
                url: "/{{htname}}/talkDetail",
                data:{
                    id:e,
                    _token:"{{csrf_token()}}"
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){},
                success:function(res){
                    console.log(res);
                    var talkadd = res.data.result.talkadd;
                    var types = res.data.result.types;
                    var core = res.data.result.core;

                    $("#type_name").html(types.type_name);
                    $("#talkid").val(talkadd.id);// .data.result.talkadd.id

                    $("#day_time").html(talkadd.day_time);
                    $("#times").html(talkadd.times);
                    $("#toptimes").html(talkadd.toptimes);

                    switch (talkadd.status){
                        case 1 : $("#status").html("订单待完成");break;
                        case 2 : $("#status").html("订单完成");break;
                        case 3 : $("#status").html("订单取消");break;
                        case 5 : $("#status").html("订单删除");break;
                        default : $("#status").html("订单异常");break;
                    }

                    $("select[name='status']").val(talkadd.status);

                    $("#remark").html(talkadd.remark);

                    $("#corerealname").html(core.username);
                    $("#corephone").html(core.phone);
                    $("#coreprovince").html(core.province);
                    $("#corecity").html(core.city);
                    $("#corearea").html(core.area);
                    $("#coreaddress").html(core.address);

                    $("#myModal").modal("show");
                },
                error: function (XMLHttpRequest) {
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }

        function hideCourier(e) {
            if(e==0){
                $(".hideCourier").hide();
            }else{
                $(".hideCourier").show();
            }
        }
    </script>
@endsection
