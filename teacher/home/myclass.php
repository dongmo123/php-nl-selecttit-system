<?php
    session_start();
    if(!$_SESSION['teachnum']){
        header("Location:../../index.php");
            exit();
    }
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!doctype html>
<html>
    <head>
        <title>我的选题</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position2">
                    <span>您的位置:个人中心>></span>
                    <a href="myclass.php" target="iframe">我的课题</a>
                    <span>>查看我的课题</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div id="hotinf">
                    操作流程：①认真填写个人信息-->②导师添加选题(限时)-->③等待审核通过-->④导师确选学生选题-->⑤导师给学生发布毕设任务书并指导学生完成毕业设计
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
                <button class="text-but2" style="position:absolute;top:60px;left:20px;" onclick="myPrint(document.getElementById('content'))">打 印</button>
                <!-- 打印当前页 -->
                <div id="addinfo">
                    <a href="addmyclass.php" target="iframe" class="add2">新增选题</a>
                </div>
                <div id="content" name="content">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
                        <tr class="top">
                            <th>序号</th>
                            <th>我的课题名称</th>
                            <th>限选专业</th>
                            <th>选题方向</th>
                            <th>选题难度</th>
                            <th>限选人数</th>
                            <th>已选人数</th>
                            <th class="remark">课题要求</th>
                            <th>发布/修改时间</th>
                            <th>审核</th>
                            <th>是否确选</th>
                            <th>操作</th>
                        </tr>
                        <?php
                            require("./public/config.php");
                            $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
                            mysqli_select_db($link,DBNAME);
                            mysqli_set_charset($link,"utf8");
                            //定义数组选择器
                            $confirm = array(0=>"否",1=>"是");
                            $pass = array(0=>"等待",1=>"PASS",2=>"不合格");
                            $dif = array(level01=>"较容易",level02=>"中等",level03=>"较难",level04=>"很难");

                            $sql = "select * from selecttitle where teachnum='{$_SESSION['teachnum']}' order by procode desc,pass,time desc";
                            $result = mysqli_query($link,$sql);
                            if(mysqli_num_rows($result)){
                                $num=0;
                                while($row = mysqli_fetch_array($result)){
                                    $num++;
                                    echo '<tr class="middle">';
                                    echo '<td>'.$num.'</td>';
                                    echo '<td>'.$row['titname'].'</td>';
                                    //根据procode专业代码,查proinf查出专业名字
                                    $sql2 = "select name from proinf where code='{$row['procode']}'";
                                    $result2 = mysqli_query($link,$sql2);
                                    $row2 = mysqli_fetch_array($result2);
                                    echo '<td>'.$row2['name'].'</td>';
                                    echo '<td>'.$row['direction'].'</td>';
                                    echo '<td>'.$dif[$row['difcode']].'</td>';
                                    echo '<td>'.$row['numlimit'].'</td>';
                                    //已选人数统计,先调取终选结果表,查看是否已经确选了
                                    //计数-预选人数加确选人数,有限选多人的情况,若其中一人已确选,还剩余几个名额--
                                    $knum=0;
                                    $sql_countID = "select count(ID) from finalresult where titname='{$row["titname"]}'";
                                    $result_countID = mysqli_query($link,$sql_countID);
                                    $row_countID = mysqli_fetch_array($result_countID);
                                    $knum+=$row_countID[0];
                                    //调取预选结果表,查看是否已经选择了
                                    $sql_preCountID = "select count(ID) from preresult where titname='{$row["titname"]}'";
                                    $result_preCountID = mysqli_query($link,$sql_preCountID);
                                    $row_preCountID = mysqli_fetch_array($result_preCountID);
                                    $knum+=$row_preCountID[0];
                                    echo '<td>'.$knum.'</td>';
                                    echo '<td  class="remark" title="'.$row['remark'].'">'.$row['remark'].'</td>';
                                    echo '<td>'.$row['time'].'</td>';
                                    if($row['pass']==0){
                                        echo '<td>'.$pass[$row['pass']].'</td>';
                                    }elseif($row['pass']==1){
                                        echo '<td style="color:#40B827;font-weight:bold;">'.$pass[$row['pass']].'</td>';
                                    }else{
                                        echo '<td style="color:red;">'.$pass[$row['pass']].'</td>';
                                    }
                                    if($confirm[$row['confirm']]=='是'){
                                        echo '<td style="color:red;">'.$confirm[$row['confirm']].'</td>';
                                    }else{
                                        echo '<td>'.$confirm[$row['confirm']].'</td>';
                                    }
                                    if($row['pass']==1){
                                        echo '<td><a href="editmyclass.php?id='.$row['ID'].'" target="iframe" class="link">查看/编辑</a></td>';
                                    }else{
                                        echo '<td>
                                                <a href="editmyclass.php?id='.$row['ID'].'" target="iframe" class="link">查看/编辑</a>
                                                    <span class="gray">&nbsp;|&nbsp;</span>
                                                <a href="javascript:dodel('.$row["ID"].')" target="" class="link red">删除</a>
                                            </td>';
                                    }
                                }
                            }else{
                                echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="12">导师您还没有提交选题</td></tr>';
                            }
                            mysqli_close($link);
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function dodel(id){
        if(confirm ("确认删除吗？")){
            window.location="teachinfaction.php?type=delete&id="+id;
        }
    }
</script>
<!-- 引用jquery.validate表单验证框架 -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/jquery.zzcheck.js"></script>
<script src="js/jquery.hot.js"></script>