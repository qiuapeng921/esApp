<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="聊天室">
    <title>聊天室</title>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    @yield('style')
</head>

<body>
@include('layouts.header')

<div class="container">
    @yield('content')
</div>
@include('layouts.footer')

@yield('script')
</body>
</html>
