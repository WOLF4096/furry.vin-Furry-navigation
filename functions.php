<?php
//2022-05-17 19:33
//48个函数

//数据库类
//数据库连接OK
function conndb(){
    $servername = "127.0.0.1";
    $username = "用户名";
    $password = "密码";
    $dbname = "数据库名";
    $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
}
//初始化数据库
function initializationdb(){
    $conn = conndb();
    $sql = "SELECT * FROM `wolf-post` LIMIT 1";
    if ($conn->query($sql)){
        $sql = "";
    }else{
        $sql = "CREATE TABLE `wolf-user` (
            `frid` int(8) NOT NULL,
            `user` varchar(32) NOT NULL,
            `pass` varchar(64) NOT NULL,
            `txqq` varchar(10) NOT NULL,
            `name` varchar(16) NOT NULL,
            `search` int(1) NOT NULL,
            `book` int(1) NOT NULL,
            `public` int(1) NOT NULL,
            `loginr` int(1) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';";
        if ($conn->query($sql)){
            $sql = "ALTER TABLE `wolf-user`ADD PRIMARY KEY (`frid`);";$conn->query($sql);
            $sql = "CREATE TABLE `wolf-link` (
                `urlid` int(10) AUTO_INCREMENT PRIMARY KEY,
                `frid` int(8) NOT NULL,
                `time` datetime NOT NULL,
                `num` int(4) NOT NULL,
                `class` varchar(32) NOT NULL,
                `title` varchar(32) NOT NULL,
                `introduce` varchar(128) NOT NULL,
                `url` text NOT NULL,
                `icon` text NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='书签表';";
            $conn->query($sql);
            $sql = "CREATE TABLE `wolf-code` (
                `time` int(10) NOT NULL,
                `ip` varchar(16) NOT NULL,
                `qq` varchar(10) NOT NULL,
                `code` int(6) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='验证码';";
            $conn->query($sql);
            $sql = "CREATE TABLE `wolf-uall` (
                `time` datetime NOT NULL,
                `url` text NOT NULL,
                `all` int(8) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='链接浏览量';";
            $conn->query($sql);
            $sql = "CREATE TABLE `wolf-info` (
                `frid` int(8) NOT NULL,
                `time` datetime NOT NULL,
                `ip` varchar(16) NOT NULL,
                `location` varchar(32) NOT NULL,
                `platform` varchar(32) NOT NULL,
                `operate` varchar(4) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作记录';";
            $conn->query($sql);
            $sql = "CREATE TABLE `wolf-fank` (
                `time` datetime NOT NULL,
                `ip` varchar(16) NOT NULL,
                `location` varchar(32) NOT NULL,
                `platform` varchar(16) NOT NULL,
                `mail` varchar(32) NOT NULL,
                `text` text NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='反馈';";
            $conn->query($sql);
            $sql = "CREATE TABLE `wolf-post` (
                `frid` int(8) NOT NULL,
                `time` datetime NOT NULL,
                `ip` varchar(16) NOT NULL,
                `location` varchar(32) NOT NULL,
                `post` varchar(32) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='请求记录表';";
            $conn->query($sql);
            $sql = "INSERT INTO `wolf-user` VALUES ('0','reserve0','WOLF4096','746515005','保留账号','1','1','2','2')";
            $conn->query($sql);
            $sql = "INSERT INTO `wolf-user` VALUES ('1','reserve1','WOLF4096','746515005','保留账号','2','3','2','2')";
            $conn->query($sql);
            $time = date('Y-m-d H:i:s',time());
            $sql = "INSERT INTO `wolf-link` VALUES (1,1,'$time',1,'联系方式','反馈群','福瑞导航反馈群','https://qm.qq.com/cgi-bin/qm/qr?k=waTcE7DHS-KNgIL3cltGH2P6zTxA57hy','http://p.qlogo.cn/gh/749609639/749609639/0')";
            $conn->query($sql);
            $sql = "INSERT INTO `wolf-link` VALUES (2,1,'$time',2,'联系方式','开发者','网站开发者-狼介','https://qm.qq.com/cgi-bin/qm/qr?k=VrhyBED02tgcgV0sazOIoJPYhwKbArAF','http://q1.qlogo.cn/g?b=qq&nk=746515005&s=640')";
            $conn->query($sql);
            for ($i=2; $i<=99; $i++){
                $sql = "INSERT INTO `wolf-user` VALUES ('$i','reserve$i','WOLF4096','746515005','保留账号','2','4','1','2')";
                $conn->query($sql);
            }
            $val = "初始化完成";
        }else{
            $val = "初始化失败,请检查数据库参数是否正确";
        }
    }
    return $val;
}



//判断查询类
//判断输入值返回邮箱OK
function cx_mail($fuid){
    $qq = cx_fuid($fuid)[4];
    if ($qq <> ""){
        $val = $qq."@qq.com";
    }elseif(substr($fuid, -7) == "@qq.com"){
        $mail = str_replace('@qq.com','',$fuid);
        $pdsz = preg_match('/^[0-9]+$/', $mail, $matches);
        if ($pdsz == 1){
            $val = $mail."@qq.com";
        }else{
            $val = "格式错误";
        }
    }else{
        $val = "格式错误";
    }
    return $val;
}
//输入fuid or user or mail查询OK
function cx_fuid($fuid){
    $conn = conndb();
    $fuid = (String)substr($fuid, 0, 32);
    $furif = preg_match('/^[A-Za-z0-9-_@.]+$/', $fuid, $matches);//检查合法字符
    $furem = preg_match('/^[A-Za-z0-9@.]*$/', $fuid, $matches);
    $furid = preg_match('/^[0-9]*$/', $fuid, $matches);
    $furus = preg_match('/^[A-Za-z0-9-_]+$/', $fuid, $matches);
    if ($furif == 1){
        if ($furem == 1 and substr($fuid, -7) == "@qq.com"){//mail
            $fuid = str_replace('@qq.com','',$fuid);
            $sql = "SELECT * FROM `wolf-user` WHERE `txqq` = '$fuid'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if ($row["txqq"] <> ""){
                $val = "邮箱可用";
            }else{
                $val = "邮箱未找到";
            }
        }else if ($furid == 1){//furid
            $sql = "SELECT * FROM `wolf-user` WHERE `frid` = '$fuid'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if ($row["frid"] <> ""){
                $val = "FUID可用";
            }else{
                $val = "FUID未找到";
            }
        }else if ($furus == 1){//user
            $sql = "SELECT * FROM `wolf-user` WHERE `user` = '$fuid'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if ($row["user"] <> ""){
                $val = "用户名可用";
            }else{
                $val = "用户名未找到";
            }
        }else{
            $val = "无结果";
        }
        $frid = $row["frid"];
        $user = $row["user"];
        $pass = $row["pass"];
        $txqq = $row["txqq"];
        $name = $row["name"];
        $sous = $row["search"];
        $book = $row["book"];
        $publ = $row["public"];
        $logi = $row["loginr"];
    }else{
        $val = "无结果";
    }
    $value = array(); 
    $value[0] = $val;//返回值
    $value[1] = $frid;//FurID
    $value[2] = $user;//用户名
    $value[3] = $pass;//密码
    $value[4] = $txqq;//QQ
    $value[5] = $name;//兽名
    $value[6] = $sous;//设置搜索
    $value[7] = $book;//设置书签
    $value[8] = $publ;//设置公开
    $value[9] = $logi;//登录提醒
    return $value;
}
//查询用户名或邮箱是否可用OK
function cx_usermail($in){
    $conn = conndb();
    $pdyh = preg_match('/^[A-Za-z0-9-_]+$/', $in, $matches);
    $pdsz = preg_match('/^[0-9]+$/', $in, $matches);
    if ($in <> ""){
        if ($pdyh == 1){
            if ($pdsz <> 1 and substr($in, -7) <> "@qq.com"){
                $sql = "SELECT * FROM `wolf-user` WHERE `user` = '$in'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $yhm = $row["user"];
                if($yhm == ""){
                    $val = $in."可用";
                }else{
                    $val = $in."已被注册";
                }
            }else{
                $val = "用户名不允许纯数字";
            }
        }elseif (substr($in, -7) == "@qq.com"){
            $qq = str_replace('@qq.com','',$in);
            $pdsz = preg_match('/^[0-9]+$/', $qq, $matches);
            if ($pdsz == 1 and strlen($qq) >= 5 and strlen($qq) <= 10){
                $sql = "SELECT * FROM `wolf-user` WHERE `txqq` = '$qq'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $yhm = $row["user"];
                if($yhm == ""){
                    $val = $in."可用";
                }else{
                    $val = $in."已被注册";
                }                
            }else{
                $val = "邮箱不合法";
            }
        }
    }else{
        $val = "未输入";
    }
    return $val;
}
//查询验证码是否正确OK
function cx_code($fuid,$code){
    $pdcd = preg_match('/^[0-9]+$/', $code, $matches);
    $mail = cx_mail($fuid);
    $ip = cx_ip()[0];
    $lval = strlen($mail);
    if ($lval >= 13 and $lval <= 17 and $code <> "" and $pdcd == 1){
        $conn = conndb();
        $mail = str_replace('@qq.com','',$mail);
        $tis = time() - 600;
        $sql = "SELECT * FROM `wolf-code` WHERE `time` > $tis AND `qq` = '$mail' AND `ip` = '$ip' GROUP BY `time` DESC LIMIT 1";//查询验证码是否正确
        $row = mysqli_fetch_array($conn->query($sql));
        $yzm1 = $row["code"];
        if($yzm1 == $code){
            $val = "正确";
        }else{
            $val = "错误";
        }
    }
    return $val;
}
//更新访客浏览量OK
function up_fklll($url){
    $time = date('Y-m-d H:i:s',time());
    if ($url <> ""){
        $conn = conndb();
        $sql = "SELECT `url` FROM `wolf-link` WHERE `url` = '$url'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $te1 = $row["url"];
        $tey = substr($url, 0, -1);
        $sql = "SELECT `url` FROM `wolf-link` WHERE `url` = '$tey'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $te2 = $row["url"];
        if ($te1 <> ""){
            $sql = "SELECT `all` FROM `wolf-uall` WHERE `url` = '$url'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $all = $row["all"];
            if ($all <> ""){
                $all++;
                $sql = "UPDATE `wolf-uall` SET `time` = '$time' , `all` = $all WHERE `url` = '$url'";
                $conn->query($sql);
                $val = $all;
            }else{
                $val = 1;
                $sql = "INSERT INTO `wolf-uall` VALUES ('$time','$url','$val')";
                $conn->query($sql);
            }
        }elseif ($te2 <> ""){
            $sql = "SELECT `all` FROM `wolf-uall` WHERE `url` = '$tey'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $all = $row["all"];
            if ($all <> ""){
                $all++;
                $sql = "UPDATE `wolf-uall` SET `time` = '$time' , `all` = $all WHERE `url` = '$tey'";
                $conn->query($sql);
                $val = $all;
            }else{
                $val = 1;
                $sql = "INSERT INTO `wolf-uall` VALUES ('$time','$tey','$val')";
                $conn->query($sql);
            }
        }else{
            $val = "非法请求";
        }
    }else{
        $val = "非法请求";
    }
    return $val;
}
//查询IP地址与大致位置OK
function cx_ip(){
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) {
        $wolf_ip = getenv('HTTP_CLIENT_IP');
    }elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')) {
        $wolf_ip = getenv('HTTP_X_FORWARDED_FOR');
    }elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'),'unknown')) {
        $wolf_ip = getenv('REMOTE_ADDR');
    }elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')) {
        $wolf_ip = $_SERVER['REMOTE_ADDR'];
    }
    $wolf_ip = preg_match ('/[\d\.]{7,15}/',$wolf_ip,$matches) ?$matches[0] :'';
    $ipjson = "http://myip.c.owo.fit/?ip=$wolf_ip";
    $json_string = file_get_contents($ipjson);
    $data = json_decode($json_string, true);
    $wz = $data['country'].$data['province'].$data['city'].$data['county']."_".$data['isp'];
    $wz_out = array(); 
    $wz_out[0] = $wolf_ip;
    $wz_out[1] = $wz;
    return $wz_out;
}
//获取请求数OK
function wolf_post($fuid,$val0){
    $conn = conndb();
    $time = date('Y-m-d H:i:s',time());
    $tim1 = date('Y-m-d H:i:s',time() - 60*60);
    $ipwz = cx_ip();
    $ip = $ipwz[0];
    $wz = $ipwz[1];
    $sql = "INSERT INTO `wolf-post` VALUES ('$fuid','$time','$ip','$wz','$val0')";
    $conn->query($sql);
    $sql = "SELECT COUNT(`ip`) as `all` FROM `wolf-post` WHERE `ip`='$ip' and `post`='$val0' and `time`>'$tim1'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $val = $row["all"];
    return $val;
}
//查询链接访客量OK
function cx_fklll($url){
    $conn = conndb();
    $sqla = "SELECT * FROM `wolf-uall` WHERE `url`='$url'";
    $rowa = $conn->query($sqla)->fetch_assoc();
    $val = $rowa["all"];
    if ($val == ""){$val = 0;}
    return $val;
}
//查询系统类型OK
function cx_os(){
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(strpos($agent, 'windows nt 10.0.2'))     {$platform = 'Windows 11';
    } elseif (strpos($agent, 'windows nt 10.')) {$platform = 'Windows 10';
    } elseif (strpos($agent, 'windows nt 6.4')) {$platform = 'Windows 10';
    } elseif (strpos($agent, 'windows nt 6.3')) {$platform = 'Windows 8.1';
    } elseif (strpos($agent, 'windows nt 6.2')) {$platform = 'Windows 8';
    } elseif (strpos($agent, 'windows nt 6.1')) {$platform = 'Windows 7';
    } elseif (strpos($agent, 'windows nt 6.0')) {$platform = 'Windows Vista';
    } elseif (strpos($agent, 'windows nt 5.2')) {$platform = 'Windows XP';
    } elseif (strpos($agent, 'windows nt 5.1')) {$platform = 'Windows XP';
    } elseif (strpos($agent, 'windows nt 5.0')) {$platform = 'Windows 2000';
    } elseif (strpos($agent, 'macintosh'))      {$platform = 'Mac';
    } elseif (strpos($agent, 'ipod'))           {$platform = 'Ipod';
    } elseif (strpos($agent, 'ipad'))           {$platform = 'Ipad';
    } elseif (strpos($agent, 'iphone'))         {$platform = 'Iphone';
    } elseif (strpos($agent, 'android 11'))     {$platform = 'Android 11';
    } elseif (strpos($agent, 'android 10'))     {$platform = 'Android 10';
    } elseif (strpos($agent, 'android 9'))      {$platform = 'Android 9';
    } elseif (strpos($agent, 'android 8.1'))    {$platform = 'Android 8.1';
    } elseif (strpos($agent, 'android 8.0'))    {$platform = 'Android 8.0';
    } elseif (strpos($agent, 'android 7.1'))    {$platform = 'Android 7.1';
    } elseif (strpos($agent, 'android 6.0'))    {$platform = 'Android 6.0';
    } elseif (strpos($agent, 'android 5.1'))    {$platform = 'Android 5.1';
    } elseif (strpos($agent, 'android 5.0'))    {$platform = 'Android 5.0';
    } elseif (strpos($agent, 'android 4.4'))    {$platform = 'Android 4.4';
    } elseif (strpos($agent, 'android 4.3'))    {$platform = 'Android 4.3';
    } elseif (strpos($agent, 'android 4.2'))    {$platform = 'Android 4.2';
    } elseif (strpos($agent, 'android 4.1'))    {$platform = 'Android 4.1';
    } elseif (strpos($agent, 'android 4.0'))    {$platform = 'Android 4.0';
    } elseif (strpos($agent, 'android 3.2'))    {$platform = 'Android 3.2';
    } elseif (strpos($agent, 'android 3.1'))    {$platform = 'Android 3.1';
    } elseif (strpos($agent, 'android 3.0'))    {$platform = 'Android 3.0';
    } elseif (strpos($agent, 'android'))        {$platform = 'Android';
    } elseif (strpos($agent, 'unix'))           {$platform = 'Unix';
    } elseif (strpos($agent, 'linux'))          {$platform = 'Linux';
    } else {$platform = 'Other';}
    return $platform;
}



//操作账号类
//注册OK
function zc_user($name,$user,$pass,$mail,$code){
    $conn = conndb();
    $kyus = substr(cx_usermail($user), - 6);
    $kyyx = substr(cx_usermail($mail), - 6);
    $ps64 = strlen($pass);
    $nail = cx_mail($mail);
    $txqq = str_replace('@qq.com','',$nail);
    $cdqq = strlen($txqq);
    $kycd = cx_code($nail,$code);
    $time = date('Y-m-d H:i:s',time());
    if ($kyus == "注册"){
        $val = "用户名已被使用";
    }elseif ($kyyx == "注册"){
        $val = "邮箱已被注册";
    }elseif ($kyus == "可用" and $kyyx == "可用" and $kycd == "正确" and $ps64 == 64 and $cdqq >= 5 and $cdqq <= 10){
        $ipwz = cx_ip();
        $ip = $ipwz[0];
        $wz = $ipwz[1];
        $xt = cx_os();
        $sql = "SELECT MAX(`frid`) AS MAX FROM `wolf-user`";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $max = $row["MAX"];
        $fuid = $max + 1;
        $pass = jy_pass($fuid,$pass);
        $sql = "INSERT INTO `wolf-user` VALUES ('$fuid', '$user', '$pass', '$txqq', '$name', 1, 2, 1, 2)";
        if ($conn->query($sql)){
            $sql = "INSERT INTO `wolf-info` VALUES ($fuid, '$time', '$ip', '$wz', '$xt', '注册成功')";
            $conn->query($sql);
            fs_zhuceok($fuid);
            $val = "注册成功";
        }else{
            $val = "注册失败";
        }
    }else{
        $val = "验证失败";
    }
    return $val;
}
//登录OK
function login($fuid,$pass){
    $conn = conndb();
    $time = date('Y-m-d H:i:s',time());
    $ipwz = cx_ip();
    $ip = $ipwz[0];
    $wz = $ipwz[1];
    $xt = cx_os();
    $cxus = cx_fuid($fuid);
    $fuid = $cxus[1];
    $cxmm = $cxus[3];
    $logi = $cxus[9];
    $pass = jy_pass($fuid,$pass);
    if($pass == $cxmm){
        $sql = "INSERT INTO `wolf-info` VALUES ('$fuid', '$time', '$ip', '$wz', '$xt', '登录成功')";//添加登录成功记录
        $conn->query($sql);
        cx_cookie($fuid,$pass);
        if ($logi == 2){
            fs_loginok($fuid,"登录成功");
        }
        $val = "登录成功";
    }else{
        $val = "用户名或密码错误";
        $sql = "INSERT INTO `wolf-info` VALUES ('$fuid', '$time', '$ip', '$wz', '$xt', '登录失败')";//添加登录失败记录
        $conn->query($sql);
    }
    return $val;
}
//密码加盐OK
function jy_pass($fuid,$pass){
    return hash("sha3-256",$fuid."不告诉你这里是什么".$pass);
}
//登录操作cookieOK
function cx_cookie($fid,$pas){
    if ($pas <> ""){
        $key = substr(hash("sha3-256",$fid."不告诉你这里是什么".$pas), 7, 50).substr(hash("sha3-256",$fid."不告诉你这里是什么".$pas), 2, 49);
        $expire = time() + 60*60*24*365;
        setcookie("FID", "$fid", $expire);
        setcookie("UID", "$key", $expire);
    }elseif ($fid <> ""){
        $uid = $_COOKIE["UID"];
        $pas = cx_fuid($fid)[3];
        $key = substr(hash("sha3-256",$fid."不告诉你这里是什么".$pas), 7, 50).substr(hash("sha3-256",$fid."不告诉你这里是什么".$pas), 2, 49);
        if ($uid == $key){
            $fid = $fid;
        }else{
            setcookie("FID", "", time()-3600);
            setcookie("UID", "", time()-3600);
            $fid = "";
        }    
    }
    return $fid;
}
//修改兽名OK
function xg_name($fuid,$name){
    $conn = conndb();
    $name =  htmlentities($name);
    $sql = "UPDATE `wolf-user` SET `name` = '$name' WHERE `frid` = '$fuid'";
    if ($conn->query($sql)){
        $sql = "SELECT `name` FROM `wolf-user` WHERE `frid` = '$fuid'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $name = $row["name"];
    }
    return $name;
}
//更新设置OK
function up_setup($fuid,$val1,$val2,$val3,$val4){
    $zhi = (int)$val1 + (int)$val2 + (int)$val3 + (int)$val4;
    if ($zhi >= 4){
        $conn = conndb();
        $sql = "UPDATE `wolf-user` SET `search`='$val1',`book`='$val2',`public`='$val3',`loginr`='$val4' WHERE `frid`='$fuid'";
        if ($conn->query($sql)){
            $val = "保存成功";
        }else{
            $val = "保存失败";
        }
    }
    return $val;
}
//忘记密码OK
function up_forget($fuid,$pass,$code){
    $conn = conndb();
    $time = date('Y-m-d H:i:s',time());
    $ipwz = cx_ip();
    $ip = $ipwz[0];
    $wz = $ipwz[1];
    $xt = cx_os();
    $cxus = cx_fuid($fuid);
    $fuid = $cxus[1];
    $cxmm = $cxus[3];
    $nail = $cxus[4]."@qq.com";
    $pass = jy_pass($fuid,$pass);
    $kycd = cx_code($nail,$code);
    if ($fuid <> "" and $kycd == "正确"){
        $sql = "UPDATE `wolf-user` SET `pass` = '$pass' WHERE `wolf-user`.`frid` = $fuid";
        if ($conn->query($sql)){
            $sql = "INSERT INTO `wolf-info` VALUES ('$fuid', '$time', '$ip', '$wz', '$xt', '忘记密码')";
            $conn->query($sql);
            $val = "修改成功";
            fs_loginok($fuid,"修改密码成功");
        }else{
            $val = "修改失败";
        }
    }else{
        $val = "验证失败";
    }
    return $val;
}
//修改密码OK
function up_change($pas1,$pas2){
    $conn = conndb();
    $time = date('Y-m-d H:i:s',time());
    $ipwz = cx_ip();
    $ip = $ipwz[0];
    $wz = $ipwz[1];
    $xt = cx_os();
    $fid = $_COOKIE["FID"];
    $fuid = cx_cookie($fid,"");
    $pas1 = jy_pass($fuid,$pas1);
    $pas2 = jy_pass($fuid,$pas2);
    $cxus = cx_fuid($fuid);
    $cxmm = $cxus[3];
    if ($cxmm == $pas2){
        $val = "新密码不能与旧密码相同";
    }elseif($cxmm == $pas1){
        $sql = "UPDATE `wolf-user` SET `pass` = '$pas2' WHERE `wolf-user`.`frid` = $fuid";
        if ($conn->query($sql)){
            $sql = "INSERT INTO `wolf-info` VALUES ('$fuid', '$time', '$ip', '$wz', '$xt', '修改密码')";
            $conn->query($sql);
            $val = "修改成功";
        }else{
            $val = "修改失败";
        }
    }else{
        $val = "验证失败";
    }
    return $val;
}
//添加书签OK
function ins_book($val1,$val2,$val3,$val4,$val5,$val6){
    $fid = $_COOKIE["FID"];
    $fuid = cx_cookie($fid,"");
    $time = date('Y-m-d H:i:s',time());
    if ($fuid <> ""){
        $conn = conndb();
        // if ($val1 == ""){
            $sql = "SELECT MAX(`num`) AS MAX FROM `wolf-link` WHERE `frid` = $fuid";//查询最大ID
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $max = $row["MAX"];
            $val1 = $max + 1;
        // }
        $ser = (int)$val1;
        $sql = "INSERT INTO `wolf-link` VALUES ('',$fuid,'$time',$ser,'$val2','$val3','$val4','$val5','$val6')";
        if ($conn->query($sql)){
            $val = "添加成功";
        }else{
            $val = "添加失败";
        }        
    }else{
        $val = "非法请求";
    }
    return $val;
}
//更新书签OK
function upd_book($val1,$val2,$val3,$val4,$val5,$val6){
    $fid = $_COOKIE["FID"];
    $fuid = cx_cookie($fid,"");
    $time = date('Y-m-d H:i:s',time());
    if ($fuid <> ""){
        $conn = conndb();
        if ($val1 <> ""){
            $val1 = (int)$val1;
            $sql = "SELECT * FROM `wolf-link` WHERE `frid` = $fuid AND `num` = $val1";//查询最大ID
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $fl1 = $row["class"];
            $bt1 = $row["title"];
            $js1 = $row["introduce"];
            $lj1 = $row["url"];
            $tb1 = $row["icon"];
            
            if ($val2 == ""){$val2 = $fl1;}
            if ($val3 == ""){$val3 = $bt1;}
            if ($val4 == ""){$val4 = $js1;}
            if ($val5 == ""){$val5 = $lj1;}
            if ($val6 == ""){$val6 = $tb1;}
            
            $sql = "UPDATE `wolf-link` SET `time`='$time',`class`='$val2',`title`='$val3',`introduce`='$val4',`url`='$val5',`icon`='$val6' WHERE `frid` = $fuid AND `num`= $val1";
            if ($conn->query($sql)){
                $val = "修改成功";
            }else{
                $val = "修改失败";
            }
        }else{
            $val = "未输入序号";
        }
    }else{
        $val = "非法请求";
    }
    return $val;
}
//删除指定书签OK
function del_book($val1){
    $fid = $_COOKIE["FID"];
    $fuid = cx_cookie($fid,"");
    if ($fuid <> ""){
        $conn = conndb();
        $d1 = explode("-",$val1)[0];
        $d2 = explode("-",$val1)[1];
        if ($d1 == $d2){
            $d1 = (int)$d1;
            $sql = "DELETE FROM `wolf-link` WHERE `frid` = $fuid AND `num` = $d1";
        }else if ($d2 <> ""){
            if ($d1 > $d2){$d0 = $d1;$d1 = $d2;$d2 = $d0;}
            $d1 = (int)$d1;$d2 = (int)$d2;
            $sql = "DELETE FROM `wolf-link` WHERE `frid` = $fuid AND `num` >= $d1 AND `num` <= $d2";
        }else{
            $ser = (int)$val1;
            $sql = "DELETE FROM `wolf-link` WHERE `frid` = $fuid AND `num` = $ser";
        }
        if ($conn->query($sql)){
            echo "删除成功";
        }else{
            echo "删除失败";
        }
    }else{
        $val = "非法请求";
    }
    return $val;
}
//反馈问题
function feedback($mail,$text){
    $conn = conndb();
    $time = date('Y-m-d H:i:s',time());
    $ipwz = cx_ip();
    $ip = $ipwz[0];
    $wz = $ipwz[1];
    $xt = cx_os();
    $sql = "INSERT INTO `wolf-fank` VALUES ('$time','$ip','$wz','$xt','$mail','$text')";
    if ($conn->query($sql)){
        $val = "提交成功";
        fs_audit("反馈问题","反馈",$text,$mail,"http://furry.vin/favicon.png","","","");
    }else{
        $val = "提交失败";
    }
    return $val;
}
//添加待审核网站OK
function addtourl($cla,$tit,$jie,$url,$ico){
    $conn = conndb();
    $time = date('Y-m-d H:i:s',time());
    $ipwz = cx_ip();
    $ip = $ipwz[0];
    $wz = $ipwz[1];
    $icn = "IP：".$ip." 位置：".$wz." 分类：".$cla." 图标：".$ico;
    
    $sql = "SELECT * FROM `wolf-link` WHERE `url` = '$url'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $durl = $row["url"];
    
    if ($durl == ""){
        $sql = "SELECT MAX(`num`) AS MAX FROM `wolf-link` WHERE `frid` = 6";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $max = (int)$row["MAX"];
        
        $max ++;
        $sql = "INSERT INTO `wolf-link` VALUES ('', 6, '$time', '$max', '1.待审核', '$tit', '$jie', '$url', '$icn')";
        if ($conn->query($sql)){
            $val = "提交成功";
            fs_audit($cla,$tit,$jie,$url,$ico,$ip,$wz,$max);// 发送邮件提醒
        }else{
            $val = "提交失败";
        }        
    }else{
        $val = "已提交";
    }
    return $val;
}
//输出CVS书签表OK
function outbook($fuid){
    $conn = conndb();
    $filn = "Furid_".$fuid."_bookmark_".time().".csv";
    $top = "FurID,添加时间,序号,分类,标题,介绍,链接,图标\n";
    $sql = "SELECT * FROM `wolf-link` WHERE `frid` = '$fuid' ORDER BY `num` ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $val1 = $row["frid"];
            $val2 = $row["time"];
            $val3 = $row["num"];
            $val4 = $row["class"];
            $val5 = $row["title"];
            $val6 = $row["introduce"];
            $val7 = $row["url"];
            $val8 = $row["icon"];
            $top .= "$val1,$val2,$val3,$val4,$val5,$val6,$val7,$val8\n";
        }
    }
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filn);
    echo mb_convert_encoding($top, "CP936");//适合Excel
}
//导入书签OK
function in_url($fuid,$type,$size,$error,$tmp_name){
    if (($type == "text/html") && ($size < 1024000)){//文件为html，且小于1000KB
    	if ($error > 0){
    	    echo "Error";
    	}else{
            $conn = conndb();
            $time = date('Y-m-d H:i:s',time());
            $filetime = "tmp/".time();
    		move_uploaded_file($tmp_name,$filetime);
            $file = fopen($filetime, "r") or exit("无法打开文件!");
            while(!feof($file)){
                $str = fgets($file);
                preg_match('/">(.*?)</', $str, $matches);
                $tit = $matches[1];
                preg_match('/\HREF="(.*?)\"/', $str, $matches);
                $url = $matches[1];
                preg_match('/ADD_DATE="(.*?)\"/', $str, $matches);
                $tim = $matches[1];
                if ($tim <> 0){
                    $tim = date('Y-m-d H:i:s',$tim);
                }
                preg_match('/\ICON="(.*?)\"/', $str, $matches);
                $icn = $matches[1];
                if ($tit <> "" and substr($url, 0, 4) <> "http"){
                    $fen = $tit;
                }else if ($tit <> "" and $url <> ""){
                    $sql = "SELECT * FROM `wolf-link` WHERE `frid` = $fuid AND `url` = '$url'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $clj = $row["url"];
                    $sql = "SELECT MAX(`num`)AS `max` FROM `wolf-link` WHERE `frid` = $fuid";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $max = $row["max"];
                    if ($clj == ""){
                        $max++;
                        $sql = "INSERT INTO `wolf-link` VALUES ('',$fuid,'$time',$max,'$fen','$tit','$tit','$url','$icn')";
                        $conn->query($sql);
                        $jie = "";
                    }
                }
            }
            fclose($file);//关闭文件
            unlink($filetime);//处理完后删除临时文件
    	}
    }
}
//简单的图片验证码OK
function imgcode(){
    $user = $_SERVER['HTTP_USER_AGENT'];
    $font = 'Res.ttf';
    $intg = rand(1111,9999);
    $key = hash("sha3-256",$intg."WOLF4096!WOLF!".$user);
    $expire = time() + 600;
    setcookie("key", $key, $expire);
    
    $s1 = substr($intg, 0, 1);
    $s2 = substr($intg, 1, 1);
    $s3 = substr($intg, 2, 1);
    $s4 = substr($intg, 3, 1);
    
    $im = imagecreate(128,64);
    $bg = imagecolorallocate($im, 255, 255, 255);
    
    $b1 = imagecolorallocate($im, rand(0,224), rand(0,224), rand(0,224));
    $b2 = imagecolorallocate($im, rand(0,224), rand(0,224), rand(0,224));
    $b3 = imagecolorallocate($im, rand(0,224), rand(0,224), rand(0,224));
    $b4 = imagecolorallocate($im, rand(0,224), rand(0,224), rand(0,224));
    
    imagettftext($im, 27, rand(-30,30), 07, rand(30,50), $b1, $font, $s1);
    imagettftext($im, 27, rand(-30,30), 37, rand(30,50), $b2, $font, $s2);
    imagettftext($im, 27, rand(-30,30), 67, rand(30,50), $b3, $font, $s3);
    imagettftext($im, 27, rand(-30,30), 97, rand(30,50), $b4, $font, $s4);
    
    imageline($im, 0, rand(0,64), 128, rand(0,64), $b1);
    imageline($im, 0, rand(0,64), 128, rand(0,64), $b2);
    imageline($im, 0, rand(0,64), 128, rand(0,64), $b3);
    imageline($im, 0, rand(0,64), 128, rand(0,64), $b4);
    
    header('Content-type: image/png');
    imagepng($im);
}
//验证图片验证码
function turecode($code){
    $user = $_SERVER['HTTP_USER_AGENT'];
    $cook = $_COOKIE["key"];
    $key = hash("sha3-256",$code."WOLF4096!WOLF!".$user);
    if ($cook == $key){
        $val = true;
    }else{
        setcookie("key", "", time()-3600);
        $val = false;
    }
    return $val;
}



//发送邮件类
//发送注册成功邮件OK
function fs_zhuceok($fuid){
    $user = cx_fuid($fuid);
    $us = $user[2];
    $qq = $user[4];
    $wz = cx_ip()[1];
    $xt = cx_os();
    $to = $qq."@qq.com";
    $message = '
    <div style="width: 100%;background: #f5f8fa;">
        <div style="width: 450px;background: #fff;margin: auto;">
            <div style="width: 360px;margin: auto;">
                <br/>
                <div style="font-size: 24px;"><b>'.$us.'</b> <span style="font-size: 14px;">FurID : '.$fuid.'</span><br/><b>在 福瑞导航 注册成功</b></div>
                <p style="font-size: 14px;"><b>位置</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$wz.'<br/><b>设备</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$xt.'</p>
                <div style="font-size: 12px;color: #777;">*位置系根据注册IP地址粗略确定。</div><br/>
                <p style="line-height: 24px;"><b style="font-size: 16px;">如果是你本人的操作</b><br/>你可以忽略此消息，无需采取任何措施。</p>
                <p style="line-height: 24px;"><b style="font-size: 16px;">如果这不是你本人操作</b><br/>请检查您的邮箱是否被盗用。</p>
                <p><a href="http://furry.vin/change" style="text-decoration: none;color: #1DA1F2;">更改你的密码。</a> 更改密码后所有平台需重新登录。</p><br/>
                <div style="text-align: center;font-size: 12px;color: #777;line-height: 20px;">这封邮件由系统自动生成，请勿回复</div>
                <br/>
                <br/>
            </div>
        </div>
    </div>
    ';
    $header  = "MIME-Version: 1.0\n";                        //设置MIME版本 
    $header .= "Content-type: text/html; charset=UTF-8\n";   //设置内容类型和字符集
    $header .= "From: Furry.vin<www@furry.vin>";             //设置发件人 
    $subject = "$us Registration success";                   //设置E-mail主题 不能为中文
    mail($to, $subject, $message, $header);
}
//发送登录成功邮件OK
function fs_loginok($fuid,$text){
    $user = cx_fuid($fuid);
    $us = $user[2];
    $qq = $user[4];
    $wz = cx_ip()[1];
    $xt = cx_os();
    $to = $qq."@qq.com";
    $message = '
    <div style="width: 100%;background: #f5f8fa;">
        <div style="width: 450px;background: #fff;margin: auto;">
            <div style="width: 360px;margin: auto;">
                <br/>
                <div style="font-size: 24px;"><b>您的账户 '.$us.'</b> <span style="font-size: 14px;">FurID : '.$fuid.'</span><br/><b>在 福瑞导航 '.$text.'</b></div>
                <p style="font-size: 14px;"><b>位置</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$wz.'<br/><b>设备</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$xt.'</p>
                <div style="font-size: 12px;color: #777;">*位置系根据登录IP地址粗略确定。</div><br/>
                <p style="line-height: 24px;"><b style="font-size: 16px;">如果是你本人的操作</b><br/>你可以忽略此消息，无需采取任何措施。</p>
                <p style="line-height: 24px;"><b style="font-size: 16px;">如果这不是你本人操作</b><br/>立即完成这些步骤，以保护你的账号。</p>
                <p><a href="http://furry.vin/change" style="text-decoration: none;color: #1DA1F2;">更改你的密码。</a> 更改密码后所有平台需重新登录。</p><br/>
                <div style="text-align: center;font-size: 12px;color: #777;line-height: 20px;">这封邮件由系统自动生成，请勿回复</div>
                <br/>
                <br/>
            </div>
        </div>
    </div>
    ';
    $header  = "MIME-Version: 1.0\n";                        //设置MIME版本 
    $header .= "Content-type: text/html; charset=UTF-8\n";   //设置内容类型和字符集
    $header .= "From: Furry.vin<www@furry.vin>";             //设置发件人 
    $subject = "$us login successful";                       //设置E-mail主题 不能为中文
    mail($to, $subject, $message, $header);
}
//发送邮件验证码OK
function fs_yzcode($fuid,$text){
    $val = cx_mail($fuid);
    $lval = strlen($val);
    if ($lval >= 13 and $lval <= 17){
        $time = time();
        $gtim = date('Y-m-d H:i:s',time() + 600);
        $ip = cx_ip()[0];
        $code = rand(111111,999999);
        
        $to = $val; //设置收件人 
        $message = '
        <div style="width: 100%;background: #f5f8fa;">
            <div style="width: 450px;background: #fff;margin: auto;">
                <div style="width: 360px;margin: auto;">
                    <br/>
                    <div style="font-size: 24px;"><b>确认你的邮件地址</b></div>
                    <p>输入以下验证码确认你的邮箱地址。</p>
                    <p>以下验证码用于 <b>'.$text.'</b>。</p>
                    <span style="font-size: 32px;"><b>'.$code.'</b></span>
                    <p>验证码将在 '.$gtim.' 过期。</p>
                    <p>谢谢，<br/>福瑞导航</p><br/>
                    <div style="text-align: center;font-size: 12px;color: #777;line-height: 20px;">如果您从未访问过 福瑞导航 请忽略此邮件<br/>这封邮件由系统自动生成，请勿回复</div>
                    <br/>
                    <br/>
                </div>
            </div>
        </div>
        ';
        $header  = "MIME-Version: 1.0\n";                        //设置MIME版本 
        $header .= "Content-type: text/html; charset=UTF-8\n";   //设置内容类型和字符集
        $header .= "From: Furry.vin<www@furry.vin>";             //设置发件人 
        $subject = "$code is your verification code";            //设置E-mail主题 不能为中文
        mail($to, $subject, $message, $header);
        $qq = str_replace('@qq.com','',$val);
        $conn = conndb();
        $sql = "INSERT INTO `wolf-code` VALUES ('$time', '$ip', '$qq', '$code')";//插入访客记录表//添加记录
        if ($conn->query($sql)){
            $val = "发送成功";
        }else{
            $val = "发送失败";
        }
    }else{
        $val = "格式错误";
    }
    return $val;
}
//发送审核网站邮件OK
function fs_audit($cla,$tit,$jie,$url,$ico,$ip,$wz,$max){
    $xt = cx_os();
    $to = "746515005@qq.com";
    $message = '
    <div style="width: 100%;background: #f5f8fa;">
        <div style="width: 450px;background: #fff;margin: auto;">
            <div style="width: 360px;margin: auto;">
                <br/>
                <div style="font-size: 24px;"><b>待审核的网址</b></div>
                <p style="font-size: 14px;">
                    <b>地址</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$ip.'<br/>
                    <b>位置</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$wz.'<br/>
                    <b>设备</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$xt.'<br/>
                </p>
                <p style="line-height: 24px;"><b style="font-size: 16px;">提交的内容</b>
                    <br/>序号：'.$max.'
                    <br/>分类：'.$cla.'
                    <br/>标题：'.$tit.'
                    <br/>介绍：'.$jie.'
                    <br/>链接：'.$url.'
                    <br/>图标：'.$ico.'
                    <br/><img src="'.$ico.'" width="320px" height="320px">
                </p>
                <br/>
                <br/>
            </div>
        </div>
    </div>
    ';
    $header  = "MIME-Version: 1.0\n";                        //设置MIME版本 
    $header .= "Content-type: text/html; charset=UTF-8\n";   //设置内容类型和字符集
    $header .= "From: Furry.vin<www@furry.vin>";             //设置发件人 
    $subject = "pending review";                             //设置E-mail主题 不能为中文
    mail($to, $subject, $message, $header);
}



//php输出html类
//输出个人设置OK
function out_user($fuid,$retu){
    $xinx = cx_fuid($fuid);
    $user = $xinx[2];
    $txqq = $xinx[4];
    $name = $xinx[5];
    $sous = $xinx[6];
    $book = $xinx[7];
    $publ = $xinx[8];
    $logi = $xinx[9];
    if ($fuid < 100){
        $zu = "[保留账号]";
    }else{
        $zu = "[普通用户]";
    }
    if ($sous == 1){$s1 = "checked";}elseif($sous == 2){$s2 = "checked";}
    if ($book == 1){$b1 = "checked";}elseif($book == 2){$b2 = "checked";}elseif($book == 3){$b3 = "checked";}elseif($book == 4){$b4 = "checked";}
    if ($publ == 1){$p1 = "checked";}elseif($publ == 2){$p2 = "checked";}
    if ($logi == 1){$l1 = "checked";}elseif($logi == 2){$l2 = "checked";}
?>
                <div>
                    <img src="http://q1.qlogo.cn/g?b=qq&nk=<?php echo $txqq;?>&s=640" width="128" width="128" style="border-radius: 64px;border: 2px solid #448EF677;float: left;">
                    <div style="margin-left: 140px;">
                        <div id="inname" style="font-size: 20px;line-height: 40px;">
                            <sapn><b id="renr"><?php echo $name;?></b></span>
                            <span style="font-size:14px;color:#777;" onclick="iname()">[修改兽名]</span>
                        </div>
                        <div style="font-size: 14px;height: 128px;" id="re">FurID : <?php echo $fuid." ".$zu?>
                        
                            <br/>用户名：<?php echo $user;?>
                            
                            <br/>注册邮箱：<?php echo $txqq;?>@qq.com
                            <!--<br/>专属邮箱：<?php echo $user;?>@furry.cfd (暂未开放) -->
                        </div>
                    </div>
                </div>
                <div style="width: 100%;background: #fafafa;border-radius: 4px;border: 1px solid #e7e7e7;padding: 16px;">
                    <p style="font-size:24px;"><b>设置</b></p>
                    <form method="post">
                        
                        <span style="font-size:20px;">主页搜索框</span><br>
                        <input type="radio" name="val1" value="1" <?php echo $s1;?>> 显示&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="val1" value="2" <?php echo $s2;?>> 不显示<br/>
                        <br/>
                        
                        <span style="font-size:20px;">主页书签</span><br>
                        <input type="radio" name="val2" value="1" <?php echo $b1;?>> 仅显示公共主页&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="val2" value="2" <?php echo $b2;?>> 个人书签+公共书签<br/>
                        <input type="radio" name="val2" value="3" <?php echo $b3;?>> 仅显示个人书签&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="val2" value="4" <?php echo $b4;?>> 无书签<br>
                        <br/>
                        
                        <span style="font-size:20px;">公开书签</span><br>
                        <input type="radio" name="val3" value="1" <?php echo $p1;?>> 关闭&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="val3" value="2"  <?php echo $p2;?>> 开启<br>
                        <span class="huise">*开启后，无需登录即可通过 http://<?php echo $_SERVER['HTTP_HOST']."/u/".$fuid;?><br/>查看已保存的书签，或者与他人共享</span><br/>
                        <br/>
                        
                        <span style="font-size:20px;">登录提醒</span><br>
                        <input type="radio" name="val4" value="1" <?php echo $l1;?>> 关闭&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="val4" value="2"  <?php echo $l2;?>> 开启<br>
                        <span class="huise">*关闭后无法接收登录提醒</span><br/>
                        <br/>
                        
                        <input type="submit" value="保存更改" class="input-text" style="background-color: #448EF6;color: #fff;">
                        <span><?php echo $retu;?></span>
                    </form>
                </div>
<?php
}
//输出主页书签OK
function out_bookmark($fuid){
    $conn = conndb();
    $sql = "SELECT DISTINCT `class` FROM `wolf-link` WHERE `frid` = $fuid ORDER BY `class` ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
        $fenl = $row["class"];
        echo '
                <p class="helptit"><b>'.$fenl.'</b></p>
                <ul class="joe_detail__friends" style="margin: auto;">';
        $sqlb = "SELECT * FROM `wolf-link` WHERE `frid` = $fuid and `class` = '$fenl' ORDER BY `num` ASC";
        $resultb = $conn->query($sqlb);
        if ($resultb->num_rows > 0) {
            while($row = $resultb->fetch_assoc()) {
            $url = $row["url"];
            $jsb = $row["introduce"];
            $ico = $row["icon"];
            $btb = $row["title"];
            $fkl = cx_fklll($url);
            echo '
                    <li style="height: 64px;">
                        <a target="_blank" href="'.$url.'" class="card tooltip tooltip-bottom " data-tooltip="'.$jsb.'" onclick="jl_url(href)">
                            <div class="bokico" style="background-image: url('.$ico.');"></div>
                            <div class="em05">
                                <b><span class="jctit">'.$btb.'</span></b><br/>
                                <span class="huise"><i class="text-md mr-2 iconfont icon-share-box-fill"></i>'.$fkl.'</span>
                            </div>
                        </a>
                    </li>';
                }
            }echo "
                </ul>";
        }
    }
}
//输出登录信息OK
function out_logininfo($fuid){
    $conn = conndb();
    $user = cx_fuid($fuid)[5];
    $sql = "SELECT * FROM `wolf-info` WHERE `frid` = $fuid order by `time` desc";
    $result = $conn->query($sql);
    echo '
                <div style="text-align: center;font-size: 20px;height: 50px;">'.$user.' 的账号操作记录 <span style="font-size: 12px;">FurID : '.$fuid.'</span></div>
                <table border="0" style="margin: auto;width: 100%;max-width: 768px;text-align: center;">
                    <tr>
                        <th>时间</th>
                        <th>IP</th>
                        <th>大致位置_运营商</th>
                        <th>设备</th>
                        <th>操作</th>
                    </tr>';
    if ($result->num_rows > 0) {// 输出数据
        while($row = $result->fetch_assoc()) {
            if ($row["operate"] == "登录失败"){
                echo '
                    <tr style="background: #CEF;">';
            }else if($row["operate"] == "修改密码" or $row["operate"] == "忘记密码"){
                echo '
                    <tr style="background: #FDB;">';
            }else if($row["operate"] == "注册成功"){
                echo '
                    <tr style="background: #DDD;">';
            }else{
                echo '
                    <tr>';
            }
                echo '
                        <td>'.$row["time"].'</td>
                        <td>'.$row["ip"].'</td>
                        <td>'.$row["location"].'</td>
                        <td>'.$row["platform"].'</td>
                        <td>'.$row["operate"].'</td>
                    </tr>';
        }
    }
    echo '
                </table>';
}
//输出网址管理OK
function out_urlmanager($fuid){
    $conn = conndb();
    $user = cx_fuid($fuid)[5];
    $sql = "SELECT * FROM `wolf-link` WHERE `frid` = $fuid ORDER BY `num` ASC";
    $result = $conn->query($sql);
    echo '
                <div style="text-align: center;font-size: 20px;height: 50px;">'.$user.' 的书签管理器v1.2 <span style="font-size: 12px;">FurID : '.$fuid.'</span></div>';
?>
                
                <table border="0" style="margin: auto;width: 100%;text-align: center;font-size: 14px;">
                    <tr>
                        <th>序号</th>
                        <th>分类</th>
                        <th>标题</th>
                        <th>介绍</th>
                        <th>链接（URL）</th>
                        <th>图标（URL）</th>
                    </tr>
                    <tr>
                        <td class="bgn"><input type="text" id="val1" class="inbfb" placeholder="修改/删除（1 or 1-5）"></td>
                        <td class="bgn"><input type="text" id="val2" class="inbfb" placeholder="添加/修改"></td>
                        <td class="bgn"><input type="text" id="val3" class="inbfb" placeholder="添加/修改"></td>
                        <td class="bgn"><input type="text" id="val4" class="inbfb" placeholder="添加/修改"></td>
                        <td class="bgn"><input type="text" id="val5" class="inbfb" placeholder="添加/修改"></td>
                        <td class="bgn"><input type="text" id="val6" class="inbfb" placeholder="添加/修改"></td>
                    </tr>
                </table>
                <br/>
                <div style="text-align:center;font-size: 14px;">
                    <form method="post" enctype="multipart/form-data">
                        <input type="button" value="添加网站" style="border: none;background-color: #448EF6;color: #fff;padding: 8px;border-radius: 4px;margin: 4px;" onclick="insbook()">
                        <input type="button" value="修改记录" style="border: none;background-color: #448EF6;color: #fff;padding: 8px;border-radius: 4px;margin: 4px;" onclick="updbook()">
                        <input type="button" value="删除记录" style="border: none;background-color: #448EF6;color: #fff;padding: 8px;border-radius: 4px;margin: 4px;" onclick="delbook()">
                        
                        <input type="file" name="file" style="border: none;background-color: #448EF6;color: #fff;padding: 6px;border-radius: 4px;margin: 4px;">
                        <input type="submit" value="导入书签" style="border: none;background-color: #448EF6;color: #fff;padding: 8px;border-radius: 4px;margin: 4px;">
                        <a target="_blank" href="/outbook">
                            <input type="button" value="导出CSV" style="border: none;background-color: #448EF6;color: #fff;padding: 8px;border-radius: 4px;margin: 4px;">
                        </a>
                    </form>
                    <span id="return">&nbsp;</span>
                </div>
                <br/>
                <table border="0" style="margin: auto;width: 100%;text-align: center;font-size: 12px;">
                    <tr>
                        <th style="width: 40px;">序号</th>
                        <th style="width: 96px;">分类</th>
                        <th>标题</th>
                        <th>介绍</th>
                        <th>链接</th>
                        <th style="width: 40px;">图标</th>
                    </tr>
<?php
    if ($result->num_rows > 0) {// 输出数据
        while($row = $result->fetch_assoc()) {
                echo '
                    <tr id="list'.$row["num"].'">
                        <td>'.$row["num"].'</td>
                        <td>'.$row["class"].'</td>
                        <td>'.$row["title"].'</td>
                        <td>'.$row["introduce"].'</td>
                        <td>'.$row["url"].'</td>
                        <td><img width="24" height="24" src="'.$row["icon"].'"></td>
                    </tr>';
        }
    }
    echo '
                </table>';
}



//纯html类
//输出头部html
function html_top($title,$avatar){
    setcookie("night", "0", time() + 746515005);
    $rurl = $_SERVER['REQUEST_URI'];
    $pubi = (String)substr($rurl, 0, 3);
?>
<!--函数输出头部-->
<!-- 狼介（WOLF4096）  QQ：746515005    All Platform ID: WOLF4096
 _       __   ____     __     ______   __ __   ____    ____    _____
| |     / /  / __ \   / /    / ____/  / // /  / __ \  / __ \  / ___/
| | /| / /  / / / /  / /    / /_     / // /_ / / / / / /_/ / / __ \ 
| |/ |/ /  / /_/ /  / /___ / __/    /__  __// /_/ /  \__, / / /_/ / 
|__/|__/   \____/  /_____//_/         /_/   \____/  /____/  \____/  

如有修改建议、添加功能、修复bug等问题，请与本狼联系（不吃兽）
日志：
2022-02-28：新建项目
2022-03-30：完成
2022-04-25：重构！
2022-05-02：重构完成！
2022-05-03：上线！
-->
<!DOCTYPE html>
<html lang="zh-CN" style="transform: none;">
    <head>
        <title><?php echo $title ?></title>
        <meta name="renderer" content="webkit">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="keywords" content="福瑞导航, Furry同城群, 兽圈快捷导航, furry,快捷导航,导航,兽圈,兽展,Furry同城,兽圈同城,同城,同好,约稿">
        <meta name="description" content="福瑞导航 - Furry文化主题网址及信息汇聚导航">
        <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
        <link rel="stylesheet" type="text/css" media="all" href="https://b.4l2.cn/usr/themes/Joe/assets/css/joe.global.min.css">    <!--蹭CDN-->
        <link rel="stylesheet" type="text/css" media="all" href="https://blog.furry.top/usr/themes/splity/css/iconfont.css">        <!--蹭CDN-->
        <link rel="stylesheet" type="text/css" media="all" href="https://blog.furry.top/usr/themes/splity/css/splity.css">          <!--蹭CDN-->
        <link rel="stylesheet" type="text/css" media="all" href="https://blog.furry.top/usr/themes/splity/style.css">               <!--蹭CDN-->
        <link rel="stylesheet" type="text/css" media="all" href="/static/append.css">
        <script type="text/javascript" src="https://blog.furry.top/usr/themes/splity/js/jquery.min.js"></script>                    <!--蹭CDN-->
    </head>
    <body class="home blog night" size-sensor-id="1" style="position: relative; transform: none;">
        <header class="header">
            <nav class="navbar navbar-expand-lg shadow">
                <div class="container">
                    <a href="/" rel="home" class="logo navbar-brand order-2 order-lg-1">福瑞导航</a>
                    <button class="navbar-toggler order-1" type="button" id="sidebarCollapse" name="wolf0x1">
                        <i class="text-xl iconfont icon-menu-line"></i>
                    </button>
                    <button class="navbar-toggler nav-search order-3 collapsed" data-target="#navbar-search" data-toggle="collapse" aria-expanded="false" aria-controls="navbar-search" name="wolf0x2">
<?php if($avatar <> ""){ ?>
                        <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $avatar; ?>&s=640" width="32px" height="32px" class="avatar avatar-32 photo avatar-default loaded" alt="qqico">
<?php }else{ ?>
                        <a class="btn btn-primary btn-sm" href="/login">登录</a>
<?php } ?>
                    </button>
                    <div class="collapse navbar-collapse order-md-2">
                        <ul class="navbar-nav main-menu ml-4 mr-auto">
                            <li><a href="/">首页</a></li>
                            <li><a href="/addto">添加</a></li>
                            <li><a href="/feedback">反馈</a></li>
                            <li><a href="/help">帮助</a></li>
                            <li><a href="/about">关于</a></li>
                        </ul>
                        <ul class="navbar-nav align-items-center order-1 order-lg-2">
<?php if($avatar <> ""){ ?>
                            <li class="nav-item nav-item-signin text-sm ml-2 ml-md-3">
                                <a class="d-flex align-items-center dropdown-toggle" id="link_item_signin" data-toggle="dropdown">
                                    <span class="flex-avatar w-32 mx-2">
                                        <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $avatar; ?>&s=640" width="32px" height="32px" class="avatar avatar-32 photo avatar-default loaded">
                                    </span>
                                </a>
<?php if ($avatar <> "" and $pubi <> "/u/"){ ?>
                                <div class="nice-dropdown" aria-labelledby="link_item_signin">
                                    <ul class="text-xs p-2">
                                        <li class="p-2">
                                            <a href="/user" rel="nofollow">
                                                <i class="text-md mr-2 iconfont icon-user-star-line"></i>个人设置</a>
                                        </li>
                                        <li class="p-2">
                                            <a href="/urlmanager" rel="nofollow">
                                                <i class="text-md mr-2 iconfont icon-menu-line"></i>网址管理</a>
                                        </li>
                                        <li class="p-2">
                                            <a href="/logininfo" rel="nofollow">
                                                <i class="text-md mr-2 iconfont icon-file-list--line"></i>操作记录</a>
                                        </li>
                                        <li class="p-2">
                                            <a href="/change" rel="nofollow">
                                                <i class="text-md mr-2 iconfont icon-pencil-line"></i>修改密码</a>
                                        </li>
                                        <li class="p-2">
                                            <a href="/exit">
                                                <i class="text-md mr-2 iconfont icon-login-box-line"></i>退出登录</a>
                                        </li>
                                    </ul>
                                </div>
<?php }else{ ?>
                                <div class="nice-dropdown" aria-labelledby="link_item_signin">
                                    <ul class="text-xs p-2">
                                        <li class="p-2">
                                            <a href="/" rel="nofollow">
                                                <i class="text-md mr-2 iconfont iconfont icon-home--line"></i>返回主页</a>
                                        </li>
                                    </ul>
                                </div>
<?php } ?>
                            </li>
<?php }else{ ?>
                            <a class="btn btn-primary btn-sm" href="/login">登录</a>
<?php } ?>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="mobile-sidebar">
                <div class="mobile-sidebar-header text-right p-3">
                    <div style="width: 66%;float: left;padding: 6px;">福瑞导航</div>
                    <button class="btn btn-icon sidebar-close" name="wolf0x3">
                        <span>
                            <i class="text-xl iconfont icon-close-circle-line"></i>
                        </span>
                    </button>
                </div>
                <ul class="mobile-sidebar-menu nav flex-column" style="padding: .75rem 0 0 0;">
                    <li><a href="/">网站首页</a></li>
                    <li><a href="/addto">添加网站</a></li>
                    <li><a href="/feedback">反馈问题</a></li>
                    <li><a href="/help">使用帮助</a></li>
                    <li><a href="/about">关于本站</a></li>
<?php if($avatar <> "" and $pubi <> "/u/"){ ?>
                    <li><a href="/user">个人设置</a></li>
                    <li><a href="/urlmanager">网址管理</a></li>
                    <li><a href="/logininfo">操作记录</a></li>
                    <li><a href="/change">修改密码</a></li>
                    <li><a href="/exit">退出登录</a></li>
<?php } ?>
                </ul>
            </div>
        </header>
        <main class="py-3 py-md-5" style="transform: none;">
            <div class="container" style="transform: none;">
<!--头部输出结束-->
<?php
}
//输出底部html
function html_bottom(){
?>

<!--函数输出底部-->
            </div>
        </main>
        <footer class="footer bg-dark py-3 py-lg-4">
            <div class="container">
                <div class="d-md-flex flex-md-fill align-items-md-center">
                    <div class="d-md-flex flex-md-column">
                        <div class="footer-copyright text-xs">Copyright © 2022. <a href="https://furgov.cn/?query=furry.vin" target="_blank">兽ICP备202205555号.</a></div>
                    </div>
                    <div class="flex-md-fill"></div>
                    <div class="mt-3 mt-md-0"></div>
                </div>
                <div class="footer-links border-top border-secondary pt-3 mt-3 text-xs">
                    <span>嗷呜~</span>
                </div>
            </div>
        </footer>
        <a href="javascript:void(0)" id="scroll_to_top" class="btn btn-primary btn-icon scroll-to-top" style="display: none;" name="wolf0x4">
            <span>
                <i class="text-lg iconfont icon-arrow-up-fill"></i>
            </span>
        </a>
        <div class="mobile-overlay"></div>
        <script type="text/javascript" src="/static/sha3.js"></script>
        <script type="text/javascript" src="/static/functions.js"></script>
        <script type="text/javascript" src="https://blog.furry.top/usr/themes/splity/js/splity.js?ver=1.0.2"></script>          <!--蹭CDN-->
        <script type="text/javascript" src="https://blog.furry.top/usr/themes/splity/js/bootstrap.min.js?ver=1.0.2"></script>   <!--蹭CDN-->
        <!--<script type="text/javascript" color="96,116,166" opacity="0.7" zindex="-1" count="128" src="/static/canvas-nest.js"></script>-->
        <!--<canvas style="display: block; position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; overflow: hidden; pointer-events: none; z-index: -1; opacity: 0.7;"></canvas>-->
    </body>
</html>
<!--底部输出结束-->
<?php
}
//搜索区域html
function html_search(){
?>
                <div style="box-sizing: border-box;">
                    <div id="search" style="max-width: 540px;padding: 16px;">
                        <div id="search-list" style="margin-left: 1em;">
                            <div class="s-current">
                                <ul class="search-type" style="max-width: 320px;border-radius: 10px 10px 0 0;">
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-baidu">
                                        <label id="s-baidu" for="type-baidu" onclick="baidu()"><span style="color:#2529d8">Baidu</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-bing1">
                                        <label id="s-bing" for="type-bing1" onclick="bing()"><span style="color:#0C8484">Bing</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-google">
                                        <label id="s-google" for="type-google" onclick="google()">
                                            <span style="color:#4285f4">G</span><span style="color:#ea4335">o</span><span style="color:#fbbc05">o</span><span style="color:#4285f4">g</span><span style="color:#34a853">l</span><span style="color:#ea4335">e</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-github">
                                        <label id="s-yandex" for="type-github" onclick="yandex()"><span style="color:#fc3f1d">Y</span><span style="color:#000">andex</span></label>
                                    </li>
                                    <li>
                                        <input hidden="" type="radio" name="type" id="type-mj">
                                        <label id="s-ask" for="type-mj" onclick="ask()"><span style="color:#464646">Ask</span></label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <form action="https://www.baidu.com/s?wd=" method="get" target="_blank" id="super-search-fm" autocomplete="off">
                            <input type="text" class="srk" id="search-text" placeholder="百度一下 你就知道" name="wd" autocomplete="off" autofocus="autofocus" style="outline:0;text-align: left;text-indent: 1em;">
                        </form>
                    </div>
                </div>
                <div style="height: 64px;"></div>
<?php
}
//注册界面html
function html_register(){
?>
            <div class="heiyeb"></div>
            <div class="ymzj">
                <div>
                    <span class="fonts24">注册</span><br/><br/>
                    <input type="text" id="name" placeholder="兽名" class="input-text" onblur="value=value.replace(/[^\u4E00-\u9FA5a-zA-Z0-9-_]/g,'')"><br/><br/>
                    <input type="text" id="user" placeholder="用户名" class="input-text" onblur="value=value.replace(/[^a-zA-Z0-9-_]/g,''),cx_user(this.value)"><br/><span id="return1"></span><br/>
                    <input type="password" id="pass" placeholder="密码" class="input-text" onkeyup="pass1(this.value)"><input type="hidden" id="val1"><br/><br/>
                    <input type="text" id="mail" placeholder="邮箱" class="input-text" onkeyup="value=value.replace(/[^0-9@qq.com]/g,''),cx_mail(this.value)" maxlength="20"><br/><span id="return2"></span><br/>
                    <div style="width: 256px;margin: auto;">
                        <input type="text" id="code" placeholder="验证码" class="input-text" onkeyup="value=value.replace(/[^0-9]/g,'')" maxlength="6" style="width: 47%;float: left;">
                        <input type="button" id="faso" value="发送验证码" class="input-text" onclick="fs_code(this)" style="background-color: #448EF6;color: #fff;width: 47%;float: right;" disabled>
                    </div><br/><br/><br/>
                    <input type="button" id="zhuc" value="注册" class="input-text" onclick="register(this)" style="background-color: #448EF6;color: #fff;"><br/><span id="return3"></span><br/>
                    <div class="xmx" style="height: 32px;"><a href="/login">已有账号登录</a></div>
                    <div class="xmx" style="color: #777;">*注册成功后，用户名与邮箱不可更改</div>
                </div>
            </div>
            <div class="heiyeb"></div>
<?php
}
//登录界面html
function html_login(){
?>
            <div class="heiyeb"></div>
            <div class="ymzj">
                <div>
                    <span class="fonts24">登录</span><br/><br/>
                    <input type="hidden" name="operate" id="operate">
                    <input type="text" id="fuid" placeholder="FurID / USER / MAIL" class="input-text" onblur="value=value.replace(/[^\u4E00-\u9FA5a-zA-Z0-9-_@.]/g,''),uid(this.value)" ><br/><br/>
                    <input type="password" id="pass" placeholder="密码" class="input-text" onkeyup="pass1(this.value)"><input type="hidden" id="val1"><br/><br/>
                    <input type="button" id="login" value="登录" class="input-text" style="background-color: #448EF6;color: #fff;" onclick="login(this)" ><br/>
                    <span id="return">&nbsp;</span><br/>
                    <div class="xmx">
                        <div style="float: left;"><a href="/register">新用户注册</a></div>
                        <div style="text-align: right;"><a href="/forget">忘记密码</a></div>
                    </div>
                </div>
            </div>
            <div style="height: 270px;"></div>
<?php
}
//忘记密码html
function html_forget(){
?>
            <div class="heiyeb"></div>
            <div class="ymzj">
                <div>
                    <span class="fonts24">忘记密码</span><br/><br/>
                    <input type="text" id="fuid" placeholder="FurID / USER / MAIL" class="input-text" onblur="value=value.replace(/[^\u4E00-\u9FA5a-zA-Z0-9-_@.]/g,''),notnull(this.value)" ><br/><br/>
                    <input type="password" id="pass" placeholder="新密码" class="input-text" onkeyup="pass1(this.value)"><input type="hidden" id="val1"><br/><br/>
                    <div style="width: 256px;margin: auto;">
                        <input type="text" id="code" placeholder="验证码" class="input-text" onkeyup="value=value.replace(/[^0-9]/g,'')" maxlength="6" style="width: 47%;float: left;">
                        <input type="button" id="faso" value="发送验证码" class="input-text" onclick="fs_codeval(this)" style="background-color: #448EF6;color: #fff;width: 47%;float: right;" disabled>
                    </div><br/><br/><br/>
                    <input type="button" id="zhuc" value="提交" class="input-text" onclick="forget(this)" style="background-color: #448EF6;color: #fff;"><br/><span id="return"></span><br/>
                    <div class="xmx">
                        <div style="float: left;"><a href="/register">新用户注册</a></div>
                        <div style="text-align: right;"><a href="/login">已有账号登录</a></div>
                    </div>
                </div>
            </div>
            <div style="height: 270px;"></div>
<?php
}
//修改密码html
function html_change(){
?>
            <div class="heiyeb"></div>
            <div class="ymzj">
                <div>
                    <form method="post">
                        <span class="fonts24">修改密码</span><br/><br/>
                        <input type="password" id="pass" placeholder="旧密码" class="input-text" onkeyup="pass1(this.value)"><input type="hidden" id="val1"><br/><br/>
                        <input type="password" id="pass" placeholder="新密码" class="input-text" onkeyup="pass2(this.value)"><input type="hidden" id="val2"><br/><br/>
                        <input type="button" id="chan" value="提交" class="input-text" onclick="change(this)" style="background-color: #448EF6;color: #fff;"><br/><span id="return"></span><br/>
                        <span id="return">&nbsp;</span><br/>
                    </form>
                </div>
            </div>
            <div style="height: 270px;"></div>
<?php
}
//提交网站html
function html_addto($val){
?>
            <div class="heiyeb"></div>
            <div class="ymzj">
                <div>
                    <form method="post">
                        <span class="fonts24">添加网站</span><br/><br/>
                        <input type="text" name="val1" placeholder="分类" class="input-text"><br/><br/>
                        <input type="text" name="val2" placeholder="标题" class="input-text"><br/><br/>
                        <input type="text" name="val3" placeholder="介绍" class="input-text"><br/><br/>
                        <input type="url"  name="val4" placeholder="链接" class="input-text"><br/><br/>
                        <input type="url"  name="val5" placeholder="图标" class="input-text"><br/><br/>
                        <input type="text"  name="val6" placeholder="验证码" class="input-text" style="max-width: 128px;">
                        <img src="/imgcode" style="border-radius: 4px;"><br/><br/>
                        <input type="submit" value="提交" class="input-text" style="background-color: #448EF6;color: #fff;"><br/>
                        <span id="return"><?php echo $val;?></span><br/>
                        <div class="bottomtxt">
                            *提交须知：提交后，经网站管理员审核，通过之后将会出现在公共主页上<br/>*填写完整通过几率较大<br/>*其他途径：可通过QQ联系网站管理员添加网站，联系方式：746515005
                        </div>
                    </form>
                </div>
            </div>
            <div class="heiyeb"></div>
<?php
}
//反馈网站html
function html_feedback($val){
?>
            <div class="heiyeb"></div>
            <div class="ymzj">
                <div>
                    <form method="post">
                        <span class="fonts24">反馈</span><br/><br/>
                        <textarea rows="5" name="val1" class="input-text" placeholder="Bug，建议... 都可以在此填写"></textarea><br/><br/>
                        <input type="mail" name="val2" placeholder="邮箱（选填，用于回复您的反馈）" class="input-text"><br/><br/>
                        <input type="text"  name="val3" placeholder="验证码" class="input-text" style="max-width: 128px;">
                        <img src="/imgcode" style="border-radius: 4px;"><br/><br/>
                        <input type="submit" value="提交" class="input-text" style="background-color: #448EF6;color: #fff;"><br/>
                        <span id="return"><?php echo $val;?></span><br/>
                        <div class="bottomtxt">
                            *其他途径：可通过QQ联系网站管理员反馈，联系方式：746515005
                        </div>
                    </form>
                </div>
            </div>
            <div class="heiyeb"></div>
<?php
}
//提示信息html
function html_a_login($tit,$url,$but){
echo '
    <div style="height: 256px;"></div>
    <p style="font-size: 50px;">'.$tit.'</p><br/>
    <a href="'.$url.'" style="background-color: #448EF6;color: #fff;padding: 10px;border-radius: 4px;">'.$but.'</a>
    <div style="height: 266px;"></div>
';
}
//使用帮助html
function html_help(){
    $gtim = date('Y-m-d H:i:s',time() + 600);
    $date = date('Y_m_d',time());
    $code = rand(111111,999999);
?>
            <p style="font-size: 50px;">使用帮助</p><br/>
            <p class="helptit"><a name="help1"><b>添加网址</b></a></p>
            <div class="gytit">
                在添加网址页面，填写相应信息，点击提交，待网站管理员审核通过后，将会出现在对应位置
                <br/>也可直接联系站长添加 <a href="/about" target="_blank" style="text-decoration: none;color: #1DA1F2;">点此进入</a>
                <br/><b>网站 提交示例：</b>
                <br/>分类：实用工具
                <br/>标题：狼介短址
                <br/>介绍：狼介短址 - 一个支持 链接、文本 以及 Markdown 的短链接
                <br/>链接：http://f0f.cc/
                <br/>图标：http://f0f.cc/favicon.png
                <br/>
                <br/><b>兽群 提交示例：</b>
                <br/>分类：兽群 或 兽群【重庆】（注：【】内填写地名）
                <br/>标题：群名（示例：FurDevsCommunityCN 小动物开发/运维交流群）
                <br/>介绍：群介绍（示例：欢迎来到小动物开发交流群，在这里你可以见到“群除我佬”的聚聚，可以和其他伙伴一起⌈愉快⌋交流…）
                <br/>链接：填写群号（自动生成加群链接）
                <br/>图标：[使用群头像作为图标]
                <br/>
                <br/><b>兽友 提交示例：</b>
                <br/>分类：扩列 或 扩列【重庆】（注：【】内填写地名）
                <br/>标题：您的圈名（示例：狼介）
                <br/>介绍：简短的自我介绍（示例：这里狼介w~蠢狼一只）
                <br/>链接：个人二维码内容（示例：https://qm.qq.com/cgi-bin/qm/qr?k=VrhyBED02tgcgV0sazOIoJPYhwKbArAF）可使用 <a href="https://cli.im/deqr" target="_blank" style="text-decoration: none;color: #1DA1F2;">草料二维码</a> 解码内容
                <br/>图标：QQ号[使用您的QQ头像作为图标]（示例：746515005）
                <br/>
                <br/><b>更多 提交类型：</b>
                <br/>进入反馈界面，<a href="/feedback" target="_blank" style="text-decoration: none;color: #1DA1F2;">点此进入</a>，填写您的建议，我们会尽快回复您
                <br/>或者也可用直接联系站长
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help2"><b>反馈问题</b></a></p>
            <div class="gytit">
                在这里你可以反馈问题，或提建议
                <br/>把你发现的 Bug 或建议提交，我们会尽快修复 或 追加新功能
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help3"><b>注册账号</b></a></p>
            <div class="gytit">
                注册账号入口在 登录 页面下方的 新用户注册 <a href="/register" target="_blank" style="text-decoration: none;color: #1DA1F2;">点此进入</a>
                <br/><b>注册 福瑞导航 账号，注意事项：</b>
                <br/>兽名：最长支持 16 个字符，注册<b>后允许修改</b>
                <br/>用户名：必须为 <b>英文字母数字和-_</b> 且 <b>不允许纯数字</b>，注册完成后<b>不可修改</b>
                <br/>密码：随意，没有限制，但不建议使用弱密码
                <br/>邮箱：暂时只支持QQ邮箱
                <br/>验证码：点击发送验证码，会往你的QQ邮箱发送邮件验证码（如下所示）
                <br/>&nbsp;
                <div style="background: #f5f8fa;">
                    <div class="gymal">
                        <div class="gyyjkd">
                            <br/>
                            <br/>
                            <div class="fonts24"><b>确认你的邮件地址</b></div>
                            <p>输入以下验证码确认你的邮箱地址。</p>
                            <p>以下验证码用于 <b>新用户注册</b>。</p>
                            <div style="font-size: 32px;"><b><?php echo $code?></b></div>
                            <p>验证码将在 <?php echo $gtim?> 过期。</p>
                            <p>谢谢，<br/></p><p>福瑞导航</p><br/>
                            <div style="text-align: center;font-size: 12px;color: #777;line-height: 20px;">如果您从未访问过 福瑞导航 请忽略此邮件<br/>这封邮件由系统自动生成，请勿回复</div>
                            <br/>
                            <br/>
                        </div>
                    </div>
                </div>
                <br/>输入你收到的验证码，如果无误，点击注册即可注册成功
                <br/>注册成功后，系统会给你发一条注册成功的邮件（如下所示）
                <br/>&nbsp;
                <div style="background: #f5f8fa;">
                    <div class="gymal">
                        <div class="gyyjkd">
                            <br/>
                            <br/>
                            <div class="fonts24"><b>wolf</b> <span class="fonts14">FurID : 1</span><br/><b><p>在 福瑞导航 注册成功</p></b></div>
                            <p class="fonts14"><b>位置</b>&nbsp;&nbsp;&nbsp;&nbsp;浙江杭州<br/><p class="fonts14"><b>设备</b>&nbsp;&nbsp;&nbsp;&nbsp;Windows 10</p></p>
                            <div class="huise">*位置系根据注册IP地址粗略确定。</div><br/>
                            <p style="line-height: 24px;"><b style="font-size: 16px;">如果是你本人的操作</b><br/><p>你可以忽略此消息，无需采取任何措施。</p>
                            <p style="line-height: 24px;"><b style="font-size: 16px;">如果这不是你本人操作</b><br/><p>请检查您的邮箱是否被盗用。</p>
                            <p><a href="/change" style="text-decoration: none;color: #1DA1F2;"><p>更改你的密码。</a> 更改密码后所有平台需重新登录。</p><br/>
                            <div style="text-align: center;font-size: 12px;color: #777;line-height: 20px;">这封邮件由系统自动生成，请勿回复</div>
                            <br/>
                            <br/>
                        </div>
                    </div>
                </div>
                <br/>此时你已经在登录界面，你可以使用 用户名 登录，也可以使用 邮箱 登录，还可以使用 FurID 登录（FurID 在注册成功的邮件里）
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help9"><b>关于登录</b></a></p>
            <div class="gytit">
                登录您的账号
                <br/>如果忘记了密码，不用担心，右下角有 找回密码 选项
                <br/>登录成功后，系统会发送登录成功邮件（如下所示）
                <br/>&nbsp;
                <div style="background: #f5f8fa;">
                    <div class="gymal">
                        <div class="gyyjkd">
                            <br/>
                            <br/>
                            <div class="fonts24"><b>wolf</b> <span class="fonts14">FurID : 1</span><br/><b><p>在 福瑞导航 登录成功</p></b></div>
                            <p class="fonts14"><b>位置</b>&nbsp;&nbsp;&nbsp;&nbsp;浙江杭州<br/><p class="fonts14"><b>设备</b>&nbsp;&nbsp;&nbsp;&nbsp;Windows 10</p></p>
                            <div class="huise">*位置系根据注册IP地址粗略确定。</div><br/>
                            <p style="line-height: 24px;"><b style="font-size: 16px;">如果是你本人的操作</b><br/><p>你可以忽略此消息，无需采取任何措施。</p>
                            <p style="line-height: 24px;"><b style="font-size: 16px;">如果这不是你本人操作</b><br/><p>请检查您的邮箱是否被盗用。</p>
                            <p><a href="/change" style="text-decoration: none;color: #1DA1F2;"><p>更改你的密码。</a> 更改密码后所有平台需重新登录。</p><br/>
                            <div style="text-align: center;font-size: 12px;color: #777;line-height: 20px;">这封邮件由系统自动生成，请勿回复</div>
                            <br/>
                            <br/>
                        </div>
                    </div>
                </div>
                <br/>登录有效期为 365 天，如果不手动退出、不清浏览器缓存 会一直保持登录状态
                <br/>若修改了密码，所有平台都需要重新登录
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help0"><b>关于账号</b></a></p>
            <div class="gytit">
                关于 FurID 的划分
                <br/>FurID 0 为公共账号，未登录打开主页显示为 FurID 0 的主页，仅管理员可操作
                <br/>FurID 1-99 为保留账号，为后续分类提供，仅管理员可操作（保留账号包含 分类用账号 与 管理员账号），允许普通用户申请管理员
                <br/>FurID 100+ 为普通用户账号，为普通用户所有，第一个用户的 FurID 为 100，第二个用户的 FurID 为 101 ，以此类推
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help4"><b>忘记密码</b></a></p>
            <div class="gytit">
                输入您的 FurID 或 用户名 或 邮箱
                <br/>填写新的密码
                <br/>发送验证码，会往你的QQ邮箱发送邮件验证码（如下所示）
                <br/>&nbsp;
                <div style="background: #f5f8fa;">
                    <div class="gymal">
                        <div class="gyyjkd">
                            <br/>
                            <br/>
                            <div class="fonts24"><b>确认你的邮件地址</b></div>
                            <p>输入以下验证码确认你的邮箱地址。</p>
                            <p>以下验证码用于 <b>修改密码</b>。</p>
                            <div style="font-size: 32px;"><b><?php echo $code?></b></div>
                            <p>验证码将在 <?php echo $gtim?> 过期。</p>
                            <p>谢谢，<br/></p><p>福瑞导航</p><br/>
                            <div style="text-align: center;font-size: 12px;color: #777;line-height: 20px;">如果您从未访问过 福瑞导航 请忽略此邮件<br/>这封邮件由系统自动生成，请勿回复</div>
                            <br/>
                            <br/>
                        </div>
                    </div>
                </div>
                <br/>填写验证码
                <br/>提交！若无误即可更新密码
                <br/>更新密码之后，所有登录此账号的平台都需要重新登录
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help5"><b>个人设置</b></a></p>
            <div class="gytit">
                在这里 你可以修改你的 <b>兽名</b>，可以设置你的主页
                <br/>主页搜索框：可以1设置是否显示搜索框
                <br/>主页书签：可以设置主页书签显示组合
                <br/>公开书签：可以设置你的是否对他人可见（开启后，可以方便的共享你的主页，并且无需登录，且只能访问，无法对你的账号操作）
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help6"><b>网址管理</b></a></p>
            <div class="gytit">
                在这里 你可以设置你的 <b>书签内容</b>
                <br/>添加网站：在上方的输入框内输入对应的字符（允许为空）所有都是选填项
                <br/>修改记录：在上方的输入框内输入对应的字符（允许为空）序号为必填项
                <br/>删除记录：只需填写对应序号，允许多选（例如：1 或 1-10）
                <br/>导入书签：选择 浏览器导出的html书签（一般为 bookmarks_<?php echo $date;?>.html） 单击导入书签，即可根据你书签的内容导入
                <br/>导出CSV：将书签管理器里的链接通过CSV格式导出，支持使用 Excel 打开
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help7"><b>操作记录</b></a></p>
            <div class="gytit">
                可以查看你的账号操作记录
                <br/>发现可疑记录可以及时修改密码
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help8"><b>修改密码</b></a></p>
            <div class="gytit">
                可以修改你的密码
                <br/>输入旧密码
                <br/>再输入新密码
                <br/>提交！
                <br/>修改完密码后，所有登录的平台都需要重新登录
                <br/>如果好巧不巧你忘记了刚刚设置的密码，这边还有 忘记密码选项
                <br/>
                <br/>
            </div>
            <p class="helptit"><a name="help8"><b>添加帮助</b></a></p>
            <div class="gytit">
                添加帮助内容
                <br/>在 反馈页面 中提交你遇到的问题
                <br/>如果哪条帮助写的不明白，也可以反馈给我们修改
                <br/>
                <br/>
            </div>
            
            <div style="position: fixed;background: #222831;bottom: 0px;width: 100%;padding: 10px;font-size:14px;left: 0px;">
                <a href="#help1" style="color: #9ca0ad;">添加网址</a>&nbsp;
                <a href="#help2" style="color: #9ca0ad;">反馈问题</a>&nbsp;
                <a href="#help3" style="color: #9ca0ad;">注册账号</a>&nbsp;
                <a href="#help9" style="color: #9ca0ad;">关于登录</a>&nbsp;
                <a href="#help0" style="color: #9ca0ad;">关于账号</a><br/>
                <a href="#help4" style="color: #9ca0ad;">忘记密码</a>&nbsp;
                <a href="#help5" style="color: #9ca0ad;">个人设置</a>&nbsp;
                <a href="#help6" style="color: #9ca0ad;">网址管理</a>&nbsp;
                <a href="#help7" style="color: #9ca0ad;">操作记录</a>&nbsp;
                <a href="#help8" style="color: #9ca0ad;">修改密码</a>
            </div>
<?php
}
//关于本站html
function html_about(){
?>
            <p style="font-size: 50px;">关于本站</p><br/>
            <?php out_bookmark(1);?>
            <p class="helptit"><b>QQ二维码</b></p>
            <img src="/qqcode" style="width: 400px;height: auto;" class="card tooltip tooltip-bottom ">
            <p class="helptit"><b>本站友链</b></p>
                <div style="margin-left: 3em;">标题：福瑞导航
                <br/>地址：https://furry.vin/
                <br/>图标：https://furry.vin/favicon.png
                <br/>简介：Furry文化主题网址及信息汇聚导航
                </div>
            <p class="helptit"><b>申请管理员</b></p>
            <div class="gytit">管理分类内容，请与本狼联系，联系方式见上方</div>
            <p class="helptit"><b>开发人员</b></p>
            <div class="gytit">本站从无到有，由狼介一狼独立完成开发</div>
            <p class="helptit"><b>安全性</b></p>
            <div class="gytit">使用cookie判断用户是否登录</div>
            <p class="helptit"><b>关于密码</b></p>
            <div class="gytit">注册/登录时均使用密文传输，即使密码使用：123456，在后端还会进行加盐哈希加密，存储在数据库中均为长度为64位的加盐密文，并且绝对不会重复，就算是网站管理员也不会知道你的明文密码，但这不意味着你可以随意使用弱密码，即使有加密也招不住暴力破解</div>
            <p class="helptit"><b>关于隐私</b></p>
            <div class="gytit">未注册用户仅记录IP，注册用户仅记录QQ及IP，仅用于发送验证码、获取头像、判断请求是否合法</div>
            <p class="helptit"><b>关于收录</b></p>
            <div class="gytit">分主动收录（站长找的）与被动收录（用户提交），按照收录时间排序，仅收录，不对其网站内容负责，请自行甄别</div>
            <p class="helptit"><b>其他</b></p>
            <div class="gytit">由于站长的前端实属拉跨，这里借用了 Typecho 下 Splity 主题的部分代码（仅顶部和底部），网站后端使用的语言是 PHP，绝大多数是站长写的，该项目从2022年2月28日开始，到2022年3月30日，完成后端功能及简单的前端，咕了一个月，在2022年4月25日开始重构前后端代码，在这咕了一个月期间还重构了另一个项目：<a href="https://github.com/WOLF4096/wolf4096-short_link" target="_blank" style="text-decoration: none;color: #1DA1F2;">狼介短址</a></div>
            
<?php
}

?>
