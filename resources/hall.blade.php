@extends('layouts.main')
@section('content')
    <div class="col-xs-12 col-sm-9">
        <div class="jumbotron" id="message" style="height: 500px;overflow: auto">

        </div>
        <div class="row" style="width: 75%">
            <div class="col-xs-6 col-lg-4">
                <label>
                    <textarea class="form-control input-lg" style="width: 848px;" rows="3" id="content"></textarea>
                </label>
                <button type="button" class="btn btn-success" style="margin-left: 784px"
                        onclick="sendMessage('sendAll')">发送
                </button>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
        <a class="list-group-item active">在线用户</a>
        <div class="list-group" style="height: 450px;overflow: auto;" id="onlineUsers">
           
        </div>
    </div>
@endsection