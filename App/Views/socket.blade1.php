<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
    <style>
        .container {
            text-align: center
        }
    </style>
</head>
<body>
<div class="container">
    <div>
        <label>
            <input type="hidden" id="token" value="{{$token}}">
        </label>
        <h1>聊天室</h1>
        <div id="content" style="width: 472px;height: 500px;background: #9d9d9d;">
        </div>

    </div>
    <div>
        <label for="action">1</label>
        <select id="action">
            <option value="sendToAll">全体</option>
        </select>
        <label for="says">聊天信息</label>
        <textarea id="says" style="width: 472px;height: 204px;"></textarea>
        <button onclick="say()">发送</button>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
<script>
    let token = $("#token").val();
    let wsServer = 'ws://dev.phpswoole.com/socket?token=' + token;
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
            addLine('消息: ' + evt.data);
        }
    };
    websocket.onerror = function (evt, e) {
        addLine('连接失败: ' + evt.data);
    };

    function addLine(data) {
        $("#content").append("<span style='float: left;'>" + data + "</span>");
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
</html>