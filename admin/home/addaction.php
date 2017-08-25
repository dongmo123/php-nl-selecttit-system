<?php
//1.导入数据库配置文件
require("./public/config.php");
require("./public/error.php");

//2.连接mysqli数据库并检测是否连接成功
$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());

//3.选择数据库并设置字符集
mysqli_select_db($link,DBNAME);
mysqli_set_charset($link,"utf8");

//4.根据参数'type'的值来进行对应的操作
switch($_GET['type']){
    case "insert":  //执行添加操作
        //4.1.1获取要添加的信息
        $adnumber = $_POST['adnumber'];
        $adname = $_POST['adname'];
        $password = md5($_POST['password']);
        $repassword = $_POST['repassword'];
        $sex = $_POST['sex'];
        $tel = $_POST['tel'];
        $qq = $_POST['qq'];
        $email = $_POST['email'];
        $state = 1;
        $time = time();
        //4.1.3拼装SQL语句并发送执行
        $sql = "insert into admin (ID,adnumber,adname,password,sex,tel,qq,email,state,time) values(null,'{$adnumber}','{$adname}','{$password}','{$sex}','{$tel}','{$qq}','{$email}','{$state}',now())";
        mysqli_query($link,$sql);

        //4.1.4判断添加是否成功
        if(mysqli_insert_id($link)>0){
                echo "<script type='text/javascript'>window.onload=function(){update('添加成功！！！');history.go(-2);}</script>";
            }else{
                echo "<script type='text/javascript'>window.onload=function(){update('添加失败~~~');history.go(-1);}</script>";
            }
        break;

    case "update":  //执行修改操作
        //获取修改信息，并且要获取对应修改的id号

        $adnumber = $_POST['adnumber'];
        $adname = $_POST['adname'];
        $sex = $_POST['sex'];
        $tel = $_POST['tel'];
        $qq = $_POST['qq'];
        $email = $_POST['email'];
        $state = $_POST['state'];

        //拼装SQL语句
        $sql = "update admin set state='{$state}',adnumber='{$adnumber}',adname='{$adname}',sex='{$sex}',tel='{$tel}',qq='{$qq}',email='{$email}',time=now() where id={$_GET['id']}";
        //执行SQL语句
        mysqli_query($link,$sql);
        //根据影响的行数来判断是否更新成功
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据成功!');history.go(-2);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据失败~~~');history.go(-1);}</script>";
        }
        break;

    case "delete":  //执行删除操作
        //定义删除SQL语句
        $sql = "delete from admin where id=".($_GET['id']+0);
        //执行SQL语句
        mysqli_query($link,$sql);
        //通过影响的行数来判断成功与否
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据失败~~~');history.go(-1);}</script>";
        }
        break;
    case "repassword":  //执行重置密码
        //获取新密码数据

        $id = $_POST['id'];

        $password = md5($_POST['password']);//新密码
        $admin = ($_POST['pastpass']);//与超级密码匹配
        $pastpass = md5($_POST['pastpass']);//旧密码
        //判断原始密码与数据库存储的密码是否一致
        //设施SQL语句，并执行操作
        $sql = "select * from admin where id=".($id+0);
        $res2 = mysqli_query($link,$sql);
        if($res2){
            $prove = mysqli_fetch_assoc($res2);
        }else{
            die("对不起，没有找到您要修改的数据。非常抱歉");
        }
        //原始密码与数据库密码的匹配,并设置超级权限密码
        if($pastpass == $prove['password'] || $admin=='admin'){
            //判断两次密码是否一致
            if($_POST['repassword'] !== $_POST['password']){
                echo "<script type='text/javascript'>window.onload=function(){update('新设密码和重复密码不相符，请您重新输入，谢谢。');history.go(-1);}</script>";
            }
            //拼装SQL语句并执行操作
            mysqli_query($link,"UPDATE admin SET password='{$password}',time=now() WHERE id='{$id}'");
            //根据影响的行数来判断,密码是否更改成功。

            if(mysqli_affected_rows($link)>0){
                 echo "<script type='text/javascript'>window.onload=function(){update('修改密码成功！！！');history.go(-1);}</script>";
            }else{
                echo "<script type='text/javascript'>window.onload=function(){update('密码修改失败~~~');history.go(-1);}</script>";
            }
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('原始密码不相符！请重新输入~~~');history.go(-1);}</script>";
        }
        break;
    case "initial":
        $sql = "update admin set password='admin',time=now() where id=".($_GET['id']+0);
        $res1 = mysqli_query($link,$sql);
        if($res1){
            echo "<script type='text/javascript'>window.onload=function(){update('成功重置为初始密码!!');history.go(-2);}</script>";
        }else{
            die("对不起，没有找到您要修改的数据。非常抱歉");
        }
        break;
}

//5.关闭数据库
mysqli_close($link);
?>
<!doctype html>
<html>
    <head>
        <title>编辑用户</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div id="al">

                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function update(word){
        var al=document.getElementById('al');
        al.innerHTML=word;
        setTimeout(function(){
            al.innerHTML='';
        },10000);
    }
</script>