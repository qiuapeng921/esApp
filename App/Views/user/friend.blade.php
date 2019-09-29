@extends('layouts.main')
@section('content')
    <span style="float: left">通讯录</span>
    <a href="/addFriend" class="btn btn-success" style="margin-left: 1040px;" target="_blank">添加好友</a>
    <ul class="list-group" style="margin-top: 50px;">
        @foreach($result as $key=>$item)
            <li class="list-group-item">
                <a href="/message?type=send&id={{$item['user_id']}}" target="_blank">
                    <img class="img-circle" src="{{$item['image_url']}}" alt="{{$item['image_url']}}"
                         style="float: left;width: 60px;height: 60px;">
                    <h3>{{$item['nick_name']}}</h3>
                </a>
                <button type="button" style="float: right;margin-top: -50px;" class="btn btn-danger"
                        onclick="deleteFriends({{$item['user_id']}})">删除
                </button>
            </li>
        @endforeach
    </ul>
@endsection
@section('script')
    <script type="application/javascript">
        function deleteFriends(userId) {
            layer.msg('你确定要删除吗？', {
                time: 0 //不自动关闭
                , btn: ['是', '否']
                , yes: function (index) {
                    $.ajax({
                        type: "post",
                        url: "/api/user/delFriend",
                        dataType: "json",
                        data: {userId: userId},
                        success: function (result) {
                            console.log(result);
                            if (result.code == 100) {
                                layer.msg(result.message, {icon: 2});
                                return false;
                            } else {
                                layer.close(index);
                                layer.msg('删除成功', {icon: 6}, function () {
                                    window.location.reload();
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection