@extends('layouts.app')

@section('title', 'test')

@section('sidebar')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    @php
            var_dump($a);
            @endphp
@endsection