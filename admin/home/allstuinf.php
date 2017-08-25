<?php
    session_start();
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();
    }
    require("./public/error.php");
    header("Content-Type:text/html;charset=utf8");
    require("./public/config.php");
    $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
    $sql_zteach = "select teachname,teachnum from teacher order by teachnum";
    $result_zteach = mysqli_query($link,$sql_zteach);
?>
<!doctype html>
<html>
    <head>
        <title>所有选题</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
        <style type="text/css" media="screen">
            .conselect{
                outline:none;
                border:1px solid red;
                background:transparent;
                width:60px;
                height:20px;
                cursor:pointer;
                font-size:12px;
                border-radius:3px;
                box-shadow:2px 1px 1px red;
                color:red;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position">
                    <span>您的位置: 选题管理>></span>
                    <a href="allstuinf.php" target="iframe">所有学生选题列表</a>
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
                <button class="text-but2" style="position:absolute;top:2px;left:300px;" onclick="myPrint(document.getElementById('content'))">打 印</button>
                <!-- 打印当前页 -->
                <div class="search">
                  <!---=======搜索表单信息========-->
                    <form action="allstuinf.php" method="get">
                        <span>&nbsp;学号：</span>
                        <input class="text-word3" type="text" size="6" name="stunum" value="<?php  echo isset($_GET['stunum'])?($_GET['stunum']):""; ?>">
                        <span>&nbsp;所属专业：</span>
                        <select name="profession" style="width:140px;height:30px">
                            <option value="">请选择</option>
                            <?php
                                $sql_proinf = "select * from proinf where type=0 order by code";
                                $result_proinf = mysqli_query($link,$sql_proinf);
                                if(count($result_proinf)>0){
                                    while($row_proinf = mysqli_fetch_array($result_proinf)){
                                       echo '<option value="'.$row_proinf['name'].'"';
                                       echo ($row_proinf['name']==$_GET['profession'])?"selected":"";
                                       echo '>'.$row_proinf['name'].'</option>';
                                    }
                                }else{
                                    echo "<option value=\"\">数据字典连接错误</option>";
                                }
                            ?>
                        </select>
                        <span>&nbsp;选题关键字：</span>
                        <input class="text-word3" type="text" size="6" name="titname" value="<?php  echo isset($_GET['titname'])?($_GET['titname']):""; ?>">
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
                        &nbsp;&nbsp;
                        <span>
                            <select name="confirm" class="text-word" style="width:100px;font-size:14px;">
                                <option value="finalresult" class="opt" <?php echo ($_GET['confirm']=='finalresult')?"selected":""; ?>>&nbsp;已确选</option>
                                <option value="preresult" class="opt" <?php echo ($_GET['confirm']=='preresult')?"selected":""; ?>>&nbsp;未确选</option>
                            </select>
                        </span>&nbsp;&nbsp;
                        <input type="submit" value="搜索" class="text-but">
                        <input type="button" onclick="window.location='allstuinf.php'" value="全部" class="text-but">
                    </form>
                </div>
                <div id="content" name="content">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
                        <tr class="top">
                            <th>序号</th>
                            <th>学号</th>
                            <th>姓名</th>
                            <th>所属专业</th>
                            <th>班级</th>
                            <th>选题名称</th>
                            <th>指导老师</th>
                            <th>限选专业</th>
                            <th>选题方向</th>
                            <th>选题难度</th>
                            <th>预选/确选时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        <?php
                            date_default_timezone_set("PRC");//设置中国时区
                            //============封装搜索条件===================================
                            $wherelist = array();  //定义一个用于封装搜索条件的数组
                            $urllist = array();
                            //判断是否有名字搜索
                            if(!empty($_GET['stunum'])){
                                $wherelist[] = "stunum like '%{$_GET['stunum']}%'";
                                $urllist[] = "stunum={$_GET['stunum']}";
                            }
                            if(!empty($_GET['titname'])){
                                $wherelist[] = "titname like '%{$_GET['titname']}%'";
                                $urllist[] = "titname={$_GET['titname']}";
                            }

                            if(!empty($_GET['teachnum'])){
                                $wherelist[] = "teachnum = '{$_GET['teachnum']}'";
                                $urllist[] = "teachnum={$_GET['teachnum']}";
                            }
                            //判断并拼装搜索条件
                            $where = "";
                            $url = "";
                            if(!empty($_GET['confirm'])){
                                $biao=' '.$_GET['confirm'].' ';
                            }else{
                                $biao=' finalresult ';
                            }
                            if(count($wherelist)>0){
                                $where  =" where ".implode(" and ",$wherelist);
                                $url = "&".implode("&",$urllist);
                            }
                            //定义数组选择器
                            $confirm = array(0=>"否",1=>"是");
                            $dif = array(level01=>"较容易",level02=>"中等",level03=>"较难",level04=>"很难");
                            //初始化变量
                            //$page = 1;  //当前那页(当前页)
                            $page = isset($_GET['page'])?$_GET['page']:1;  //当前页
                            $pagesize = 15;  //页大小
                            $maxrows = 0;   //总数据条数
                            $maxpages = 0;  //总页数
                            //获取总数据条数
                            $sql = "select * from ".$biao.$where;
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
                            $order=" order by stunum ";
                            //拼装分页limit语句----limit (当前页-1)*页大小,页大小-----分页公式
                            $limit = " limit ".(($page-1)*$pagesize).",".$pagesize;
                                //4.拼装SQL语句并发送服务器执行
                                //排序问题,,在where之后,,,在limit之前
                            $sql = "select * from ".$biao.$where.$order.$limit;
                            $result = mysqli_query($link,$sql);
                            //5.执行遍历解析输出操作
                            $error=0;
                            $num=0;
                            if(mysqli_num_rows($result)>0){
                                while($row = mysqli_fetch_assoc($result)){
                                    //将学生编号转换为真实姓名,调用student数据库
                                    $sql_stuname = "select stuname,class,profession from student where stunum='{$row['stunum']}'";
                                    $result_stuname = mysqli_query($link,$sql_stuname);
                                    $row_stuname = mysqli_fetch_array($result_stuname);
                                    if(empty($_GET['profession'])||$row_stuname['profession']==$_GET['profession']){
                                        $num++;
                                        echo '<tr class="middle">';
                                        echo '<td>'.(($page-1)*15+$num).'</td>';
                                        echo '<td><a href="student.php?stunum='.$row['stunum'].'" title="点击可查询该学生信息">'.$row['stunum'].'</a></td>';
                                        echo '<td>'.$row_stuname['stuname'].'</td>';
                                        echo '<td>'.$row_stuname['profession'].'</td>';
                                        echo '<td>'.$row_stuname['class'].'</td>';
                                        echo '<td><a href="selecttitle.php?titname='.$row['titname'].'" title="点击可查询该选题信息">'.$row['titname'].'</a></td>';
                                        //通过选题名称,调用selecttitle数据库
                                        $sql_titinf = "select teachnum,direction,difcode,procode from selecttitle where titname='{$row['titname']}' limit 1";
                                        $result_titinf = mysqli_query($link,$sql_titinf);
                                        $row_titinf = mysqli_fetch_array($result_titinf);
                                        //将教师编号$row_titinf['teachnum']转换为真实姓名,调用teacher数据库
                                        $sql_teachname = "select teachname from teacher where teachnum='{$row_titinf['teachnum']}'";
                                        $result_teachname = mysqli_query($link,$sql_teachname);
                                        $row_teachname = mysqli_fetch_array($result_teachname);
                                        echo '<td><a href="teacher.php?teachnum='.$row_titinf['teachnum'].'" title="点击可查询该教师信息">'.$row_teachname['teachname'].'</a></td>';
                                        //将专业代码转化为专业名称,调用proinf数据库
                                        $sql_proname = "select name from proinf where code='{$row_titinf['procode']}'";
                                        $result_proname = mysqli_query($link,$sql_proname);
                                        $row_proname = mysqli_fetch_array($result_proname);
                                        echo '<td>'.$row_proname['name'].'</td>';
                                        echo '<td>'.$row_titinf['direction'].'</td>';
                                        echo '<td>'.$dif[$row_titinf['difcode']].'</td>';
                                        echo '<td>'.$row['time'].'</td>';
                                        if($biao==" finalresult "){
                                            echo '<td style="color:red;">已确选</td>';
                                            echo '<td>
                                                <button class="conselect" style="color:red;" onclick="sel('.$row["ID"].')">强制退选</button>
                                            </td>';
                                        }else{
                                            echo '<td>未确选</td>';
                                            echo '<td>
                                                <button class="conselect" style="color:red;" onclick="presel('.$row["ID"].')">强制退选</button>
                                            </td>';
                                        }

                                        echo '</tr>';
                                    }else{
                                        $error+=1;
                                        if($error>=mysqli_num_rows($result)){
                                            echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="11">没有找到此信息!</td></tr>';
                                        }
                                    }
                                }
                            }else{
                                echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="11">没有找到此信息!</td></tr>';
                            }

                        ?>
                    </table>

                </div>
                <div align="left" valign="top" class="fenye">
                        <?php
                            echo "当前第{$page}/{$maxpages}页，共计{$maxrows}条 ";
                            echo " <a href='alltit.php?page=1{$url}'>首页</a> ";
                            echo " <a href='alltit.php?page=".($page-1)."{$url}'>上一页</a> ";
                            echo " <a href='alltit.php?page=".($page+1)."{$url}'>下一页</a> ";
                            echo " <a href='alltit.php?page={$maxpages}{$url}'>末页</a> ";
                        ?>
                    </div>
                    <form name="form2" method="post" enctype="multipart/form-data" action="./download/export_excel_allstutit.php">
                        <input type="hidden" name="leadExcel" value="true">
                        <table align="center" width="100% " id="upload" border="0" class="repasstable">
                            <tr style="font-size:13px;font-weight: bold;">
                                <td>导出学生选题信息表</td>
                                <td>
                                    <span>&nbsp;专业：</span>
                                    <select name="profession" style="width:140px;height:30px">
                                        <option value="">请选择</option>
                                        <?php
                                            $sql_proinf = "select * from proinf where type=0 order by code";
                                            $result_proinf = mysqli_query($link,$sql_proinf);
                                            if(count($result_proinf)>0){
                                                while($row_proinf = mysqli_fetch_array($result_proinf)){
                                                   echo '<option value="'.$row_proinf['name'].'"';
                                                   echo ($row_proinf['name']==$_GET['profession'])?"selected":"";
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
                                    &nbsp;
                                    <span>
                                        <select name="confirm" class="text-word" style="width:100px;font-size:14px;">
                                            <option value="finalresult" class="opt" <?php echo ($_GET['confirm']=='finalresult')?"selected":""; ?>>&nbsp;已确选</option>
                                            <option value="preresult" class="opt" <?php echo ($_GET['confirm']=='preresult')?"selected":""; ?>>&nbsp;未确选</option>
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
<script type="text/javascript">
    function sel(id,titname){
        if(confirm ("确认强制退选已被确选的学生吗？")){
            window.location="titaction.php?type=qzdel&id="+id;
        }
    }
    function presel(id){
        if(confirm ("确认强制退选学生的预选吗？")){
            window.location="titaction.php?type=qzpredel&id="+id;
        }
    }
</script>