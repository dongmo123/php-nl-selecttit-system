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
?>
<!doctype html>
<html>
    <head>
        <title>学生信息管理</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position">
                    <span>您的位置:留言建议</span>
                    <a href="message.php" target="iframe">>>查看留言</a>
                    <span>>我的留言</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div id="addinfo">
                    <a href="addmess.php" target="iframe" class="add2">添加留言</a>&nbsp;&nbsp;&nbsp;<span style="color:red;">审核不合格和待审核的留言不会显示在公共留言平台</span>
                </div>
                <div id="content" name="content">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
                        <tr class="top">
                            <th>序号</th>
                            <th>留言人</th>
                            <th>用户类型</th>
                            <th>内容</th>
                            <th>时间</th>
                            <th>留给</th>
                            <th>审核状态</th>
                            <th>操作</th>
                        </tr>
                        <?php
                            date_default_timezone_set("PRC");//设置中国时区

                            $sql = "select * from message where usernum='{$_SESSION['stunum']}' order by pass";
                            $result = mysqli_query($link,$sql);

                            //5.执行遍历解析输出操作
                            $num=0;
                            $zpass=array(0=>"待审核",1=>"通过",2=>"不合格");
                            if(mysqli_num_rows($result)){
                                while($row = mysqli_fetch_assoc($result)){
                                        $num++;
                                        echo '<tr class="middle">';
                                        echo '<td>'.$num.'</td>';
                                        echo '<td>'.$row['username'].'</td>';
                                        echo '<td>'.$row['usertype'].'</td>';
                                        echo '<td>'.$row['content'].'</td>';
                                        echo '<td>'.$row['time'].'</td>';
                                        echo '<td>'.$row['targetuser'].'</td>';
                                        echo '<td>'.$zpass[$row['pass']].'</td>';
                                        echo '<td>
                                            <a href="javascript:dodel('.$row["ID"].')" target="" class="link red">删除</a>
                                        </td>';
                                        echo '</tr>';
                                }
                            }else{
                                echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="13">没有找到此信息</td></tr>';
                            }
                        ?>
                    </table>
                    <?php
                        //6.释放结果集并关闭数据库
                        mysqli_free_result($result);
                        mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function dodel(id){
        if(confirm ("确认删除吗？")){
            window.location="messaction.php?type=delete&id="+id;
        }
    }
</script>