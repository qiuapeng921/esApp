@extends('layouts.main')
@section('content')
    <h4>群组</h4>
    <ul class="list-group">
        @foreach($result as $key=>$item)
            <li class="list-group-item">
                <a href="/groupMessage?type=group&id={{$item['id']}}" target="_blank">
                    <img class="img-circle" src="{{$item['group_hand_url']}}" alt="{{$item['group_hand_url']}}"
                         style="float: left;width: 60px;height: 60px;">
                    <h3>{{$item['group_name']}}</h3>
                </a>
            </li>
        @endforeach
    </ul>
@endsection