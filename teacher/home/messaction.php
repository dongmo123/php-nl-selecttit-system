<?php
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
        $usertype = $_POST['usertype'];
        $username = $_POST['username'];
        $content = $_POST['content'];
        $targetuser = $_POST['targetuser'];
        $usernum = $_POST['usernum'];
        //4.1.3拼装SQL语句并发送执行
        $sql = "insert into message (ID,usernum,username,usertype,content,time,targetuser,pass) values(null,'{$usernum}','{$username}','{$usertype}','{$content}',now(),'{$targetuser}',1)";
        mysqli_query($link,$sql);

        //4.1.4判断添加是否成功
        if(mysqli_insert_id($link)>0){
                echo "<script type='text/javascript'>window.onload=function(){update('添加成功！！！');history.go(-2);}</script>";
            }else{
                echo "<script type='text/javascript'>window.onload=function(){update('添加失败~~~');history.go(-1);}</script>";
            }
        break;

    case "delete":  //执行删除操作
        //定义删除SQL语句
        $sql = "delete from message where id='{$_GET['id']}'";
        //执行SQL语句
        mysqli_query($link,$sql);
        //通过影响的行数来判断成功与否
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('删除留言成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('删除留言失败~~~');history.go(-1);}</script>";
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