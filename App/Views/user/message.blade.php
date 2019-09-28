@extends('layouts.main')
@section('content')
    <div class="jumbotron" style="height: 500px;overflow:auto;">
        <div class="container">
            <ul id="message" style="font-size: 14px;">

            </ul>
            <div class="media">
                <div class="media-left">
                    <img src="https://static.runoob.com/images/mix/img_avatar.png" class="media-object"
                         style="width:40px" alt="">
                </div>
                <div class="media-body">
                    <h6 class="media-heading">admin</h6>
                    <p>测试</p>
                </div>
            </div>
            <hr>
        </div>
    </div>

    <div class="content">
        <div class="form-group">
            <label for="name"></label>
            <label>消息框
                <textarea class="form-control" id="says" rows="6" style="width: 1140px"></textarea>
                <button type="button" class="btn btn-success" style="margin-top: 20px;" onclick="say()">发送</button>
            </label>
        </div>
    </div>
@endsection