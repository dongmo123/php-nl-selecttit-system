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
        <title>选题情况统计</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position">
                    <span>您的位置:选题管理</span>
                    <a href="tongji.php" target="iframe">>>选题情况统计<span style="color:red;">&nbsp;&nbsp;本页面统计数据量较大，请耐心等待!</span></a>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <!-- 打印当前页 -->
                <script>
                    function myPrint(obj){
                        document.getElementsByClassName('biaoge')[0].border='1';
                        document.getElementsByClassName('biaoge')[1].border='1';
                        var newWindow=window.open("打印窗口","_blank");
                        var docStr = obj.innerHTML;
                        newWindow.document.write(docStr);
                        newWindow.document.close();
                        newWindow.print();
                        newWindow.close();
                        document.getElementsByClassName('biaoge')[0].border='0';
                        document.getElementsByClassName('biaoge')[1].border='0';
                    }
                </script>
                <button class="text-but2" style="position:absolute;top:2px;left:400px;" onclick="myPrint(document.getElementById('content'))">打 印</button>
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
                            <th>选题人数统计</th>
                            <th>总人数</th>
                            <th>已确选人数</th>
                            <th>未确选人数</th>
                            <th>未选题人数</th>
                            <th>已确选学生名单</th>
                            <th>未确选学生名单</th>
                            <th>仍未选题学生名单</th>
                        </tr>
                        <?php
                            $sql_dep = "select name from department limit 1";
                            $result_dep = mysqli_query($link,$sql_dep);
                            $row_dep = mysqli_fetch_assoc($result_dep);
                            echo '<tr class="middle">';
                            echo '<td>'.$row_dep['name'].'</td>';
                            $sql_stu = "select stunum,stuname from student where college='{$row_dep['name']}'";
                            $result_stu = mysqli_query($link,$sql_stu);
                            $numstu = mysqli_num_rows($result_stu);
                            echo '<td>'.$numstu.'</td>';
                            $stuarray=array();
                            $pstuarray=array();
                            $fstuarray=array();
                            $wnum=0;
                            $fnum=0;
                            $pnum=0;
                            if(count($result_stu)>0){
                                while($row_stu = mysqli_fetch_assoc($result_stu)){
                                    //将搜出的学生学号带入终选表
                                    $sql_zf = "select stunum from finalresult where stunum='{$row_stu['stunum']}'";
                                    $result_zf = mysqli_query($link,$sql_zf);
                                    $row_zf = mysqli_fetch_assoc($result_zf);
                                    if(count($row_zf)>0){
                                        $fnum+=1;
                                        $fstuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                    }else{
                                        //将搜出的学生学号带入终选表
                                        $sql_zp = "select stunum from preresult where stunum='{$row_stu['stunum']}' group by stunum";
                                        $result_zp = mysqli_query($link,$sql_zp);
                                        $row_zp = mysqli_fetch_assoc($result_zp);
                                        if(count($row_zp)>0){
                                            $pnum+=1;
                                            $pstuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                        }else{
                                            $stuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                            $wnum+=1;
                                        }
                                    }
                                }
                            }
                            echo '<td>'.$fnum.'</td>';
                            echo '<td>'.$pnum.'</td>';
                            echo '<td>'.$wnum.'</td>';
                            //-------------------------------------已确选名单
                            echo '<td><form action="linkaction.php" method="get" accept-charset="utf-8">
                                <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                <option value=""> 查 看 名 单</option>';
                            for($i=0;$i<count($fstuarray);$i++){
                                echo $fstuarray[$i];
                            }
                            echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub">&nbsp;<input type="submit" name="submit" value="查选题" class="but-sub2"></form></td>';
                            //-------------------------------------未确选
                            echo '<td><form action="linkaction2.php" method="get" accept-charset="utf-8">
                                <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                <option value=""> 查 看 名 单</option>';
                            for($i=0;$i<count($pstuarray);$i++){
                                echo $pstuarray[$i];
                            }
                            echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub">&nbsp;<input type="submit" name="submit" value="查选题" class="but-sub2"></form></td>';
                            //-------------------------------------还未选
                            echo '<td><form action="student.php" method="get" accept-charset="utf-8">
                                <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                <option value=""> 查 看 名 单</option>';
                            for($i=0;$i<count($stuarray);$i++){
                                echo $stuarray[$i];
                            }
                            echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub"></form></td>';
                            echo '</tr>';

                            //==============================================
                            //各专业统计
                            //==============================================
                            $pre=array();
                            $sql_dep = "select profession from student group by profession";
                            $result_dep = mysqli_query($link,$sql_dep);
                            if(count($result_dep)>0){
                                while($row_dep= mysqli_fetch_assoc($result_dep)){
                                    echo '<tr class="middle">';
                                    echo '<td>'.$row_dep['profession'].'</td>';
                                    $sql_stu = "select stunum,stuname from student where profession='{$row_dep['profession']}'";
                                    $result_stu = mysqli_query($link,$sql_stu);
                                    $numstu = mysqli_num_rows($result_stu);
                                    echo '<td>'.$numstu.'</td>';
                                    $stuarray=array();
                                    $pstuarray=array();
                                    $fstuarray=array();
                                    $wnum=0;
                                    $fnum=0;
                                    $pnum=0;
                                    if(count($result_stu)>0){
                                        while($row_stu = mysqli_fetch_assoc($result_stu)){
                                            //将搜出的学生学号带入终选表
                                            $sql_zf = "select stunum from finalresult where stunum='{$row_stu['stunum']}'";
                                            $result_zf = mysqli_query($link,$sql_zf);
                                            $row_zf = mysqli_fetch_assoc($result_zf);
                                            if(count($row_zf)>0){
                                                $fnum+=1;
                                                $fstuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                            }else{
                                                //将搜出的学生学号带入终选表
                                                $sql_zp = "select stunum from preresult where stunum='{$row_stu['stunum']}' group by stunum";
                                                $result_zp = mysqli_query($link,$sql_zp);
                                                $row_zp = mysqli_fetch_assoc($result_zp);
                                                if(count($row_zp)>0){
                                                    $pnum+=1;
                                                    $pstuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                                }else{
                                                    $stuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                                    $wnum+=1;
                                                }
                                            }
                                        }
                                    }
                                    echo '<td>'.$fnum.'</td>';
                                    echo '<td>'.$pnum.'</td>';
                                    echo '<td>'.$wnum.'</td>';
                                    //-------------------------------------
                                    echo '<td><form action="linkaction.php" method="get" accept-charset="utf-8">
                                        <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                        <option value=""> 查 看 名 单</option>';
                                    for($i=0;$i<count($fstuarray);$i++){
                                        echo $fstuarray[$i];

                                    }
                                    echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub">&nbsp;<input type="submit" name="submit" value="查选题" class="but-sub2"></form></td>';
                                    //-------------------------------------
                                    echo '<td><form action="linkaction2.php" method="get" accept-charset="utf-8">
                                        <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                        <option value=""> 查 看 名 单</option>';
                                    for($i=0;$i<count($pstuarray);$i++){
                                        echo $pstuarray[$i];
                                    }
                                    echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub">&nbsp;<input type="submit" name="submit" value="查选题" class="but-sub2"></form></td>';
                                    //-------------------------------------
                                    echo '<td><form action="student.php" method="get" accept-charset="utf-8">
                                        <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                        <option value=""> 查 看 名 单</option>';
                                    for($i=0;$i<count($stuarray);$i++){
                                        echo $stuarray[$i];
                                    }
                                    echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub"></form></td>';
                                    echo '</tr>';
                                }
                            }
                            //==============================================
                            //年级选题统计
                            //==============================================
                            $pre=array();
                            $sql_dep = "select year from student group by year";
                            $result_dep = mysqli_query($link,$sql_dep);
                            if(count($result_dep)>0){
                                while($row_dep= mysqli_fetch_assoc($result_dep)){
                                    echo '<tr class="middle">';
                                    echo '<td>'.$row_dep['year'].'年级'.'</td>';
                                    $sql_stu = "select stunum,stuname from student where year='{$row_dep['year']}'";
                                    $result_stu = mysqli_query($link,$sql_stu);
                                    $numstu = mysqli_num_rows($result_stu);
                                    echo '<td>'.$numstu.'</td>';
                                    $stuarray=array();
                                    $pstuarray=array();
                                    $fstuarray=array();
                                    $wnum=0;
                                    $fnum=0;
                                    $pnum=0;
                                    if(count($result_stu)>0){
                                        while($row_stu = mysqli_fetch_assoc($result_stu)){
                                            //将搜出的学生学号带入终选表
                                            $sql_zf = "select stunum from finalresult where stunum='{$row_stu['stunum']}'";
                                            $result_zf = mysqli_query($link,$sql_zf);
                                            $row_zf = mysqli_fetch_assoc($result_zf);
                                            if(count($row_zf)>0){
                                                $fnum+=1;
                                                $fstuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                            }else{
                                                //将搜出的学生学号带入终选表
                                                $sql_zp = "select stunum from preresult where stunum='{$row_stu['stunum']}' group by stunum";
                                                $result_zp = mysqli_query($link,$sql_zp);
                                                $row_zp = mysqli_fetch_assoc($result_zp);
                                                if(count($row_zp)>0){
                                                    $pnum+=1;
                                                    $pstuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                                }else{
                                                    $stuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                                    $wnum+=1;
                                                }
                                            }
                                        }
                                    }
                                    echo '<td>'.$fnum.'</td>';
                                    echo '<td>'.$pnum.'</td>';
                                    echo '<td>'.$wnum.'</td>';
                                    //-------------------------------------
                                    echo '<td><form action="linkaction.php" method="get" accept-charset="utf-8">
                                        <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                        <option value=""> 查 看 名 单</option>';
                                    for($i=0;$i<count($fstuarray);$i++){
                                        echo $fstuarray[$i];

                                    }
                                    echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub">&nbsp;<input type="submit" name="submit" value="查选题" class="but-sub2"></form></td>';
                                    //-------------------------------------
                                    echo '<td><form action="linkaction2.php" method="get" accept-charset="utf-8">
                                        <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                        <option value=""> 查 看 名 单</option>';
                                    for($i=0;$i<count($pstuarray);$i++){
                                        echo $pstuarray[$i];
                                    }
                                    echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub">&nbsp;<input type="submit" name="submit" value="查选题" class="but-sub2"></form></td>';
                                    //-------------------------------------
                                    echo '<td><form action="student.php" method="get" accept-charset="utf-8">
                                        <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                        <option value=""> 查 看 名 单</option>';
                                    for($i=0;$i<count($stuarray);$i++){
                                        echo $stuarray[$i];
                                    }
                                    echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub"></form></td>';
                                    echo '</tr>';
                                }
                            }
                            //==============================================
                            //各专业的各班级统计
                            //==============================================
                            $pre=array();
                            $sql_dep = "select profession from student group by profession";
                            $result_dep = mysqli_query($link,$sql_dep);
                            if(count($result_dep)>0){
                                while($row_dep= mysqli_fetch_assoc($result_dep)){
                                    $sql_class = "select class from student where profession='{$row_dep['profession']}' group by class";
                                    $result_class = mysqli_query($link,$sql_class);
                                    if(count($result_class)>0){

                                        while($row_class= mysqli_fetch_assoc($result_class)){
                                            echo '<tr class="middle">';
                                            echo '<td>'.$row_dep['profession'].$row_class['class'].'</td>';
                                            $sql_stu = "select stunum,stuname from student where profession='{$row_dep['profession']}' and class='{$row_class['class']}'";
                                            $result_stu = mysqli_query($link,$sql_stu);
                                            $numstu = mysqli_num_rows($result_stu);
                                            echo '<td>'.$numstu.'</td>';
                                            $stuarray=array();
                                            $pstuarray=array();
                                            $fstuarray=array();
                                            $wnum=0;
                                            $fnum=0;
                                            $pnum=0;
                                            if(count($result_stu)>0){
                                                while($row_stu = mysqli_fetch_assoc($result_stu)){
                                                    //将搜出的学生学号带入终选表
                                                    $sql_zf = "select stunum from finalresult where stunum='{$row_stu['stunum']}'";
                                                    $result_zf = mysqli_query($link,$sql_zf);
                                                    $row_zf = mysqli_fetch_assoc($result_zf);
                                                    if(count($row_zf)>0){
                                                        $fnum+=1;
                                                        $fstuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                                    }else{
                                                        //将搜出的学生学号带入终选表
                                                        $sql_zp = "select stunum from preresult where stunum='{$row_stu['stunum']}' group by stunum";
                                                        $result_zp = mysqli_query($link,$sql_zp);
                                                        $row_zp = mysqli_fetch_assoc($result_zp);
                                                        if(count($row_zp)>0){
                                                            $pnum+=1;
                                                            $pstuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                                        }else{
                                                            $stuarray[]='<option value="'.$row_stu['stunum'].'">'.$row_stu['stunum'].$row_stu['stuname'].'</option>';
                                                            $wnum+=1;
                                                        }
                                                    }
                                                }
                                            }
                                            echo '<td>'.$fnum.'</td>';
                                            echo '<td>'.$pnum.'</td>';
                                            echo '<td>'.$wnum.'</td>';
                                            //-------------------------------------
                                            echo '<td><form action="linkaction.php" method="get" accept-charset="utf-8">
                                                <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                                <option value=""> 查 看 名 单</option>';
                                            for($i=0;$i<count($fstuarray);$i++){
                                                echo $fstuarray[$i];

                                            }
                                            echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub">&nbsp;<input type="submit" name="submit" value="查选题" class="but-sub2"></form></td>';
                                            //-------------------------------------
                                            echo '<td><form action="linkaction2.php" method="get" accept-charset="utf-8">
                                                <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                                <option value=""> 查 看 名 单</option>';
                                            for($i=0;$i<count($pstuarray);$i++){
                                                echo $pstuarray[$i];
                                            }
                                            echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub">&nbsp;<input type="submit" name="submit" value="查选题" class="but-sub2"></form></td>';
                                            //-------------------------------------
                                            echo '<td><form action="student.php" method="get" accept-charset="utf-8">
                                                <select name="stunum" class="text-word" style="margin:0;width:150px;">
                                                <option value=""> 查 看 名 单</option>';
                                            for($i=0;$i<count($stuarray);$i++){
                                                echo $stuarray[$i];
                                            }
                                            echo '</select>&nbsp;&nbsp;<input type="submit" name="submit" value="查学生" class="but-sub"></form></td>';
                                            echo '</tr>';
                                        }
                                    }

                                }
                            }

                        ?>
                    </table>
                    <table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
                        <tr class="top">
                            <th>选题方向</th>
                            <th>总选题数</th>
                            <th>工程设计</th>
                            <th>技术开发</th>
                            <th>软件工程</th>
                            <th>理论研究和方法应用</th>
                            <th>管理模式设计</th>
                            <th>其他</th>
                        </tr>
                        <?php
                            //统计总选题数
                            $sql_titnum = "select direction from selecttitle where pass=1";
                            $result_titnum = mysqli_query($link,$sql_titnum);
                            $counttit = mysqli_num_rows($result_titnum);
                            //查数据库选题表有各种方向
                            $dir1=0;
                            $dir2=0;
                            $dir3=0;
                            $dir4=0;
                            $dir5=0;
                            $dir6=0;
                            while($row_titnum= mysqli_fetch_array($result_titnum)){
                                if($row_titnum['direction']=='工程设计'){
                                    $dir1+=1;
                                }
                                if($row_titnum['direction']=='技术开发'){
                                    $dir2+=1;
                                }
                                if($row_titnum['direction']=='软件工程'||$row_titnum['direction']=='软件开发'){
                                    $dir3+=1;
                                }
                                if($row_titnum['direction']=='理论研究'){
                                    $dir4+=1;
                                }
                                if($row_titnum['direction']=='管理模式'){
                                    $dir5+=1;
                                }
                                if($row_titnum['direction']=='其他'){
                                    $dir6+=1;
                                }
                            }
                            echo '<tr class="middle">';
                            echo '<td>选题方向统计</td>';
                            echo '<td>'.$counttit.'</td>';
                            echo '<td>'.$dir1.'</td>';
                            echo '<td>'.$dir2.'</td>';
                            echo '<td>'.$dir3.'</td>';
                            echo '<td>'.$dir4.'</td>';
                            echo '<td>'.$dir5.'</td>';
                            echo '<td>'.$dir6.'</td>';
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