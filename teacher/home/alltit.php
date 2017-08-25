<?php
    session_start();//开启session会话
    //验证用户有没有登陆
    if(!$_SESSION['teachnum']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    error_reporting(E_ALL & ~E_NOTICE);
    header("Content-Type:text/html;charset=utf8");
    require("./public/config.php");
    $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
    $sql_pro = "select * from proinf order by code";
    $result_pro = mysqli_query($link,$sql_pro);
    $sql_zteach = "select teachname,teachnum from teacher order by teachnum";
    $result_zteach = mysqli_query($link,$sql_zteach);
?>
<!doctype html>
<html>
    <head>
        <title>选题管理</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position">
                    <span>您的位置:</span>
                    <a href="alltit.php" target="iframe">所有选题信息</a>
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
                <button class="text-but2" style="position:absolute;top:2px;left:200px;" onclick="myPrint(document.getElementById('content'))">打 印</button>
                <!-- 打印当前页 -->
                <div class="search">
                  <!---=======搜索表单信息========-->
                    <form action="alltit.php" method="get">
                        <span>&nbsp;选题关键字：</span>
                        <input class="text-word3" type="text" size="6" name="titname" value="<?php  echo isset($_GET['titname'])?($_GET['titname']):""; ?>">
                        <span>&nbsp;选题ID：</span>
                        <input class="text-word3" type="text" size="6" name="ID" value="<?php  echo isset($_GET['ID'])?($_GET['ID']):""; ?>">&nbsp;&nbsp;
                        <span>&nbsp;导师：</span>
                        <select name="teachnum" style="width:70px;height:24px">
                            <option value="">请选择</option>
                            <?php
                                if(count($result_zteach)>0){
                                    while($row_zteach = mysqli_fetch_array($result_zteach)){
                                        echo '<option value="'.$row_zteach['teachnum'].'"';
                                        echo ($row_zteach['teachnum']==$_GET['teachnum'])?"selected":"";
                                        echo '>'.$row_zteach['teachname'].'</option>';
                                    }
                                }else{
                                    echo "<option value=\"\">数据字典连接错误</option>";
                                }
                            ?>
                        </select>
                        <select name="procode" style="width:140px;height:24px">
                            <option value="">请选择专业</option>
                            <?php
                                if(count($result_pro)>0){
                                    while($row_pro = mysqli_fetch_array($result_pro)){
                                        echo '<option value="'.$row_pro['code'].'"';
                                        echo ($row_pro['code']==$_GET['procode'])?"selected":"";
                                        echo '>'.$row_pro['name'].'</option>';
                                    }
                                }else{
                                    echo "<option value=\"\">数据字典连接错误</option>";
                                }
                            ?>
                        </select>
                        <span>
                            <select name="direction" class="text-word" style="width:110px;font-size:14px;">
                                    <option value="" class="opt">选题方向</option>
                                    <option value="工程设计" class="opt" <?php echo ($_GET['direction']=='工程设计')?"selected":""; ?>>&nbsp;工程设计</option>
                                    <option value="技术开发" class="opt" <?php echo ($_GET['direction']=='技术开发')?"selected":""; ?>>&nbsp;技术开发</option>
                                    <option value="软件工程" class="opt" <?php echo ($_GET['direction']=='软件工程')?"selected":""; ?>>&nbsp;软件工程</option>
                                    <option value="理论研究和方法应用" class="opt" <?php echo ($_GET['direction']=='理论研究和方法应用')?"selected":""; ?>>&nbsp;理论研究</option>
                                    <option value="管理模式设计" class="opt" <?php echo ($_GET['direction']=='管理模式设计')?"selected":""; ?>>&nbsp;管理模式</option>
                                    <option value="其他" class="opt" <?php echo ($_GET['direction']=='其他')?"selected":""; ?>>&nbsp;其他</option>
                            </select>
                        </span>&nbsp;&nbsp;
                        <span>
                            <select name="difcode" class="text-word" style="width:95px;font-size:14px;">
                                    <option value="" class="opt">选题难度</option>
                                    <option value="level01" class="opt" <?php echo ($_GET['difcode']=='level01')?"selected":""; ?>>较容易</option>
                                    <option value="level02" class="opt" <?php echo ($_GET['difcode']=='level02')?"selected":""; ?>>中等</option>
                                    <option value="level03" class="opt" <?php echo ($_GET['difcode']=='level03')?"selected":""; ?>>较难</option>
                                    <option value="level04" class="opt" <?php echo ($_GET['difcode']=='level04')?"selected":""; ?>>很难</option>
                            </select>
                        </span>&nbsp;&nbsp;
                        <span>
                            <select name="confirm" class="text-word" style="width:85px;font-size:14px;">
                                    <option value="" class="opt">默认</option>
                                    <option value="1" class="opt" <?php echo ($_GET['confirm']=='1')?"selected":""; ?>>确选</option>
                                    <option value="0" class="opt" <?php echo ($_GET['confirm']=='0')?"selected":""; ?>>未确选</option>
                            </select>
                        </span>&nbsp;&nbsp;
                        <span>
                            <select name="pass" class="text-word" style="width:75px;font-size:14px;">
                                    <option value="" class="opt">审核</option>
                                    <option value="1" class="opt" <?php echo ($_GET['pass']=='1')?"selected":""; ?>>PASS</option>
                                    <option value="2" class="opt" <?php echo ($_GET['pass']=='2')?"selected":""; ?>>NO</option>
                                    <option value="0" class="opt" <?php echo ($_GET['pass']=='0')?"selected":""; ?>>等待</option>
                            </select>
                        </span> &nbsp;
                        <input type="submit" value="搜索" class="text-but">
                        <input type="button" onclick="window.location='alltit.php'" value="全部" class="text-but">
                    </form>
                </div>
                <div id="content" name="content">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
                        <tr class="top">
                            <th>序号</th>
                            <th>选题ID</th>
                            <th>选题名称</th>
                            <th>导师编号</th>
                            <th>指导老师</th>
                            <th>专业代码</th>
                            <th>限选专业</th>
                            <th>选题方向</th>
                            <th>选题难度</th>
                            <th>限选人数</th>
                            <th>选题要求</th>
                            <th>已选人数</th>
                            <th>确选</th>
                            <th>审核</th>
                        </tr>
                        <?php
                            date_default_timezone_set("PRC");//设置中国时区
                            //============封装搜索条件===================================
                            $wherelist = array();  //定义一个用于封装搜索条件的数组
                            $urllist = array();
                            //判断是否有名字搜索
                            if(!empty($_GET['titname'])){
                                $wherelist[] = "titname like '%{$_GET['titname']}%'";
                                $urllist[] = "titname={$_GET['titname']}";

                            }
                            if(!empty($_GET['ID'])){
                                $wherelist[] = "ID like '%{$_GET['ID']}%'";
                                $urllist[] = "ID={$_GET['ID']}";
                            }
                            if(!empty($_GET['teachnum'])){
                                $wherelist[] = "teachnum like '%{$_GET['teachnum']}%'";
                                $urllist[] = "teachnum={$_GET['teachnum']}";
                            }
                            if(!empty($_GET['confirm'])||$_GET['confirm']=='0'){
                             $wherelist[]="confirm='{$_GET['confirm']}'";
                             $urllist[] = "confirm={$_GET['confirm']}";
                            }
                            if(!empty($_GET['direction'])){
                             $wherelist[]="direction='{$_GET['direction']}'";
                             $urllist[] = "direction={$_GET['direction']}";
                            }
                            if(!empty($_GET['difcode'])){
                             $wherelist[]="difcode='{$_GET['difcode']}'";
                             $urllist[] = "difcode={$_GET['difcode']}";
                            }
                            if(!empty($_GET['pass'])||$_GET['pass']=='0'){
                             $wherelist[]="pass='{$_GET['pass']}'";
                             $urllist[] = "pass={$_GET['pass']}";
                            }
                            if(!empty($_GET['procode']) || $_GET['procode']==='0'){
                             $wherelist[]="procode='{$_GET['procode']}'";
                             $urllist[] = "procode={$_GET['procode']}";
                            }
                            //判断并拼装搜索条件
                            $where = "";
                            $url = "";
                            if(count($wherelist)>0){
                                $where  =" where ".implode(" and ",$wherelist);
                                $url = "&".implode("&",$urllist);
                            }
                            //定义数组选择器
                            $confirm = array(0=>"否",1=>"是");
                            $pass = array(0=>"等待",1=>"PASS",2=>"不合格");
                            $dif = array(level01=>"较容易",level02=>"中等",level03=>"较难",level04=>"很难");


                            //初始化变量
                            //$page = 1;  //当前那页(当前页)
                            $page = isset($_GET['page'])?$_GET['page']:1;  //当前页
                            $pagesize = 15;  //页大小
                            $maxrows = 0;   //总数据条数
                            $maxpages = 0;  //总页数

                            //获取总数据条数
                            $sql = "select * from selecttitle".$where;
                            //echo $sql;

                            $result = mysqli_query($link,$sql);
                            $maxrows = mysqli_num_rows($result);  //结果集的定位取值
                            //计算总页数
                            $maxpages = ceil($maxrows/$pagesize);//采用进一取整法计算总页数
                            //判断页数是否越界
                            if($page>$maxpages){
                                $page=$maxpages;  //防止页数过大
                            }
                            if($page<1){
                                $page=1;          //防止页数过小
                            }
                            //拼装分页limit语句----limit (当前页-1)*页大小,页大小-----分页公式
                            $limit = " limit ".(($page-1)*$pagesize).",".$pagesize;
                                /*
                                limit 0,4      第1条 (1-1*4),4     0,4
                                limit 4,4      第2条 (2-1*4),4     1,4
                                limit 8,4      第3条 (3-1*4),4     2,4
                                limit 12,4     第4条 (4-1*4),4     3,4
                                limit 16,4     第5条 (5-1*4),4     4,4
                                */

                            //==================================================
                            //4.拼装SQL语句并发送服务器执行
                            $order = " order by teachnum,pass,procode";
                            $sql = "select * from selecttitle".$where.$order.$limit;
                            $result = mysqli_query($link,$sql);

                            //5.执行遍历解析输出操作
                            $num=0;
                            if(mysqli_num_rows($result)){
                                while($row = mysqli_fetch_array($result)){
                                   $num++;
                                    echo '<tr class="middle">';
                                    echo '<td>'.(($page-1)*10+$num).'</td>';
                                    echo '<td>'.$row['ID'].'</td>';
                                    echo '<td>'.$row['titname'].'</td>';
                                    echo '<td>'.$row['teachnum'].'</td>';
                                    //将教师编号转换为真实姓名,调用teacher数据库
                                    $sql1 = "select teachname from teacher where teachnum='{$row['teachnum']}'";
                                    $result1 = mysqli_query($link,$sql1);
                                    $row1 = mysqli_fetch_array($result1);
                                    $row['teachnum']=$row1['teachname'];
                                    echo '<td>'.$row['teachnum'].'</td>';
                                    echo '<td>'.$row['procode'].'</td>';
                                    //将专业代码转化为专业名称,调用proinf数据库
                                    $sql2 = "select name from proinf where code='{$row['procode']}'";
                                    $result2 = mysqli_query($link,$sql2);
                                    if(mysqli_num_rows($result2)){
                                        $row2 = mysqli_fetch_array($result2);
                                        $row['procode']=$row2['name'];
                                        echo '<td>'.$row['procode'].'</td>';
                                    }else{
                                       echo '<td style="color:red;">专业代码字典中无此信息</td>';
                                    }
                                    echo '<td>'.$row['direction'].'</td>';
                                    echo '<td>'.$dif[$row['difcode']].'</td>';
                                    echo '<td>'.$row['numlimit'].'</td>';
                                    echo '<td class="remark" title="'.$row['remark'].'">'.$row['remark'].'</td>';
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
                                    if($confirm[$row['confirm']]=='是'){
                                        echo '<td style="color:red;">'.$confirm[$row['confirm']].'</td>';
                                    }else{
                                        echo '<td>'.$confirm[$row['confirm']].'</td>';
                                    }
                                    if($row['pass']==0){
                                        echo '<td>'.$pass[$row['pass']].'</td>';
                                    }elseif($row['pass']==1){
                                        echo '<td style="color:#40B827;font-weight:bold;">'.$pass[$row['pass']].'</td>';
                                    }else{
                                        echo '<td style="color:red;">'.$pass[$row['pass']].'</td>';
                                    }

                                    echo '</tr>';
                                }
                            }else{
                                echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="13">没有找到此信息</td></tr>';
                            }
                        ?>

                    </table>
                </div>
                    <div align="left" valign="top" class="fenye">
                        <?php
                            //输出页码信息
                            // echo $maxrows;
                            echo "当前第{$page}/{$maxpages}页，共计{$maxrows}条 ";
                            echo " <a href='alltit.php?page=1{$url}'>首页</a> ";
                            echo " <a href='alltit.php?page=".($page-1)."{$url}'>上一页</a> ";
                            echo " <a href='alltit.php?page=".($page+1)."{$url}'>下一页</a> ";
                            echo " <a href='alltit.php?page={$maxpages}{$url}'>末页</a> ";
                        ?>
                    </div>
                    <form name="form2" method="post" enctype="multipart/form-data" action="../../admin/home/download/export_excel_alltit.php">
                        <input type="hidden" name="leadExcel" value="true">
                        <table align="center" width="100% " id="upload" border="0" class="repasstable">
                            <tr style="font-size:13px;font-weight: bold;">
                                <td>导出选题信息表</td>
                                <td>
                                    <span>&nbsp;专业：</span>
                                    <select name="procode" style="width:140px;height:30px">
                                        <option value="">请选择</option>
                                        <?php
                                            $sql_proinf = "select * from proinf order by code";
                                            $result_proinf = mysqli_query($link,$sql_proinf);
                                            if(count($result_proinf)>0){
                                                while($row_proinf = mysqli_fetch_array($result_proinf)){
                                                   echo '<option value="'.$row_proinf['code'].'"';
                                                   echo ($row_proinf['code']==$_GET['procode'])?"selected":"";
                                                   echo '>'.$row_proinf['name'].'</option>';
                                                }
                                            }else{
                                                echo "<option value=\"\">数据字典连接错误</option>";
                                            }
                                        ?>
                                    </select>
                                    <span>&nbsp;导师：</span>
                                    <select name="teachnum" style="width:70px;height:24px">
                                        <option value="">请选择</option>
                                        <?php
                                            $sql_zteach = "select teachname,teachnum from teacher order by teachnum";
                                            $result_zteach = mysqli_query($link,$sql_zteach);
                                            if(count($result_zteach)>0){
                                                while($row_zteach = mysqli_fetch_array($result_zteach)){
                                                    echo '<option value="'.$row_zteach['teachnum'].'"';
                                                    echo ($row_zteach['teachnum']==$_GET['teachnum'])?"selected":"";
                                                    echo '>'.$row_zteach['teachname'].'</option>';
                                                }
                                            }else{
                                                echo "<option value=\"\">数据字典连接错误</option>";
                                            }
                                        ?>
                                    </select>
                                    <span>
                                        <select name="confirm" class="text-word" style="width:85px;font-size:14px;">
                                                <option value="" class="opt">默认</option>
                                                <option value="1" class="opt" <?php echo ($_GET['confirm']=='1')?"selected":""; ?>>确选</option>
                                                <option value="0" class="opt" <?php echo ($_GET['confirm']=='0')?"selected":""; ?>>未确选</option>
                                        </select>
                                    </span>&nbsp;&nbsp;
                                    <span>
                                        <select name="pass" class="text-word" style="width:75px;font-size:14px;">
                                                <option value="" class="opt">审核</option>
                                                <option value="1" class="opt" <?php echo ($_GET['pass']=='1')?"selected":""; ?>>PASS</option>
                                                <option value="2" class="opt" <?php echo ($_GET['pass']=='2')?"selected":""; ?>>NO</option>
                                                <option value="0" class="opt" <?php echo ($_GET['pass']=='0')?"selected":""; ?>>等待</option>
                                        </select>
                                    </span>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="submit" name="import" value="导 出 数 据" class="text-word4" style="background:#FFE793;cursor:pointer;font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;
                                    (若筛选为空即表示所有该信息!)
                                </td>
                            </tr>
                        </table>
                    </form>
                    <?php
                        //6.释放结果集并关闭数据库
                        mysqli_free_result($result);
                        mysqli_close($link);
                    ?>
            </div>
        </div>
    </body>
</html>
<!-- 引用jquery.validate表单验证框架 -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/jquery.zzcheck.js"></script>
<script src="js/jquery.hot.js"></script>