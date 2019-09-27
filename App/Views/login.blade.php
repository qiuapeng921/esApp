<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Login Form Template</title>

    <!-- CSS -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="{{asset("assets/bootstrap/css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/font-awesome/css/font-awesome.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/form-elements.css")}}">
    <link rel="stylesheet" href="{{asset("assets/css/style.css")}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="{{asset("assets/ico/favicon.png")}}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="{{asset("assets/ico/apple-touch-icon-144-precomposed.png")}}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="{{asset("assets/ico/apple-touch-icon-114-precomposed.png")}}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="{{asset("assets/ico/apple-touch-icon-72-precomposed.png")}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset("assets/ico/apple-touch-icon-57-precomposed.png")}}">

</head>

<body>

<!-- Top content -->
<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 form-box">
                    <div class="form-bottom">
                        <form role="form" action="" method="post" class="login-form">
                            <div class="form-group">
                                <label class="sr-only" for="account">Username</label>
                                <input type="text" name="account" placeholder="Account"
                                       class="form-account form-control" id="account">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="password">Password</label>
                                <input type="password" name="password" placeholder="Password"
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
<script src="{{asset("assets/js/cookie.js")}}"></script>
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
                console.log(result);
                if (result.code == 100) {
                    layer.alert(result.message, {icon: 2});
                    return false;
                } else {
                    layer.alert("登陆成功", {icon: 1}, function () {
                        window.location.href = '/socket?token=' + result.data;
                    });
                }
            }
        });
    }
</script>
</body>
</html>