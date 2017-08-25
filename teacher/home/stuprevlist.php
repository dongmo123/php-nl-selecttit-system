<?php
    session_start();
    if(!$_SESSION['teachnum']){
        header("Location:../../index.php");
            exit();
    }
    error_reporting(E_ALL & ~E_NOTICE);
    header("Content-Type:text/html;charset=utf8");
    require("./public/config.php");
    $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
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
                border:1px solid #2CAAF0;
                background:transparent;
                width:30px;
                height:20px;
                cursor:pointer;
                font-size:12px;
                border-radius:3px;
                box-shadow:2px 1px 1px #2CAAF0;
                color:#2CAAF0;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position">
                    <span>您的位置: 开始选题>></span>
                    <a href="stuprevlist.php" target="iframe">学生预选</a>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div class="search">
                  <!---=======搜索表单信息========-->
                    <form action="stuprevlist.php" method="get">
                        <span>&nbsp;学号：</span>
                        <input class="text-word3" type="text" size="6" name="stunum" value="<?php  echo isset($_GET['stunum'])?($_GET['stunum']):""; ?>">
                        <span>&nbsp;学生姓名：</span>
                        <input class="text-word3" type="text" size="6" name="stuname" value="<?php  echo isset($_GET['stuname'])?($_GET['stuname']):""; ?>">
                        <span>&nbsp;选题关键字：</span>
                        <input class="text-word3" type="text" size="6" name="titname" value="<?php  echo isset($_GET['titname'])?($_GET['titname']):""; ?>">&nbsp;&nbsp;
                        <input type="submit" value="搜索" class="text-but">
                        <input type="button" onclick="window.location='stuprevlist.php'" value="全部" class="text-but">
                    </form>
                </div>
                <div id="content" name="content">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
                        <tr class="top">
                            <th>学号</th>
                            <th>姓名</th>
                            <th>所属专业</th>
                            <th>班级</th>
                            <th>联系电话</th>
                            <th>选题ID</th>
                            <th>选题名称</th>
                            <th>指导老师</th>
                            <th>限选专业</th>
                            <th>选题方向</th>
                            <th>选题难度</th>
                            <th>预选时间</th>
                            <th>状态</th>
                        </tr>
                        <?php
                            date_default_timezone_set("PRC");//设置中国时区
                            //============封装搜索条件===================================
                            $wherelist = array();  //定义一个用于封装搜索条件的数组
                            $urllist = array();
                            //判断是否有搜索
                            if(!empty($_GET['stunum'])){
                                $wherelist[] = "stunum like '%{$_GET['stunum']}%'";
                                $urllist[] = "stunum={$_GET['stunum']}";
                            }
                            /*------------将得到的xs姓名转化成xsxh-------*/
                            $sql_stu = "select stunum from student where stuname like '%{$_GET['stuname']}%' limit 1";
                            $result_stu = mysqli_query($link,$sql_stu);
                            $row_stu = mysqli_fetch_array($result_stu);
                            if(!empty($_GET['stuname'])){
                                $wherelist[] = "stunum like '%{$row_stu['stunum']}%'";
                                $urllist[] = "stunum={$row_stu['stunum']}";
                            }
                            /*-------------------*/

                            if(!empty($_GET['titname'])){
                                $wherelist[] = "titname like '%{$_GET['titname']}%'";
                                $urllist[] = "titname={$_GET['titname']}";
                            }
                            $wherelist[] = "teachnum='{$_SESSION['teachnum']}'";
                            //判断并拼装搜索条件
                            $where = "";
                            $url = "";
                            $biao = " preresult ";
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
                            $order=" order by titname,stunum ";
                            //拼装分页limit语句----limit (当前页-1)*页大小,页大小-----分页公式
                            $limit = " limit ".(($page-1)*$pagesize).",".$pagesize;
                                //4.拼装SQL语句并发送服务器执行
                                //排序问题,,在where之后,,,在limit之前
                            $sql = "select * from ".$biao.$where.$order.$limit;
                            $result = mysqli_query($link,$sql);
                            //5.执行遍历解析输出操作
                            if(mysqli_num_rows($result)){
                                while($row = mysqli_fetch_assoc($result)){
                                    echo '<tr class="middle">';
                                    echo '<td>'.$row['stunum'].'</td>';
                                    //将学生转换为真实姓名,调用student数据库
                                    $sql_stuname = "select stuname,class,profession,tel from student where stunum='{$row['stunum']}'";
                                    $result_stuname = mysqli_query($link,$sql_stuname);
                                    $row_stuname = mysqli_fetch_array($result_stuname);
                                    echo '<td>'.$row_stuname['stuname'].'</td>';
                                    echo '<td>'.$row_stuname['profession'].'</td>';
                                    echo '<td>'.$row_stuname['class'].'</td>';
                                    echo '<td>'.$row_stuname['tel'].'</td>';
                                    echo '<td>'.$row['ID'].'</td>';
                                    echo '<td>'.$row['titname'].'</td>';
                                    //通过选题名称,调用selecttitle数据库
                                    $sql_titinf = "select teachnum,direction,difcode,procode from selecttitle where titname='{$row['titname']}' limit 1";
                                    $result_titinf = mysqli_query($link,$sql_titinf);
                                    $row_titinf = mysqli_fetch_array($result_titinf);
                                    //将教师编号$row_titinf['teachnum']转换为真实姓名,调用teacher数据库
                                    $sql_teachname = "select teachname from teacher where teachnum='{$row_titinf['teachnum']}'";
                                    $result_teachname = mysqli_query($link,$sql_teachname);
                                    $row_teachname = mysqli_fetch_array($result_teachname);
                                    if($row_teachname['teachname']==$_SESSION['teachname']){
                                        echo '<td style="color:red;font-weight:bold;">'.$row_teachname['teachname'].'</td>';
                                    }else{
                                        echo '<td>'.$row_teachname['teachname'].'</td>';
                                    }
                                    //将专业代码转化为专业名称,调用proinf数据库
                                    $sql_proname = "select name from proinf where code='{$row_titinf['procode']}'";
                                    $result_proname = mysqli_query($link,$sql_proname);
                                    $row_proname = mysqli_fetch_array($result_proname);
                                    echo '<td>'.$row_proname['name'].'</td>';
                                    echo '<td>'.$row_titinf['direction'].'</td>';
                                    echo '<td>'.$dif[$row_titinf['difcode']].'</td>';
                                    echo '<td>'.$row['time'].'</td>';
                                    echo '<td>
                                            <button class="conselect" onclick="sel('.$row["ID"].')">确选</button>
                                        </td>';
                                    echo '</tr>';
                                }
                            }else{
                                echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="13">还没有学生预选您的课题</td></tr>';
                            }

                            //6.释放结果集并关闭数据库
                            mysqli_free_result($result);
                            mysqli_close($link);
                        ?>
                    </table>
                    <div align="left" valign="top" class="fenye">
                        <?php
                            echo "当前第{$page}/{$maxpages}页，共计{$maxrows}条 ";
                            echo " <a href='alltit.php?page=1{$url}'>首页</a> ";
                            echo " <a href='alltit.php?page=".($page-1)."{$url}'>上一页</a> ";
                            echo " <a href='alltit.php?page=".($page+1)."{$url}'>下一页</a> ";
                            echo " <a href='alltit.php?page={$maxpages}{$url}'>末页</a> ";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function sel(id){
        if(confirm ("确认选择吗？")){
            window.location="teachtitaction.php?type=insertupdata&id="+id;
        }
    }
</script>