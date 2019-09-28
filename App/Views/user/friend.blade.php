@extends('layouts.main')
@section('content')
    <h4>通讯录</h4>
    <ul class="list-group">
        @foreach($result as $key=>$item)
            <li class="list-group-item">
                <a href="/message?type=send&id={{$item['user_id']}}" target="_blank">
                    <img class="img-circle" src="{{$item['image_url']}}" alt="{{$item['image_url']}}"
                         style="float: left;width: 60px;height: 60px;">
                    <h3>{{$item['nick_name']}}</h3>
                </a>
            </li>
        @endforeach
    </ul>
@endsection