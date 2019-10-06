<nav class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">在线聊天系统</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/hall">大厅</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="/room">聊天</a></li>
                <li><a href="/friend">通讯录</a></li>
                <li><a href="/group">群组</a></li>
            </ul>
            <ul class="nav navbar-nav" style="float: right;" id="login">
                <li><a href="/register" target="_blank">注册</a></li>
                <li><a href="/login" onclick="logout()" target="_blank">登陆</a></li>
            </ul>
            <ul class="nav navbar-nav" style="float: right;" id="login_status">
                <li><a id="nick_name"></a></li>
                <li><a href="#" onclick="logout()">退出</a></li>
            </ul>
        </div>
    </div>
</nav>