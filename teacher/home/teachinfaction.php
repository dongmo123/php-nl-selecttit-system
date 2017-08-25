<?php
session_start();
if(!$_SESSION['teachnum']){
    header("Location:../../index.php");
        exit();
}
require("./public/config.php");
require("./public/error.php");
$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());
mysqli_select_db($link,DBNAME);
mysqli_set_charset($link,"utf8");
switch($_GET['type']){
    case "insert":
        $titname = $_POST['titname'];
        $teachnum = $_SESSION['teachnum'];
        $difcode = $_POST['difcode'];
        $direction = $_POST['direction'];
        $procode = $_POST['procode'];
        $numlimit = $_POST['numlimit'];
        $remark = $_POST['remark'];
        $confirm = 0;
        //chaxun  shifou  yao  shenhe
        $sqlpass="select titpass from department";
        $resultpass=mysqli_query($link,$sqlpass);
        $rowpass=mysqli_fetch_array($resultpass);
        $pass=$rowpass['titpass'];
        //4.1.3拼装SQL语句并发送执行
        $sql = "insert into selecttitle (ID,titname,teachnum,difcode,direction,procode,numlimit,remark,confirm,pass,time) values(null,'{$titname}','{$teachnum}','{$difcode}','{$direction}','{$procode}','{$numlimit}','{$remark}','{$confirm}','{$pass}',now())";
        mysqli_query($link,$sql);
        if(mysqli_insert_id($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('添加成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('添加失败~~~');history.go(-1);}</script>";
        }
        break;
    case "myinfupdate":
        $teachnum = $_POST['teachnum'];
        $teachname = $_POST['teachname'];
        $sex = $_POST['sex'];
        $password = md5($_POST['password']);
        $position = $_POST['position'];
        $profession = $_POST['profession'];
        $leadnum = $_POST['leadnum'];
        $tel = $_POST['tel'];
        $qq = $_POST['qq'];
        $email = $_POST['email'];
        //拼装SQL语句
        $sql = "update teacher set teachnum='{$teachnum}',teachname='{$teachname}',sex='{$sex}',password='{$password}',position='{$position}',profession='{$profession}',leadnum='{$leadnum}',tel='{$tel}',qq='{$qq}',email='{$email}',time=now() where id='{$_GET['id']}'";
        //执行SQL语句
        mysqli_query($link,$sql);
        //根据影响的行数来判断是否更新成功
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据成功!');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据失败~~~');history.go(-1);}</script>";
        }
        break;
    case "updateall":
        $titname = $_POST['titname'];
        $procode = $_POST['procode'];
        $direction = $_POST['direction'];
        $difcode = $_POST['difcode'];
        $numlimit = $_POST['numlimit'];
        $remark = $_POST['remark'];
        //拼装SQL语句
        $sql = "update selecttitle set titname='{$titname}',procode='{$procode}',direction='{$direction}',difcode='{$difcode}',numlimit='{$numlimit}',remark='{$remark}',time=now() where id='{$_GET['id']}'";
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
        $sql = "delete from selecttitle where ID='{$_GET['id']}'";
        //执行SQL语句
        mysqli_query($link,$sql);
        //通过影响的行数来判断成功与否
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据失败~~~');history.go(-1);}</script>";
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