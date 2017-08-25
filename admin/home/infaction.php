<?php
    require("./public/error.php");
require("./public/config.php");
$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());
mysqli_select_db($link,DBNAME);
mysqli_set_charset($link,"utf8");
switch($_GET['type']){
    case "insert":
        $code = $_POST['code'];
        $name = $_POST['name'];
        $sql = "insert into teachinf (ID,code,name,time) values(null,'{$code}','{$name}',now())";
        mysqli_query($link,$sql);
        if(mysqli_insert_id($link)>0){
                echo "<script type='text/javascript'>window.onload=function(){update('添加成功！！！');history.go(-1);}</script>";
            }else{
                echo "<script type='text/javascript'>window.onload=function(){update('添加失败~~~');history.go(-1);}</script>";
            }
        break;
    case "insertpro":
        $code = $_POST['code'];
        $name = $_POST['name'];
        $depart = $_POST['depart'];
        $college = $_POST['college'];
        $type = $_POST['type'];
        $sql = "insert into proinf (ID,code,name,depart,college,type,time) values(null,'{$code}','{$name}','{$depart}','{$college}','{$type}',now())";
        mysqli_query($link,$sql);
        if(mysqli_insert_id($link)>0){
                echo "<script type='text/javascript'>window.onload=function(){update('添加成功！！！');history.go(-1);}</script>";
            }else{
                echo "<script type='text/javascript'>window.onload=function(){update('添加失败~~~');history.go(-1);}</script>";
            }
        break;
    case "inserttit":
        $code = $_POST['code'];
        $name = $_POST['name'];
        $sql = "insert into titinf (ID,code,name,time) values(null,'{$code}','{$name}',now())";
        mysqli_query($link,$sql);
        if(mysqli_insert_id($link)>0){
                echo "<script type='text/javascript'>window.onload=function(){update('添加成功！！！');history.go(-1);}</script>";
            }else{
                echo "<script type='text/javascript'>window.onload=function(){update('添加失败~~~');history.go(-1);}</script>";
            }
        break;
    case "update":
        $code = $_POST['code'];
        $name = $_POST['name'];
        $sql = "update teachinf set code='{$code}',name='{$name}',time=now() where id={$_GET['id']}";
        mysqli_query($link,$sql);
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据成功!');history.go(-2);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据失败~~~');history.go(-1);}</script>";
        }
        break;
    case "updatepro":
        $code = $_POST['code'];
        $name = $_POST['name'];
        $depart = $_POST['depart'];
        $college = $_POST['college'];
        $type = $_POST['type'];
        $sql = "update proinf set code='{$code}',name='{$name}',depart='{$depart}',college='{$college}',type='{$type}',time=now() where id={$_GET['id']}";
        mysqli_query($link,$sql);
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据成功!');history.go(-2);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据失败~~~');history.go(-1);}</script>";
        }
        break;
    case "updatetit":
        $code = $_POST['code'];
        $name = $_POST['name'];
        $sql = "update titinf set code='{$code}',name='{$name}',time=now() where id={$_GET['id']}";
        mysqli_query($link,$sql);
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据成功!');history.go(-2);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据失败~~~');history.go(-1);}</script>";
        }
        break;
    case "delete":  //执行删除操作
        //定义删除SQL语句
        $sql = "delete from teachinf where id=".($_GET['id']+0);
        //执行SQL语句
        mysqli_query($link,$sql);
        //通过影响的行数来判断成功与否
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据失败~~~');history.go(-1);}</script>";
        }
        break;
    case "deletepro":  //执行删除操作
        //定义删除SQL语句
        $sql = "delete from proinf where id=".($_GET['id']+0);
        //执行SQL语句
        mysqli_query($link,$sql);
        //通过影响的行数来判断成功与否
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据失败~~~');history.go(-1);}</script>";
        }
        break;
    case "deletetit":  //执行删除操作
        //定义删除SQL语句
        $sql = "delete from titinf where id=".($_GET['id']+0);
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
mysqli_close($link);
?>
<!doctype html>
<html>
    <head>
        <title>编辑</title>
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
        },5000);
    }
</script>