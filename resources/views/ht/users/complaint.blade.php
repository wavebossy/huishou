@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    用户投诉意见<br>
                    {{--是否关注字段（是：当前已经关注；否，取消关注；非，不是渠道注册）<br>--}}
                </caption>
                <tr>
                    <td>投诉类型</td>
                    <td>手机号</td>
                    <td>投诉内容</td>
                    <td>图片</td>
                    <td>投诉时间</td>
                    <td>处理状态</td>
                    <td>操作</td>
                </tr>
                @if(!empty($complaints))
                    @foreach($complaints as $complaint)
                        <tr>
                            <td>{{$complaint->titles}}</td>
                            <td>{{$complaint->phone}}</td>
                            <td>{{$complaint->context}}</td>
                            <td><img src="{{$complaint->imgs}}" style="width: 60px"></td>
                            <td>{{$complaint->times}}</td>
                            <td>{{$complaint->status==1?"已处理":"未处理"}}</td>
                            <td><button class="btn btn-primary btn-md" >操作</button></td>
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

@endsection

@include("ht.layouts._pagination")
@section('script')
    <script>
        $(function () {
            setPaging(parseInt({{$last}}),"complaint","?");
        });
    </script>
@endsection
