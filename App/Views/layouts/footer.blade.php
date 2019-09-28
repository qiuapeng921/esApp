<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{asset("assets/js/cookie.js")}}"></script>
<script src="{{asset("assets/layer/layer.js")}}"></script>
<script>
    let type = getQueryVariable("type");
    if (!type) {
        type = 0;
    }
    let id = getQueryVariable("id");
    if (!id) {
        id = 0;
    }
    let wsServer = 'ws://127.0.0.1:9501/hall?type=' + type + '&id=' + id;
    let websocket = new WebSocket(wsServer);
    websocket.onopen = function (evt) {
        addLine("初始化成功，请文明聊天");
        heartCheck.reset().start();//心跳检测重置
    };
    websocket.onclose = function (evt) {
        addLine("链接关闭");
    };
    websocket.onmessage = function (evt) {
        heartCheck.reset().start();//拿到任何消息都说明当前连接是正常的
        console.log(evt.data)
        if (isJSON(evt.data)) {
            let result = JSON.parse(evt.data);
            switch (result.type) {
                // 私聊
                case "send":
                    addLine(result.data);
                    break;
                // 群聊
                case "sendGroup":
                    addLine(result.data);
                    break;
                // 推送
                case "sendAll":
                    addLine(result.option.nick_name + "说:" + result.data);
                    break;
                case "join":
                    addLine("用户~" + result.option.nick_name + "加入聊天室");
                    break;
                case "leave":
                    addLine(result.option.nick_name + "已退出聊天室");
                    break;
                default:
                    break;
            }
        } else if (evt.data !== 'PONG') {
            addLine(evt.data);
        } else {
            console.log(evt.data);
        }
    };
    websocket.onerror = function (evt, e) {
        addLine('连接失败: ' + evt.data);
    };

    function addLine(data) {
        $("#message").append("<p style='line-height: 30px;'>" + data + "</p>");
    }

    // 发送消息
    function sendMessage(type) {
        let action = type;
        let content = $("#content");
        if (!content) {
            layer.msg("消息不能为空");
            return false;
        }
        websocket.send(JSON.stringify({
            class: "Send",
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
        timeout: 20000,//20秒发一次心跳
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
                websocket.send('PING');
                self.serverTimeoutObj = setTimeout(function () {//如果超过一定时间还没重置，说明后端主动断开了
                    websocket.close();     //如果onclose会执行reconnect，我们执行ws.close()就行了.如果直接执行reconnect 会触发onclose导致重连两次
                }, self.timeout)
            }, this.timeout)
        }
    };


    function isJSON(str) {
        if (typeof str == 'string') {
            try {
                JSON.parse(str);
                return true;
            } catch (e) {
                return false;
            }
        }
    }

    function getQueryVariable(variable) {
        let query = window.location.search.substring(1);
        let vars = query.split("&");
        for (let i = 0; i < vars.length; i++) {
            let pair = vars[i].split("=");
            if (pair[0] === variable) {
                return pair[1];
            }
        }
        return false;
    }
</script>