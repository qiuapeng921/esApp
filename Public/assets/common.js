// $.cookie('the_cookie','the_value',{
//     expires:7,
//     path:'/',
//     domain:'jquery.com',
//     secure:true
// })
// expires：（Number|Date）有效期；设置一个整数时，单位是天；也可以设置一个日期对象作为Cookie的过期日期；
// path：（String）创建该Cookie的页面路径；
// domain：（String）创建该Cookie的页面域名；
// secure：（Booblean）如果设为true，那么此Cookie的传输会要求一个安全协议，例如：HTTPS；

function setCookie(key, value, time = 7) {
    $.cookie(key, value, {expires: time, path: '/'});
}

function getCookie(key) {
    return $.cookie(key);
}

function delCookie(key) {
    $.cookie(key, null);
}

function logout() {
    delCookie('user_id');
    delCookie('token');
    delCookie('nick_name');
}

function init() {
    let token = getCookie("token")
    let nick_name = getCookie("nick_name")
    if (token) {
        $("#nick_name").text("欢迎您：" + nick_name);
        // $("#login").remove()
        // $("#login_status").remove()
    }
}

init();
