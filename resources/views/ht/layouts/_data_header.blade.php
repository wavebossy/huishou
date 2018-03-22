<h1 class="page-header">
@if(session("menuName"))
    {{session("menuName")}}
@endif
    <small>
        @if(session("smallText"))
            {{session("smallText")}}
        @endif
    </small>
</h1>
<ol class="breadcrumb">
{{--<li><a href="#">Home</a></li>--}}
{{--<li><a href="#">Dashboard</a></li>--}}
{{--<li class="active">Data</li>--}}
    {{--@for($b=0;$b<sizeof($breadcrumb);$b++)--}}
        {{--@if($b==(sizeof($breadcrumb)-1))--}}
            {{--<li><a href="{{$breadcrumb[$b]->href}}">{{$breadcrumb[$b]->text}}</a></li>--}}
        {{--@else--}}
            {{--<li class="active">{{$breadcrumb[$b]->text}}</li>--}}
        {{--@endif--}}
    {{--@endfor--}}
    {{--@php dd(session("breadcrumb")); @endphp--}}
    @foreach(session("breadcrumb") as $k=>$v)
        @if($k!=(sizeof(session("breadcrumb"))-1))
            <li><a href="{{$v->href}}">{{$v->text}}</a></li>
        @else
            <li class="active">{{$v->text}}</li>
        @endif
    @endforeach



</ol>