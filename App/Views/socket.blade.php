<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="聊天室">

    <title>聊天室</title>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-fixed navbar-inverse" role="navigation">
    <div class="container-fluid">

        <div>
            <ul class="nav navbar-nav">
                <li><a href="/socket">聊天室</a></li>
                <li><a href="#">创建分组</a></li>
            </ul>

            <ul class="nav navbar-nav" style="float: right;">
                <li><a href="register">注册</a></li>
                <li><a href="login">登陆</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="jumbotron">
        <div class="container">
            <label>
                <input type="hidden" id="token" value="{{$token}}">
            </label>

            <ul id="message" style="font-size: 14px;">

            </ul>
            {{--            <div class="media">--}}
            {{--                <div class="media-left">--}}
            {{--                    <img src="https://static.runoob.com/images/mix/img_avatar.png" class="media-object"--}}
            {{--                         style="width:40px" alt="">--}}
            {{--                </div>--}}
            {{--                <div class="media-body">--}}
            {{--                    <h6 class="media-heading">admin</h6>--}}
            {{--                    <p>测试</p>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <hr>
        </div>
    </div>
    <div class="left">

    </div>
    <div class="content">
        <div class="form-group" style="float: left;">
            <ul class="list-group" id="online_user">
                <li class="list-group-item"><a>免费域名注册</a></li>
            </ul>
        </div>
        <div class="form-group" style="margin-left: 300px;">
            <label for="name"></label>
            <label>消息框
                <textarea class="form-control" id="say" rows="6" style="width: 836px"></textarea>
                <button type="button" class="btn btn-success" style="margin-top: 20px;" onclick="say()">发送</button>
            </label>
        </div>
    </div>
</div>

<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
    let token = $("#token").val();
    let wsServer = 'ws://127.0.0.1:9501/socket?token=' + token;
    let websocket = new WebSocket(wsServer);
    websocket.onopen = function (evt) {
        addLine(new Date().toUTCString());
        heartCheck.reset().start();      //心跳检测重置
    };
    websocket.onclose = function (evt) {
        addLine("链接关闭");
    };
    websocket.onmessage = function (evt) {
        heartCheck.reset().start();      //拿到任何消息都说明当前连接是正常的
        if (evt.data != 'done') {
            addLine(evt.data);
        }
    };
    websocket.onerror = function (evt, e) {
        addLine('连接失败: ' + evt.data);
    };

    function addLine(data) {
        console.log(data);
        $("#message").append("<li style='line-height: 30px;'>" + data + "</li>");
    }

    function say() {
        let action = $("#action").val();
        let content = $("#says");
        websocket.send(JSON.stringify({
            action: action,
            content: content.val()
        }));
        content.val('')
    }

    window.onbeforeunload = function () {
        websocket.close();
    };

    //心跳检测
    let heartCheck = {
        timeout: 10000,//10秒发一次心跳
        timeoutObj: null,
        serverTimeoutObj: null,
        reset: function () {
            clearTimeout(this.timeoutObj);
            clearTimeout(this.serverTimeoutObj);
            return this;
        },
        start: function () {
            let self = this;
            this.timeoutObj = setTimeout(function () {
                //这里发送一个心跳，后端收到后，返回一个心跳消息，
                //onmessage拿到返回的心跳就说明连接正常
                websocket.send(JSON.stringify({
                    action: 'done',
                    content: 'ping'
                }));
                self.serverTimeoutObj = setTimeout(function () {//如果超过一定时间还没重置，说明后端主动断开了
                    websocket.close();     //如果onclose会执行reconnect，我们执行ws.close()就行了.如果直接执行reconnect 会触发onclose导致重连两次
                }, self.timeout)
            }, this.timeout)
        }
    }
</script>

</body>
</html>
