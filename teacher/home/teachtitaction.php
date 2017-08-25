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
    case "insertupdata":
        //查出该选题所有信息,并插入到确选表里
        $sql = "select * from preresult where ID='{$_GET['id']}'";
        $result = mysqli_query($link,$sql);
        $row = mysqli_fetch_array($result);
        $teachnum=$row['teachnum'];
        $titname=$row['titname'];
        $stunum=$row['stunum'];
        $sql = "insert into finalresult (ID,titname,stunum,teachnum,time) values(null,'{$titname}','{$stunum}','{$teachnum}',now())";
        mysqli_query($link,$sql);
        if(mysqli_insert_id($link)>0){
            /*将选题表selecttitle中的该选题确选confirm=1*/
            $sql_sel = "update selecttitle set confirm=1 where titname='{$row['titname']}'";
            //执行SQL语句
            mysqli_query($link,$sql_sel);
            /*将预选表中的所有该学生有关的信息删除*/
            $sql_del = "delete from preresult where stunum='{$row['stunum']}'";
            //执行SQL语句
            mysqli_query($link,$sql_del);
            /*查选题限选人数是否达上限，是则删除该选题名称的所有信息，反之不删除*/
            $sqltit="select numlimit from selecttitle where titname='{$row['titname']}'";
            $resulttit=mysqli_query($link,$sqltit);
            $rowtit=mysqli_fetch_array($resulttit);
            $sqlf="select ID from finalresult where titname='{$row['titname']}'";
            $resultf=mysqli_query($link,$sqlf);
            $countf=mysqli_num_rows($resultf);
            if($rowtit['numlimit']==$countf){
                $sql_fdel = "delete from preresult where titname='{$row['titname']}'";
                //执行SQL语句
                mysqli_query($link,$sql_fdel);
            }

            echo "<script type='text/javascript'>window.onload=function(){update('确选成功！');history.go(-1);}</script>";
        }else{
            echo "<script type='text/javascript'>window.onload=function(){update('确选失败~~~');history.go(-1);}</script>";
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