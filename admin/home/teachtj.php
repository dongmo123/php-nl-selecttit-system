<?php
    session_start();//开启session会话
    //验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    require("./public/error.php");
    header("Content-Type:text/html;charset=utf8");
    date_default_timezone_set("PRC");//设置中国时区
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
        <title>教师选题工作统计</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position">
                    <span>您的位置:选题管理</span>
                    <a href="teachtj.php" target="iframe">>>教师选题工作统计<span style="color:red;">&nbsp;&nbsp;本页面统计数据量较大，请耐心等待!</span></a>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <!-- 打印当前页 -->
                <script>
                    function myPrint(obj){
                        document.getElementsByClassName('biaoge')[0].border='1';
                        var newWindow=window.open("打印窗口","_blank");
                        var docStr = obj.innerHTML;
                        newWindow.document.write(docStr);
                        newWindow.document.close();
                        newWindow.print();
                        newWindow.close();
                        document.getElementsByClassName('biaoge')[0].border='0';
                    }
                </script>
                <button class="text-but2" style="position:absolute;top:2px;left:450px;" onclick="myPrint(document.getElementById('content'))">打 印</button>
                <!-- 打印当前页 -->
                <div class="search">
                    <div>
                        <span style="font-size:14px;font-weight:bold;">快捷通道: &nbsp;&nbsp;&nbsp;</span>
                        <a href="student.php" target="iframe" class="link3" style="">-学生信息-</a>
                        <a href="allstuinf.php" target="iframe" class="link3" style="">-学生选题列表-</a>
                        <a href="selecttitle.php" target="iframe" class="link3" style="">-选题信息-</a>
                    </div>
                    <div></div>
                </div>
                <div id="content" name="content">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
                        <tr class="top">
                            <th>序号</th>
                            <th>教师编号</th>
                            <th>教师姓名</th>
                            <th>职称</th>
                            <th>所在系</th>
                            <th>拟题个数</th>
                            <th>预设带领人数</th>
                            <th>有效选题个数</th>
                            <th>指导学生人数</th>
                        </tr>
                        <?php
                            //统计教师总数,只有启用的用户有效
                            $sql_teach="select ID from teacher where state=0 order by teachnum";
                            $result_teach=mysqli_query($link,$sql_teach);
                            $countteach=mysqli_num_rows($result_teach);
                            echo '<tr class="middle">';
                            echo '<td colspan="5">共有教师'.$countteach.'人</td>';
                            //统计拟题总数,只统计通过审核的
                            $sql_protit="select ID,numlimit from selecttitle where pass=1";
                            $result_protit=mysqli_query($link,$sql_protit);
                            $countprotit=mysqli_num_rows($result_protit);
                            echo '<td>'.$countprotit.'</td>';
                            //预设带领学生人数，就是把教师的限带人数相加
                            $countstu=0;
                            while($row_teach=mysqli_fetch_array($result_teach)){
                                $countstu+=$row_teach['leadnum'];
                            }
                            echo '<td>'.$countstu.'</td>';
                            //有效选题个数统计，也就是被确选的选题
                            $sql_contit="select ID from selecttitle where confirm=1 and pass=1";
                            $result_contit=mysqli_query($link,$sql_contit);
                            $countcontit=mysqli_num_rows($result_contit);
                            echo '<td>'.$countcontit.'</td>';
                            //指导学生人数统计，这里算的是已确选学生数量
                            $sql_finaltit="select ID from finalresult";
                            $result_finaltit=mysqli_query($link,$sql_finaltit);
                            $countfinaltit=mysqli_num_rows($result_finaltit);
                            echo '<td>'.$countfinaltit.'</td>';
                            echo '</tr>';
                            //==========================================================
                            //下面统计各教师的选题情况，有多少个教师就有多少条数据统计，只统计启用的教师用户数据
                            //调出每个教师用户数据
                            $sql_teach="select teachnum,teachname,profession,position,leadnum from teacher where state=0 order by profession,teachnum";
                            $result_teach=mysqli_query($link,$sql_teach);
                            $num=0;
                            while($row_teach=mysqli_fetch_array($result_teach)){
                                echo '<tr class="middle">';
                                $num+=1;
                                echo '<td>'.$num.'</td>';
                                echo '<td><a href="teacher.php?teachnum='.$row_teach['teachnum'].'" title="点击查询教师信息">'.$row_teach['teachnum'].'</a></td>';
                                echo '<td>'.$row_teach['teachname'].'</td>';
                                echo '<td>'.$row_teach['profession'].'</td>';
                                echo '<td>'.$row_teach['position'].'</td>';
                                //教师拟题个数，只算通过审核的
                                $sql_jsnt="select ID from selecttitle where teachnum='{$row_teach['teachnum']}' and pass=1";
                                $result_jsnt=mysqli_query($link,$sql_jsnt);
                                $countjsnt=mysqli_num_rows($result_jsnt);
                                echo '<td>'.$countjsnt.'</td>';
                                //限带人数
                                echo '<td>'.$row_teach['leadnum'].'</td>';
                                //有效选题个数统计
                                $sql_yxxt="select ID from selecttitle where teachnum='{$row_teach['teachnum']}' and pass=1 and confirm=1";
                                $result_yxxt=mysqli_query($link,$sql_yxxt);
                                $countyxxt=mysqli_num_rows($result_yxxt);
                                echo '<td>'.$countyxxt.'</td>';
                                //指导学生人数
                                $sql_zdxs="select ID from finalresult where teachnum='{$row_teach['teachnum']}'";
                                $result_zdxs=mysqli_query($link,$sql_zdxs);
                                $countzdxs=mysqli_num_rows($result_zdxs);
                                echo '<td>'.$countzdxs.'</td>';
                                echo '</tr>';
                            }
                            echo '<tr class="middle">';
                            echo '<td style="color:red;" rowspan="">统计说明</td>';
                            echo '<td colspan="10" rowspan="" style="text-align:left;">
                                    <span>1.只统计账号<a href="teacher.php?state=0">启用的教师用户</a></span><br>
                                    <span>2.拟题个数：只统计<a href="selecttitle.php?pass=1">通过审核的选题</a></span><br>
                                    <span>3.预设带领人数：教师的<a href="teacher.php?state=0">限带学生人数</a></span><br>
                                    <span>4.有效选题个数：指的是<a href="selecttitle.php?pass=1&confirm=1">已被确选的选题</a></span><br>
                                    <span>5.指导学生人数统计：指的是选题为该教师的<a href="allstuinf.php?confirm=finalresult">已确选学生</a>人数</span><br>
                                </td>';
                            echo '</tr>';
                        ?>
                    </table>
                    <?php
                        //6.释放结果集并关闭数据库
                        mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>