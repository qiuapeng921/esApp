@extends('layouts.main')
@section('content')
    <h3>添加好友</h3>
    <form role="form" style="margin-top: 50px;">
        <div class="form-group">
            <label for="name">账号</label>
            <input type="text" class="form-control" id="account" placeholder="请输入还有账号">
        </div>
        <button type="button" class="btn btn-default" onclick="searchFriends()">搜索</button>
    </form>
    <ul class="list-group" style="margin-top: 50px;" id="list-group-result">
        {{--        <li class="list-group-item">+value.account+--}}
        {{--            <button class="btn btn-success" style="margin-left: 300px;" onclick="addFriends(value.user_id)">+添加+--}}
        {{--            </button>--}}
        {{--        </li>--}}
    </ul>
@endsection
@section('script')
    <script type="application/javascript">
        function searchFriends() {
            let account = $("#account").val();
            if (!account) {
                layer.msg("账号不能为空");
                return false;
            }
            $.ajax({
                type: "post",
                url: "/api/user/searchFriend",
                dataType: "json",
                data: {account: account},
                success: function (result) {
                    console.log(result);
                    if (result.code == 100) {
                        layer.msg(result.message, {icon: 2});
                        return false;
                    } else {
                        $("#list-group-result").html('');
                        $.each(result.data, function (index, value) {
                            $("#list-group-result").append(
                                "<li class=\"list-group-item\">" + value.account +
                                "   <button class=\"btn btn-success\" style=\"margin-left: 300px;\" onclick=\"addFriends(" + value.user_id + ")\">添加</button>" +
                                "</li>"
                            )
                            ;
                        })
                    }
                }
            });
        }

        function addFriends(userId) {
            $.ajax({
                type: "post",
                url: "/api/user/addFriend",
                dataType: "json",
                data: {userId: userId},
                success: function (result) {
                    console.log(result);
                    if (result.code == 100) {
                        layer.msg(result.message, {icon: 2});
                        return false;
                    } else {
                        layer.msg("申请已发出", {icon: 1});
                        window.location.reload();
                    }
                }
            });
        }
    </script>
@endsection