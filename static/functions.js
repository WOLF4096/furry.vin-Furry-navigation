var name = "search=";
var ca = document.cookie.split(';');
for(var i=0; i<ca.length; i++) {
    var c = ca[i].trim();
    if (c.indexOf(name)==0) {
        var user = c.substring(name.length,c.length);
    }
}
switch (user){ 
case "baidu":
    topss("s-baidu","type-baidu");
    break;
case "bing":
    topss("s-bing","type-bing1");
    break;
case "google":
    topss("s-google","type-google");
    break;
case "yandex":
    topss("s-yandex","type-github");
    break;
case "ask":
    topss("s-ask","type-mj");
    break;
default:
    topss("s-baidu","type-baidu");
}
function topss(val1,val2) {
    document.getElementById(val1).onclick();
    document.getElementById(val2).checked = "qwq";
}
function baidu() {
    topssnr("https://www.baidu.com/s","百度一下 你就知道","wd","baidu");
}
function bing() {
    topssnr("https://cn.bing.com/search","Bing","q","bing");
}
function google() {
    topssnr("https://www.google.com/search","Google","q","google");
}
function yandex() {
    topssnr("https://yandex.com/search/","Finds everything","text","yandex");
}
function ask() {
    topssnr("https://www.ask.jp/web","Search...","q","ask");
}
function topssnr(val1,val2,val3,val4) {
    document.getElementById("super-search-fm").action = val1;
    document.getElementById("search-text").placeholder = val2;
    document.getElementById("search-text").name = val3;
    var d = new Date();
    d.setTime(d.getTime()+(365*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = "search="+val4+";"+expires;
}
function notnull(str) {
    if (str != ""){
        document.getElementById("faso").disabled = false;
    }else{
        document.getElementById("faso").disabled = true;
    }
}
function pass1(str) {
    document.getElementById("val1").value = kmac128('16bdc83ff9b03c336c594895a736b3b36667f1458441e9f17b92a0e7b94c2ec3', str, 256, 'WOLF4096');
}
function pass2(str) {
    document.getElementById("val2").value = kmac128('16bdc83ff9b03c336c594895a736b3b36667f1458441e9f17b92a0e7b94c2ec3', str, 256, 'WOLF4096');
}
function cx_user(str) {
    var use = "operate=user" + "&val1=" + str;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(use);
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var user = httpRequest.responseText;
            document.getElementById("return1").innerHTML = user;
        }
    };
}
function cx_mail(str) {
    if (str.length > 12){
        var mai = "operate=mail" + "&val1=" + str;
        var httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', '/post', true);
        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send(mai);
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                var mail = httpRequest.responseText;
                document.getElementById("return2").innerHTML = mail;
                var mail = mail.replace(/[^\u4E00-\u9FA5]/g, '');
                if (mail == "可用") {
                    document.getElementById("faso").disabled = false;
                }else{
                    document.getElementById("faso").disabled = true;
                }
            }
        };
    }else{
        document.getElementById("faso").disabled = true;
    }
}
function fs_code(thisBtn) {
    document.getElementById("mail").disabled = true;
    thisBtn.disabled = true;
    thisBtn.value = '已发送验证码';
    var mail = document.getElementById("mail").value;
    var mai = "operate=code" + "&val1=" + mail;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(mai);
}
function fs_codeval(thisBtn) {
    thisBtn.disabled = true;
    thisBtn.value = '已发送验证码';
    var fuid = document.getElementById("fuid").value;
    var code = "operate=codeval" + "&val1=" + fuid;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(code);
}
function register(thisBtn) {
    var val1 = encodeURIComponent(document.getElementById("name").value);
    var val2 = document.getElementById("user").value;
    var val3 = document.getElementById("val1").value;
    var val4 = document.getElementById("mail").value;
    var val5 = document.getElementById("code").value;
    var zhu = "operate=register" + "&val1=" + val1 + "&val2=" + val2 + "&val3=" + val3 + "&val4=" + val4 + "&val5=" + val5;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(zhu);
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var zhuc = httpRequest.responseText;
            document.getElementById("return3").innerHTML = zhuc;
            var zhuc = zhuc.replace(/[^\u4E00-\u9FA5]/g, '');
            if (zhuc == "注册成功") {
                thisBtn.disabled = true;
                setTimeout("window.location.href = '/login'", 1000);
            }
        }
    };
}
function login(thisBtn) {
    var val1 = document.getElementById("fuid").value;
    var val2 = document.getElementById("val1").value;
    var log = "operate=login" + "&val1=" + val1 + "&val2=" + val2;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(log);
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var login = httpRequest.responseText;
            document.getElementById("return").innerHTML = login;
            var login = login.replace(/[^\u4E00-\u9FA5]/g, '');
            if (login == "登录成功") {
                thisBtn.disabled = true;
                setTimeout("window.location.href = '/'", 1000);
            }
        }
    };
}
function forget(thisBtn) {
    var val1 = document.getElementById("fuid").value;
    var val2 = document.getElementById("val1").value;
    var val3 = document.getElementById("code").value;
    var forg = "operate=forget" + "&val1=" + val1 + "&val2=" + val2 + "&val3=" + val3;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(forg);
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var forget = httpRequest.responseText;
            document.getElementById("return").innerHTML = forget;
            var forget = forget.replace(/[^\u4E00-\u9FA5]/g, '');
            if (forget == "修改成功") {
                thisBtn.disabled = true;
                setTimeout("window.location.href = '/login'", 1000);
            }
        }
    };
}
function change(thisBtn) {
    var val1 = document.getElementById("val1").value;
    var val2 = document.getElementById("val2").value;
    var chan = "operate=change" + "&val1=" + val1 + "&val2=" + val2;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(chan);
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var change = httpRequest.responseText;
            document.getElementById("return").innerHTML = change;
            var change = change.replace(/[^\u4E00-\u9FA5]/g, '');
            if (change == "修改成功") {
                thisBtn.disabled = true;
                setTimeout("window.location.href = '/login'", 1000);
            }
        }
    };
}
function jl_url(href) {
    var url = "operate=url" + "&val1=" + encodeURIComponent(href);
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(url);
}
function iname() {
    var awa = document.getElementById("inname").innerHTML.slice(1,6);
    if (awa == "input"){
        var owo = document.getElementById("tene").value;
        var xwx = "operate=name" + "&val1=" + owo;
        var httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', '/post', true);
        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send(xwx);
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                var owo = httpRequest.responseText;
                var qwq = '<sapn><b id="renr">' + owo + '</b></span> ';
                var uwu = '<span style="font-size:14px;color:#777;" onclick="iname()">[修改兽名]</span>';
                document.getElementById("inname").innerHTML = qwq + uwu;
            }
        };
    }else{
        var owo = document.getElementById("renr").innerHTML;
        var qwq = '<input type="text" name="inme" value="' + owo + '" id="tene" style="height: 27px;border-radius: 4px;border: 1px solid #ccc;"> ';
        var uwu = '<span style="font-size:14px;color:#777;" onclick="iname()">[提交]</span>';
        document.getElementById("inname").innerHTML = qwq + uwu;
    }
}
function insbook() {
    var val1 = document.getElementById("val1").value;
    var val2 = encodeURIComponent(document.getElementById("val2").value);
    var val3 = encodeURIComponent(document.getElementById("val3").value);
    var val4 = encodeURIComponent(document.getElementById("val4").value);
    var val5 = encodeURIComponent(document.getElementById("val5").value);
    var val6 = encodeURIComponent(document.getElementById("val6").value);
    var insb = "operate=insbook" + "&val1=" + val1 + "&val2=" + val2 + "&val3=" + val3 + "&val4=" + val4 + "&val5=" + val5 + "&val6=" + val6;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(insb);
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var insb = httpRequest.responseText;
            var insb = insb.replace(/[^\u4E00-\u9FA5]/g, '');
            if (insb == "添加成功") {
                document.getElementById("return").innerHTML = "添加成功，刷新显示";
            }else{
                document.getElementById("return").innerHTML = insb;
            }
        }
    };
}
function updbook() {
    var val1 = document.getElementById("val1").value;
    var val2 = encodeURIComponent(document.getElementById("val2").value);
    var val3 = encodeURIComponent(document.getElementById("val3").value);
    var val4 = encodeURIComponent(document.getElementById("val4").value);
    var val5 = encodeURIComponent(document.getElementById("val5").value);
    var val6 = encodeURIComponent(document.getElementById("val6").value);
    var updb = "operate=updbook" + "&val1=" + val1 + "&val2=" + val2 + "&val3=" + val3 + "&val4=" + val4 + "&val5=" + val5 + "&val6=" + val6;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(updb);
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var updb = httpRequest.responseText;
            var updb = updb.replace(/[^\u4E00-\u9FA5]/g, '');
            if (updb == "修改成功") {
                document.getElementById("return").innerHTML = "修改成功，刷新显示";
            }else{
                document.getElementById("return").innerHTML = updb;
            }
        }
    };
}
function delbook() {
    var val1 = document.getElementById("val1").value;
    var delb = "operate=delbook" + "&val1=" + val1;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('POST', '/post', true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(delb);
    httpRequest.onreadystatechange = function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var delb = httpRequest.responseText;
            var delb = delb.replace(/[^\u4E00-\u9FA5]/g, '');
            if (delb == "删除成功") {
                document.getElementById("return").innerHTML = "删除成功，刷新显示";
            }else{
                document.getElementById("return").innerHTML = delb;
            }
        }
    };
}