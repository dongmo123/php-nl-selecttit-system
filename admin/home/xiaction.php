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
        $code = $_POST['code'];
        $xiname = $_POST['xiname'];
        $head = $_POST['head'];
        $tel = $_POST['tel'];
        $qq = $_POST['qq'];
        //4.1.3拼装SQL语句并发送执行
        $sql = "insert into xiset (ID,code,xiname,head,tel,qq,time) values(null,'{$code}','{$xiname}','{$head}','{$tel}','{$qq}',now())";
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
        $code = $_POST['code'];
        $xiname = $_POST['xiname'];
        $head = $_POST['head'];
        $tel = $_POST['tel'];
        $qq = $_POST['qq'];
        //拼装SQL语句
        $sql = "update xiset set code='{$code}',xiname='{$xiname}',head='{$head}',tel='{$tel}',qq='{$qq}',time=now() where id={$_GET['id']}";
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
        $sql = "delete from xiset where id=".($_GET['id']+0);
        //执行SQL语句
        mysqli_query($link,$sql);
        //通过影响的行数来判断成功与否
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据成功！！！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('删除数据失败~~~');history.go(-1);}</script>";
        }
        break;


    case "departset":
        $name = $_POST['name'];
        $year = $_POST['year'];
        $dispark = $_POST['dispark'];
        $permit = $_POST['permit'];
        $pass=$_POST['pass'];
        $titpass=$_POST['titpass'];
        $sql = "update department set dispark='{$dispark}',name='{$name}',year='{$year}',permit='{$permit}',pass='{$pass}',titpass='{$titpass}',time=now() where id=1";
        //执行SQL语句
        mysqli_query($link,$sql);
        //根据影响的行数来判断是否更新成功
        if(mysqli_affected_rows($link)>0){
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据成功!');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('更新数据失败~~~');history.go(-1);}</script>";
        }
        break;
}

//5.关闭数据库
mysqli_close($link);
?>
<!doctype html>
<html>
    <head>
        <title>编辑系别</title>
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