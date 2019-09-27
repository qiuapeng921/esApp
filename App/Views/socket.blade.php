<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<div>
    <div>
        <p>info below</p>
        <ul id="line">
        </ul>
    </div>
    <div>
        <select id="action">
            <option value="hello">hello</option>
            <option value="sendToAll">全体</option>
        </select>
        <input type="text" id="says">
        <button onclick="say()">发送</button>
    </div>
</div>
</body>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
    let wsServer = 'ws://dev.phpswoole.com/socket';
    let websocket = new WebSocket(wsServer);
    websocket.onopen = function (evt) {
        addLine("初始化");
    };
    websocket.onclose = function (evt) {
        addLine("链接关闭");
    };
    websocket.onmessage = function (evt) {
        addLine('消息: ' + evt.data);
    };
    websocket.onerror = function (evt, e) {
        addLine('连接失败: ' + evt.data);
    };

    function addLine(data) {
        $("#line").append("<li>" + data + "</li>");
    }

    function say() {
        let action = $("#action").val();
        let content = $("#says").val();
        $("#says").val('');
        websocket.send(JSON.stringify({
            action: action,
            content: content
        }));
    }

    function close() {
        websocket.onclose
    }
</script>
</html>