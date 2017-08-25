<?php
    session_start();//开启session会话
    //验证用户有没有登陆
    if(!$_SESSION['stunum']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    error_reporting(E_ALL & ~E_NOTICE);
    header("Content-Type:text/html;charset=utf8");
    //1.导入配置文件
    require("./public/config.php");

    //2.连接mysqli,并判断是否连接成功过
    $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");

    //3.选择连接数据库并配置字符集
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
    $sql_stu = "select stuname from student where stunum='{$_SESSION['stunum']}'";
    $result_stu = mysqli_query($link,$sql_stu);
    $row_stu=mysqli_fetch_array($result_stu);
?>
<!doctype html>
<html>
    <head>
        <title>添加留言</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position2">
                    <span>您的位置:留言>></span>
                    <a href="message.php" target="iframe">查看留言</a>
                    <span>>添加留言</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div id="addinfo">
                    <a href="addmess.php" target="iframe" class="add2">添加留言</a>
                </div>
                <div id="content" name="content">
                    <form action="messaction.php?type=insert" method="post" id="jsForm">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
                        <tr>
                            <td>留言人：</td>
                            <td>
                                <input type="hidden" name="usertype" value="学生">
                                <input type="hidden" name="usernum" value="<?php echo $_SESSION['stunum'];?>">
                                <input type="hidden" name="username" value="<?php echo $row_stu['stuname'];?>">
                                <div class="text-word2"><?php echo $row_stu['stuname'];?></div>
                            </td>
                        </tr>
                        <tr>
                            <td>留言内容：</td>
                            <td>
                                <textarea name="content" placeholder="请填写留言,不得超过50个字,注意文明用语!" maxlength="50" style="width:60%;height:60px;" required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>留给：</td>
                            <td>
                                <input type="hidden" name="targetuser" value="<?php echo $_GET['usertype'].$_GET['targetuser'];?>" style="width:120px;" class="text-word2">
                                <div class="text-word2"><?php echo $_GET['usertype'].$_GET['targetuser'];?></div>
                            </td>
                        </tr>
                        <tr >
                            <td></td>
                            <td>
                                <input name="" type="submit" value="添  加" class="text-but2">
                                <input name="" type="reset" value="重  置" class="text-but2">
                            </td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<!-- 引用jquery.validate表单验证框架 -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/jquery.zzcheck.js"></script>