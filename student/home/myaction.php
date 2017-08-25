<?php
session_start();
if(!$_SESSION['stunum']){
    header("Location:../../index.php");
        exit();
}
require("./public/error.php");
require("./public/config.php");
$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());
mysqli_select_db($link,DBNAME);
mysqli_set_charset($link,"utf8");
switch($_GET['type']){
    case "insert":
        $stunum = $_SESSION['stunum'];
        $sql = "select * from selecttitle where ID=".$_GET['id'];
        $result = mysqli_query($link,$sql);
        $row = mysqli_fetch_array($result);
        //4.1.3拼装SQL语句并发送执行
        $sql = "insert into preresult (ID,stunum,titname,teachnum,time) values(null,'{$stunum}','{$row['titname']}','{$row['teachnum']}',now())";
        mysqli_query($link,$sql);
        if(mysqli_insert_id($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('选题成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('选题失败~~~');history.go(-1);}</script>";
        }
        break;
    case "myinfupdate":
        $stunum = $_POST['stunum'];
        $stuname = $_POST['stuname'];
        $sex = $_POST['sex'];
        $password = md5($_POST['password']);
        $college = $_POST['college'];
        $profession = $_POST['profession'];
        $class = $_POST['class'];
        $tel = $_POST['tel'];
        $qq = $_POST['qq'];
        $email = $_POST['email'];
        $year = $_POST['year'];
        $ident = $_POST['ident'];
        $address = $_POST['address'];
        //拼装SQL语句
        $sql = "update student set stuname='{$stuname}',sex='{$sex}',password='{$password}',college='{$college}',profession='{$profession}',class='{$class}',tel='{$tel}',qq='{$qq}',email='{$email}',year='{$year}',time=now(),ident='{$ident}',address='{$address}' where id={$_GET['id']}";
        //执行SQL语句
        mysqli_query($link,$sql);
        //根据影响的行数来判断是否更新成功
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据成功!');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据失败~~~');history.go(-1);}</script>";
        }
        break;

    case "delete":  //执行删除操作
        //定义删除SQL语句
        $sql = "delete from preresult where titname='{$_GET['titname']}' and stunum='{$_SESSION['stunum']}'";
        //执行SQL语句
        mysqli_query($link,$sql);
        //通过影响的行数来判断成功与否
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('退选成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('退选失败~~~请重新操作');history.go(-1);}</script>";
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