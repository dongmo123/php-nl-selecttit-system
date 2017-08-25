<?php
    session_start();
    if(!$_SESSION['stunum']){
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
        <style>
            #mytit{
                text-align:center;
                line-height:40px;
                font-size:16px;
            }
            #success{
                color:#36D60A;
                font-weight:bold;
                font-size:14px;
                background:url('imgs/success.png') no-repeat left center;
                background-size:30px 30px;
                padding-left:35px;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position2">
                    <span>您的位置:</span>
                    <a href="myinf.php" target="iframe">个人中心</a>
                    <a href="mytit.php" target="iframe">>>我的选题</a>
                    <span>>查看我的选题</span>
                    &nbsp;&nbsp;<span style="color:red;font-size:14px;">请及时自行截图或打印保存选题信息！</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <!-- 打印当前页 -->
                <script>
                    function myPrint(obj){
                        var newWindow=window.open("打印窗口","_blank");
                        var docStr = obj.innerHTML;
                        newWindow.document.write(docStr);
                        newWindow.document.close();
                        newWindow.print();
                        newWindow.close();
                    }
                </script>
                <button class="text-but2" style="position:absolute;top:0px;left:520px;height:26px;" onclick="myPrint(document.getElementById('content'))">打 印</button>
                <!-- 打印当前页 -->
                <div id="content" name="content">
                        <?php
                            require("./public/config.php");
                            $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
                            mysqli_select_db($link,DBNAME);
                            mysqli_set_charset($link,"utf8");
                            //定义数组选择器
                            $confirm = array(0=>"否",1=>"是");
                            $dif = array(level01=>"较容易",level02=>"中等",level03=>"较难",level04=>"很难");
                            //调取确选结果表,查看自己是否已经被确选了
                            $sql_succ = "select * from finalresult where stunum='{$_SESSION['stunum']}'";
                            $result_succ = mysqli_query($link,$sql_succ);
                            /*echo mysqli_num_rows($result_succ);*/
                            if(mysqli_num_rows($result_succ)>0){
                                echo '<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">';
                                $row_succ = mysqli_fetch_array($result_succ);
                                echo '<tr>
                                        <td colspan="2" rowspan="" id="mytit">我 的 选 题</td>
                                    </tr>';
                                echo '<tr>
                                        <td>终选序号</td><td><input type="text" value="'.$row_succ['ID'].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>我的选题名称</td><td><input type="text" value="'.$row_succ['titname'].'" class="text-word2" disabled></td>
                                    </tr>';
                                //根据导师编号,查teacher表提取信息
                                $sql_f1 = "select teachname,tel,qq,email from teacher where teachnum='{$row_succ['teachnum']}'";
                                $result_f1 = mysqli_query($link,$sql_f1);
                                $row_f1 = mysqli_fetch_array($result_f1);

                                echo '<tr>
                                        <td>指导老师</td><td><input type="text" value="'.$row_f1['teachname'].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>导师电话</td><td><input type="text" value="'.$row_f1['tel'].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>导师qq</td><td><input type="text" value="'.$row_f1['qq'].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>导师邮箱</td><td><input type="text" value="'.$row_f1['email'].'" class="text-word2" disabled></td>
                                    </tr>';
                                //用 选题名称titname,调用proinf数据库
                                $sql3 = "select * from selecttitle where titname='{$row_succ['titname']}'";
                                $result3 = mysqli_query($link,$sql3);
                                $row3 = mysqli_fetch_assoc($result3);
                                //根据procode专业代码,查proinf查出专业名字
                                $sql2 = "select name from proinf where code='{$row3['procode']}'";
                                $result2 = mysqli_query($link,$sql2);
                                $row2 = mysqli_fetch_array($result2);

                                echo '<tr>
                                        <td>限选专业</td><td><input type="text" value="'.$row2['name'].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>选题方向</td><td><input type="text" value="'.$row3['direction'].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>选题难度</td><td><input type="text" value="'.$dif[$row3['difcode']].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>限选人数</td><td><input type="text" value="'.$row3['numlimit'].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>选题要求</td><td><textarea class="text-word2" name="remark" title="'.$row3['remark'].'" disabled placeholder="无">'.$row3['remark'].'</textarea></td>
                                    </tr>';
                                echo '<tr>
                                        <td>确选时间</td><td><input type="text" value="'.$row_succ['time'].'" class="text-word2" disabled></td>
                                    </tr>';
                                echo '<tr>
                                        <td>状态</td><td id="success">你已被确选!!!</td>
                                    </tr>';
                            }else{
                                echo '<div id="hotinf">
                                        选题流程：①认真填写个人信息-->②浏览选题-->③选择自己感兴趣的选题-->④等待导师确选-->⑤确选后联系指导老师完成毕业设计
                                    </div>';
                                echo '<table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
                                        <tr class="top">
                                            <th>预选序号</th>
                                            <th>我的预选选题名称</th>
                                            <th>指导老师</th>
                                            <th>导师电话</th>
                                            <th>导师qq</th>
                                            <th>限选专业</th>
                                            <th>选题方向</th>
                                            <th>选题难度</th>
                                            <th>限选人数</th>
                                            <th>已选人数</th>
                                            <th>是否确选</th>
                                        </tr>';
                                $sql = "select * from preresult where stunum='{$_SESSION['stunum']}'";
                                $result = mysqli_query($link,$sql);
                                if(mysqli_num_rows($result)){
                                    $num=0;
                                    while($row = mysqli_fetch_array($result)){
                                        $num++;
                                        echo '<tr class="middle">';
                                        echo '<td>'.$num.'</td>';
                                        echo '<td>'.$row['titname'].'</td>';
                                        //根据导师编号,查teacher表提取信息
                                        $sql1 = "select teachname,tel,qq from teacher where teachnum='{$row['teachnum']}'";
                                        $result1 = mysqli_query($link,$sql1);
                                        $row1 = mysqli_fetch_array($result1);
                                        echo '<td>'.$row1['teachname'].'</td>';
                                        echo '<td>'.$row1['tel'].'</td>';
                                        echo '<td>'.$row1['qq'].'</td>';

                                        //用 选题名称titname,调用proinf数据库
                                        $sql3 = "select * from selecttitle where titname='{$row['titname']}'";
                                        $result3 = mysqli_query($link,$sql3);
                                        $row3 = mysqli_fetch_assoc($result3);
                                        //根据procode专业代码,查proinf查出专业名字
                                        $sql2 = "select name from proinf where code='{$row3['procode']}'";
                                        $result2 = mysqli_query($link,$sql2);
                                        $row2 = mysqli_fetch_array($result2);
                                        echo '<td>'.$row2['name'].'</td>';

                                        echo '<td>'.$row3['direction'].'</td>';
                                        echo '<td>'.$dif[$row3['difcode']].'</td>';
                                        echo '<td>'.$row3['numlimit'].'</td>';
                                        //已选人数统计,先调取终选结果表,查看是否已经确选了
                                        $sql_countID = "select count(ID) from finalresult where titname='{$row["titname"]}'";
                                        $result_countID = mysqli_query($link,$sql_countID);
                                        $row_countID = mysqli_fetch_array($result_countID);
                                        if($row_countID[0]==1){
                                            echo '<td>'.$row_countID[0].'</td>';
                                        }else{
                                            //调取预选结果表,查看是否已经选择了
                                            $sql_preCountID = "select count(ID) from preresult where titname='{$row["titname"]}'";
                                            $result_preCountID = mysqli_query($link,$sql_preCountID);
                                            $row_preCountID = mysqli_fetch_array($result_preCountID);
                                            echo '<td>'.$row_preCountID[0].'</td>';
                                        }
                                        if($confirm[$row3['confirm']]=='是'){
                                            echo '<td style="color:red;">'.$confirm[$row3['confirm']].'</td>';
                                        }else{
                                            echo '<td>'.$confirm[$row3['confirm']].'</td>';
                                        }
                                    }
                                }else{
                                    echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="12">同学你还没有预选选题</td></tr>';
                                }
                            }
                            echo '</table>';
                            mysqli_close($link);
                        ?>
                </div>
            </div>
        </div>
    </body>
</html>
