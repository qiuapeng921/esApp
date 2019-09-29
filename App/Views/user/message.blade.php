@extends('layouts.main')
@section('title')
    与{{$result['nick_name']}}聊天中
@endsection
@section('content')
    <div class="col-xs-12 col-sm-9">
        <div class="jumbotron" style="height: 500px;overflow: auto">
            <p>This is an example to show the potential of an offcanvas layout pattern in Bootstrap</p>
        </div>
        <div class="row" style="width: 75%">
            <div class="col-xs-6 col-lg-4">
                <label>
                    <textarea class="form-control input-lg" style="width: 500px;" rows="3" id="content"></textarea>
                </label>
                <button type="button" class="btn btn-success media-right" onclick="sendMessage('send')">发送</button>
            </div>
        </div>
    </div>
@endsection