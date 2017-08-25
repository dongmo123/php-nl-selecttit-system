<?php
require("./public/error.php");
if(!empty($_GET['submit'])) {
    $link=$_GET['stunum'];
    if($_GET['submit']=="查学生"){
        header("Location:student.php?stunum=".$link);
    }elseif($_GET['submit']=="查选题"){
        header("Location:allstuinf.php?confirm=finalresult&stunum=".$link);
    }

}else{
    echo "<script type='text/javascript'>window.onload=function(){alert('查询错误！请重新操作');history.go(-1);}</script>";
}
?>