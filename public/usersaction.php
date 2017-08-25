<?php
header("Content-Type:text/html;charset=utf8");
session_start();   //开启session会话跟踪
 require("./error.php");
//会员信息操作：【登陆验证】和【退出操作】
//根据index.php传值参数element，进行对应的操作
switch($_GET['element']){
    case "welcome":   //执行登陆验证
        if($_POST['onlycode']!==$_SESSION['turecode']){
            echo "<script>alert('验证码不正确');history.go(-1);</script>";
                exit();
        }
        //执行账号密码验证
        $usernumber = $_POST['usernumber'];
        $password = $_POST['password'];
        //----Mysql数据库操作------------
        //1.导入配置文件
        require("./config.php");
        //2.连接Mysql数据库并进行是否连接成功判断
        $link = @mysqli_connect(HOST,USER,PASSWORD) or die("对不起，数据库连接失败！");
        //3.选择数据库并设置字符集
        mysqli_select_db($link,DBNAME);
        mysqli_set_charset($link,"utf8");
        //4.定义查询SQL语句(用户从表单中输入的值，从数据库中可以匹配到)
        $queryad=mysqli_query($link,"select * from admin where adnumber='{$usernumber}'");
        $querystu=mysqli_query($link,"select * from student where stunum='{$usernumber}'");
        $queryteach=mysqli_query($link,"select * from teacher where teachnum='{$usernumber}'");
        //获取用户登录信息,$user以一个数组的形式存在
        //$user = mysqli_fetch_assoc($result);
        $rowad = mysqli_fetch_array($queryad);
        $rowteach = mysqli_fetch_array($queryteach);
        $rowstu = mysqli_fetch_array($querystu);
        //5.判断是否查询到结果
        //关闭数据库
        //mysqli_close();
        if($rowad||$rowteach||$rowstu){
            //echo "<script>alert('11111111!');history.go(-1);</script>";
            //===================计数登陆模块=========================================
            //1导入配置文件
            require("./config.php");
            //2连接数据库并判断是否连接成功
            $link = @mysqli_connect(HOST,USER,PASSWORD) or die("对不起，数据库连接失败");
            //3连接库并设置字符集
            mysqli_select_db($link,DBNAME);
            mysqli_set_charset($link,"utf8");
            $sql = "select * from number where id=1";
            $result = mysqli_query($link,$sql);
            while($row=mysqli_fetch_assoc($result)){
                $num = $row['num'];
            }
            //用户类型判断
            if($rowad){
                if($rowad['state']==1){
                    if($rowad['password']==$password||$rowad['password']==md5($password)){
                        //计数判断
                       $num = $num+1 ;
                       $sqls= "update number set num= {$num},time=now() where id=1";//将登陆自加后写入数据库
                       $results = mysqli_query($link,$sqls);
                        //*此处表示登录成功*
                        ////将整个会员信息放置到session中
                        session_start();
                        $_SESSION['adnumber'] = $rowad['adnumber'];
                        $_SESSION['adname'] = $rowad['adname'];
                        header("Location:../admin/index.php");
                    }else{
                        echo "<script>alert('您的密码错了!');history.go(-1);</script>";
                        exit();
                    }
                }else{
                    echo "<script>alert('您的账号被禁用了!');history.go(-1);</script>";
                    exit();
                }

            }elseif($rowteach){
                if($rowteach['state']==0){
                    if($rowteach['password']==$password||$rowteach['password']==md5($password)){
                        //计数判断
                       $num = $num+1 ;
                       $sqls= "update number set num= {$num} where id=1";//将登陆自加后写入数据库
                       $results = mysqli_query($link,$sqls);
                        //*此处表示登录成功*
                        ////将整个会员信息放置到session中
                        session_start();
                        $_SESSION['teachnum'] = $rowteach['teachnum'];
                        $_SESSION['teachname'] = $rowteach['teachname'];
                        header("Location:../teacher/index.php");
                    }else{
                        echo "<script>alert('您填写的密码错误!');history.go(-1);</script>";
                        exit();
                    }
                }else{
                    echo "<script>alert('您的账号被禁用了!');history.go(-1);</script>";
                    exit();
                }

            }elseif($rowstu){
                if($rowstu['state']==0){
                    if($rowstu['password'] == $password || $rowstu['password'] == md5($password)){
                        //计数判断
                       $num = $num+1 ;
                       $sqls= "update number set num= {$num} where id=1";//将登陆自加后写入数据库
                       $results = mysqli_query($link,$sqls);
                       //查询系统是否想学生开放
                       $sql_open="select name,dispark from department";
                       $result_open = mysqli_query($link,$sql_open);
                       $row_open=mysqli_fetch_array($result_open);
                       if($row_open['dispark']=="1"){
                            if($row_open['name']!=$rowstu['college']){
                                echo "<script>alert('非系统面向学院学生，禁止登录！');history.go(-1);</script>";
                                exit;
                            }
                           //*此处表示登录成功*
                            ////将整个会员信息放置到session中
                            session_start();
                            $_SESSION['stunum'] = $rowstu['stunum'];
                            $_SESSION['stuname'] = $rowstu['stuname'];
                            header("Location:../student/index.php");
                       }else{
                            echo "<script>alert('系统尚未开放!请等待');history.go(-1);</script>";
                       }

                    }else{
                        echo "<script>alert('同学你的密码写错了!');history.go(-1);</script>";
                        exit();
                    }

                }else{
                    echo "<script>alert('您的账号被禁用了!');history.go(-1);</script>";
                    exit();
                }
            }
        }
        else
        {
            echo "<script>alert('用户错误或不存在');history.go(-1);</script>";
            exit();
        }
        break;
    case "thanksadmin"://执行退出操作
        //销毁登录信息
        unset($_SESSION['adnumber']);
        unset($_SESSION['adname']);
        session_destroy();

        header("Location:../index.php");
        break;
    case "thanksstu"://执行退出操作
        //销毁登录信息
        unset($_SESSION['stunum']);
        unset($_SESSION['stuname']);
        session_destroy();

        header("Location:../index.php");
        break;
    case "thanksteach"://执行退出操作
        //销毁登录信息
        unset($_SESSION['teachnum']);
        unset($_SESSION['teachname']);
        session_destroy();

        header("Location:../index.php");
        break;
}
?>