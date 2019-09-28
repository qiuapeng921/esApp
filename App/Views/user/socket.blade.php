@extends('layouts.main')
@section('content')
    <div class="jumbotron">
        <div class="container">
            <label>
                <input type="hidden" id="userId" value="{{$id}}">
            </label>

            <ul id="message" style="font-size: 14px;">

            </ul>
            <hr>
        </div>
    </div>

    <div class="content">
        <div class="form-group" style="float: left;">
            <span>在线用户</span>
            <ul class="list-group" id="online_user">

            </ul>
        </div>
        <div class="form-group" style="margin-left: 300px;">
            <label for="name"></label>
            <label>消息框
                <textarea class="form-control" id="says" rows="6" style="width: 836px"></textarea>
                <button type="button" class="btn btn-success" style="margin-top: 20px;" onclick="say()">发送</button>
            </label>
        </div>
    </div>
@endsection