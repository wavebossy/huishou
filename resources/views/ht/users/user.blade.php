@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    用户列表<br>
                    {{--是否关注字段（是：当前已经关注；否，取消关注；非，不是渠道注册）<br>--}}
                </caption>
                <tr>
                    <td>用户ID</td>
                    <td>用户姓名</td>
                    {{--<td>手机号</td>--}}
                    <td>头像</td>
                    <td>剩余积分</td>
                    <td>性别</td>
                    {{--<td>是否关注</td>--}}
                    {{--<td>上级邀请码</td>--}}
                    {{--<td>邀请码</td>--}}
                    <td>操作</td>
                </tr>
                @if(!empty($users))
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{base64_decode($user->user_name)}}</td>
                            {{--                        <td>{{$user->phone}}</td>--}}
                            <td><img src="{{$user->user_imgs}}" style="width: 60px"></td>
                            <td>{{$user->score}}</td>
                            <td>{{$user->user_sex==1?"男":$user->user_sex==2?"女":"未设置"}}</td>
                            {{--<td>{{$user->isfollow==1?"是":($user->isfollow==2?"否":"非")}}</td>--}}
                            {{--<td>{{$user->superinviter}}</td>--}}
                            {{--<td>{{$user->inviter}}</td>--}}
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
            setPaging(parseInt({{$last}}),"userlist","?");
        });
    </script>
@endsection
