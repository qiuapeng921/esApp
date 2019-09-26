<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div>
    <div>
        <p>info below</p>
        <ul  id="line">
        </ul>
    </div>
    <div>
        <select id="classes">
            <option value="index">index</option>
            <option value="user">user</option>
        </select>
        <select id="action">
            <option value="who">who</option>
            <option value="hello">hello</option>
            <option value="delay">delay</option>
        </select>
        <input type="text" id="says">
        <button onclick="say()">发送</button>
    </div>
</div>
</body>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
    var wsServer = 'ws://dev.phpswoole.com';
    var websocket = new WebSocket(wsServer);
    window.onload = function () {
        websocket.onopen = function (evt) {
            addLine("Connected to WebSocket server.");
        };
        websocket.onclose = function (evt) {
            addLine("Disconnected");
        };
        websocket.onmessage = function (evt) {
            addLine('Retrieved data from server: ' + evt.data);
        };
        websocket.onerror = function (evt, e) {
            addLine('Error occured: ' + evt.data);
        };
    };
    function addLine(data) {
        $("#line").append("<li>"+data+"</li>");
    }
    function say() {
        var classes = $("#classes").val();
        var action = $("#action").val();
        var content = $("#says").val();
        $("#says").val('');
        websocket.send(JSON.stringify({
            class:classes,
            action:action,
            content:content
        }));
    }
</script>
</html>