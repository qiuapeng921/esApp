<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登陆</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="{{asset("assets/bootstrap/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/font-awesome/css/font-awesome.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/form-elements.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/style.css")}}">

</head>

<body>

<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 form-box">
                    <div class="form-bottom">
                        <form role="form" action="" method="post" class="login-form">
                            <div class="form-group">
                                <label class="sr-only" for="account">账号</label>
                                <input type="text" name="account" placeholder="账号"
                                       class="form-account form-control" id="account">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="password">密码</label>
                                <input type="password" name="password" placeholder="密码"
                                       class="form-password form-control" id="password">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="login()">登陆</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Javascript -->
<script src="{{asset("assets/js/jquery-1.11.1.min.js")}}"></script>
<script src="{{asset("assets/bootstrap/js/bootstrap.min.js")}}"></script>
<script src="{{asset("assets/js/jquery.backstretch.min.js")}}"></script>
<script src="{{asset("assets/js/scripts.js")}}"></script>
<script src="{{asset("assets/layer/layer.js")}}"></script>
<script src="https://lib.baomitu.com/jquery.cookieBar/0.0.3/jquery.cookieBar.js"></script>
<script src="{{asset("assets/common.js")}}"></script>
<script type="application/javascript">
    function login() {
        let account = $("#account").val();
        let password = $("#password").val();
        if (!account) {
            layer.msg('账号不能为空');
            return false;
        }
        if (!password) {
            layer.msg('密码不能为空');
            return false;
        }
        $.ajax({
            type: "post",
            url: "/api/auth/login",
            dataType: "json",
            data: {account: account, password: password},
            success: function (result) {
                if (result.code == 100) {
                    layer.alert(result.message, {icon: 2});
                    return false;
                } else {
                    let user = result.data.info;
                    setCookie('user_id', user.user_id);
                    setCookie('token', result.data.token);
                    setCookie('nick_name', user.nick_name);
                    layer.alert("登陆成功", {icon: 1}, function () {
                        window.location.href = '/room';
                    });
                }
            }
        });
    }
</script>
</body>
</html>