<?php
$time0 = microtime(true);
require 'functions.php';

$fuid = $_COOKIE["FID"];
$fuid = cx_cookie($fuid,"");
$txqq = cx_fuid($fuid)[4];
$rurl = $_SERVER['REQUEST_URI'];
$pubi = (String)substr($rurl, 0, 3);
$pubu = (String)substr($rurl, 3, 8);
$page = (String)substr($rurl, 1, 16);
$val0 = (String)htmlspecialchars($_POST["operate"]);
$val1 = (String)htmlspecialchars($_POST["val1"]);
$val2 = (String)htmlspecialchars($_POST["val2"]);
$val3 = (String)htmlspecialchars($_POST["val3"]);
$val4 = (String)htmlspecialchars($_POST["val4"]);
$val5 = (String)htmlspecialchars($_POST["val5"]);
$val6 = (String)htmlspecialchars($_POST["val6"]);

// echo initializationdb();//初始化数据库

switch ($page){
case ""://主页OK
    html_top("主页 - 福瑞导航",$txqq);
    if ($fuid <> ""){
        $xinx = cx_fuid($fuid);
        $sous = $xinx[6];
        $book = $xinx[7];
        if ($sous == 1){html_search();}
        if ($book == 1){
            out_bookmark(0);
        }elseif ($book == 2){
            out_bookmark($fuid);
            echo '
                <div style="height: 32px;border-bottom: 1px solid #777;"></div>
                <div style="height: 32px;font-size: 14px;color: #777;">以下是默认书签</div>';            
            out_bookmark(0);
        }elseif ($book == 3){
            out_bookmark($fuid);
        }
    }else{
        html_search();
        out_bookmark(0);
    }
    break;
    
case $pubi == "/u/"://公开主页OK
    $xinx = cx_fuid($pubu);
    $pub = $xinx[8];
    if ($pub == 2){
        $name = $xinx[5];
        $txqq = $xinx[4];
        $sous = $xinx[6];
        $book = $xinx[7];
        html_top($name." - 福瑞导航",$txqq);
        if ($sous == 1){html_search();}
        if ($book == 1){
            out_bookmark(0);
        }elseif ($book == 2){
            out_bookmark($pubu);
            echo '
                <div style="height: 32px;border-bottom: 1px solid #777;"></div>
                <div style="height: 32px;font-size: 14px;color: #777;">以下是默认书签</div>';            
            out_bookmark(0);
        }elseif ($book == 3){
            out_bookmark($pubu);
        }
    }elseif ($pub == ""){
        html_top("当前用户不存在 - 福瑞导航",$txqq);
        html_a_login("当前用户不存在","/","返回主页");
    }else{
        html_top("当前用户未公开书签 - 福瑞导航",$txqq);
        html_a_login("当前用户未开启公开书签","/","返回主页");
    }
    break;
    
case "exit"://退出登录OK
    if ($fuid <> ""){
        setcookie("FID", "", time()-3600);
        setcookie("UID", "", time()-3600);
        html_top("已退出登录 - 福瑞导航",$txqq);
        html_a_login("已退出登录","/","返回主页");
    }else{
        html_top("您未登录 - 福瑞导航",$txqq);
        html_a_login("您未登录","/","返回主页");
    }
    break;
    
case "addto"://添加网站OK
    $code = turecode($val6);
    if ($code and $val4 <> ""){
        html_top("添加网站 - 福瑞导航",$txqq);
        $val = addtourl($val1,$val2,$val3,$val4,$val5);
        html_addto($val);
    }else{
        html_top("添加网站 - 福瑞导航",$txqq);
        html_addto("");
    }
    break;
    
case "feedback"://反馈问题OK
    $code = turecode($val3);
    if ($code and $val1 <> ""){
        html_top("反馈问题 - 福瑞导航",$txqq);
        $val = feedback($val2,$val1);
        html_feedback($val);
    }else{
        html_top("反馈问题 - 福瑞导航",$txqq);
        html_feedback($val);
    }
    break;
    
case "help"://使用帮助OK
    html_top("使用帮助 - 福瑞导航",$txqq);
    html_help();
    break;
    
case "about"://关于本站OK
    html_top("关于本站 - 福瑞导航",$txqq);
    html_about();
    break;
    
case "register"://注册OK
    html_top("注册 - 福瑞导航",$txqq);
    if ($fuid <> ""){
        html_a_login("已登录账号","/exit","退出登录");
    }else{
        html_register();
    }
    break;
    
case "login"://登录OK
    html_top("登录 - 福瑞导航",$txqq);
    if ($fuid <> ""){
        html_a_login("已登录账号","/exit","退出登录");
    }else{
        html_login();
    }
    break;
    
case "forget"://忘记密码OK
    html_top("忘记密码 - 福瑞导航",$txqq);
    if ($fuid <> ""){
        html_a_login("已登录账号","/exit","退出登录");
    }else{
        html_forget();
    }
    break;
    
case "change"://修改密码OK
    html_top("修改密码 - 福瑞导航",$txqq);
    if ($fuid <> ""){
        html_change();
    }else{
        html_a_login("当前页需登录","/login","前往登录");
    }
    break;
    
case "user"://个人设置OK
    html_top("个人设置 - 福瑞导航",$txqq);
    if ($fuid <> ""){
        $retu = up_setup($fuid,$val1,$val2,$val3,$val4);
        out_user($fuid,$retu);
    }else{
        html_a_login("当前页需登录","/login","前往登录");
    }
    break;
    
case "logininfo"://登录信息OK
    html_top("登录信息 - 福瑞导航",$txqq);
    if ($fuid <> ""){
        out_logininfo($fuid);
    }else{
        html_a_login("当前页需登录","/login","前往登录");
    }
    break;
    
case "urlmanager"://网址管理器OK
    html_top("网址管理器 - 福瑞导航",$txqq);
    if ($fuid <> ""){
        in_url($fuid,$_FILES["file"]["type"],$_FILES["file"]["size"],$_FILES["file"]["error"],$_FILES["file"]["tmp_name"]);
        out_urlmanager($fuid);
        
    }else{
        html_a_login("当前页需登录 - 福瑞导航","/login","前往登录");
    }
    break;
    
case "outbook"://输出CSV文件OK
    if ($fuid <> ""){
        outbook($fuid);
        exit;
    }else{
        html_a_login("当前页需登录","/login","前往登录");
    }
    break;
    
case "imgcode"://图片验证码OK
    imgcode();
    break;
    
case "post"://处理POST请求OK
    
    $pox = wolf_post($fuid,$val0);
    if ($pox > 256){//限制请求数OK
        echo "请求超出限制，请稍后重试";
        exit;
    }
    
    switch ($val0){
    case "url"://更新访客浏览量OK
        echo up_fklll($val1);
        break;
        
    case "user"://查询用户名是否可用OK
        echo cx_usermail($val1);
        break;
        
    case "mail"://查询邮箱是否可用OK
        if (substr($val1, -7) == "@qq.com"){
            echo cx_usermail($val1);
        }else{
            echo "仅支持QQ邮箱";
        }
        break;
        
    case "code"://发送邮件验证码OK
        if (substr($val1, -7) == "@qq.com"){
            $xinx = cx_fuid($val1);
            $mail = $xinx[4];
            if ($mail == ""){
                echo fs_yzcode($val1,"新用户注册");
            }else{
                echo "非法请求";
            }
        }else{
            echo "仅支持QQ邮箱";
        }
        break;
        
    case "codeval"://找回密码，发送邮件验证码OK
        $mail = cx_mail($val1);
        echo fs_yzcode($mail,"找回密码");
        break;
        
    case "register"://注册新用户OK
        echo zc_user($val1,$val2,$val3,$val4,$val5);
        break;
        
    case "login"://登录OK
        echo login($val1,$val2);
        break;
        
    case "forget"://忘记密码OK
        echo up_forget($val1,$val2,$val3);
        break;
        
    case "change"://修改密码OK
        echo up_change($val1,$val2);
        break;
        
    case "name"://修改名称OK
        if ($fuid <> ""){
            echo xg_name($fuid,$val1);
        }else{
            echo "非法请求";
        }
        break;
        
    case "insbook"://插入书签OK
        $val2 = str_replace("'",' ',$val2);
        $val3 = str_replace("'",' ',$val3);
        $val4 = str_replace("'",' ',$val4);
        echo ins_book($val1,$val2,$val3,$val4,$val5,$val6);
        break;
        
    case "updbook"://更新书签OK
        $val2 = str_replace("'",' ',$val2);
        $val3 = str_replace("'",' ',$val3);
        $val4 = str_replace("'",' ',$val4);
        echo upd_book($val1,$val2,$val3,$val4,$val5,$val6);
        break;
        
    case "delbook"://删除书签OK
        echo del_book($val1);
        break;
        
    default:
        echo "非法请求";
    }
    exit;
    break;
    
default:
    html_top("未找到此页面 - 福瑞导航",$txqq);
    html_a_login("当前页未找到","/","返回主页");
}

html_bottom();
$time1 = microtime(true);
$timec = (int)(($time1 - $time0)*1000000);
echo "<!--耗时：$timec μs-->\n\n";
?>
