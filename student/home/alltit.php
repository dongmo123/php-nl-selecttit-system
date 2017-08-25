<?php
    session_start();//开启session会话
    //验证用户有没有登陆
    if(!$_SESSION['stunum']){
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
    //查询选题最大限选数量
    $sql_limnum = "select permit from department";
    $result_limnum = mysqli_query($link,$sql_limnum);
    $row_limnum=mysqli_fetch_array($result_limnum);
?>
<!doctype html>
<html>
    <head>
        <title>所有选题</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
        <style type="text/css" media="screen">
            .quitselect{
                outline:none;
                border:1px solid #FF7436;
                background:transparent;
                width:30px;
                height:20px;
                cursor:pointer;
                font-size:12px;
                color:#FF7436;
                border-radius:3px;
                box-shadow:2px 1px 1px #FF7436;
            }
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
            #success{
                color:#36D60A;
                font-weight:bold;
                font-size:14px;
                background:url('imgs/success.png') no-repeat left center;
                background-size:30px 30px;
            }
            .remark{
                max-width:80px;
                overflow:hidden;
                text-overflow:ellipsis;
                white-space:nowrap;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position">
                    <span>您的位置:</span>
                    <a href="alltit.php" target="iframe">开始选题>></a>
                    <span>选题列表</span>&nbsp;&nbsp;&nbsp;<span style="color:red;font-size:14px;">当前每位学生选题最大限选数量为<?php echo $row_limnum['permit']; ?>个，限选专业不符不可选，已确选且达上限不可再选。</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
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
                        </select>&nbsp;
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
                        </select>&nbsp;&nbsp;
                        <span>
                            <select name="direction" class="text-word" style="width:110px;font-size:12px;">
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
                            <select name="difcode" class="text-word" style="width:95px;font-size:12px;">
                                    <option value="" class="opt">选题难度</option>
                                    <option value="level01" class="opt" <?php echo ($_GET['difcode']=='level01')?"selected":""; ?>>&nbsp;较容易</option>
                                    <option value="level02" class="opt" <?php echo ($_GET['difcode']=='level02')?"selected":""; ?>>&nbsp;中等</option>
                                    <option value="level03" class="opt" <?php echo ($_GET['difcode']=='level03')?"selected":""; ?>>&nbsp;较难</option>
                                    <option value="level04" class="opt" <?php echo ($_GET['difcode']=='level04')?"selected":""; ?>>&nbsp;很难</option>
                            </select>
                        </span>&nbsp;&nbsp;
                        <span>
                            <select name="confirm" class="text-word" style="width:70px;font-size:12px;">
                                    <option value="" class="opt">所有</option>
                                    <option value="1" class="opt" <?php echo ($_GET['confirm']=='1')?"selected":""; ?>>确选</option>
                                    <option value="0" class="opt" <?php echo ($_GET['confirm']=='0')?"selected":""; ?>>未确选</option>
                            </select>
                        </span>&nbsp;&nbsp;
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
                            <th>指导老师</th>
                            <th>限选专业</th>
                            <th>选题方向</th>
                            <th>选题难度</th>
                            <th>限选人数</th>
                            <th>已选人数</th>
                            <th>选题要求</th>
                            <th>选择</th>
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
                            /*-----------------------------------------------*/
                            if(!empty($_GET['confirm']) || $_GET['confirm']=='0'){
                             $wherelist[]="confirm='{$_GET['confirm']}'";
                             $urllist[] = "confirm={$_GET['confirm']}";
                            }
                            if(!empty($_GET['direction']) || $_GET['direction']==='0'){
                             $wherelist[]="direction='{$_GET['direction']}'";
                             $urllist[] = "direction={$_GET['direction']}";
                            }
                            if(!empty($_GET['difcode']) || $_GET['difcode']==='0'){
                             $wherelist[]="difcode='{$_GET['difcode']}'";
                             $urllist[] = "difcode={$_GET['difcode']}";
                            }
                            if(!empty($_GET['procode']) || $_GET['procode']==='0'){
                             $wherelist[]="procode='{$_GET['procode']}'";
                             $urllist[] = "procode={$_GET['procode']}";
                            }
                             /*-----------------------------------------------*/
                            $wherelist[]="pass=1";
                            $urllist[] = "pass=1";
                            //判断并拼装搜索条件
                            $where = "";
                            $url = "";
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
                            $sql = "select * from selecttitle".$where;
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
                            $order=" order by procode ";
                            //拼装分页limit语句----limit (当前页-1)*页大小,页大小-----分页公式
                            $limit = " limit ".(($page-1)*$pagesize).",".$pagesize;
                                //4.拼装SQL语句并发送服务器执行
                                //排序问题,,在where之后,,,在limit之前
                            $sql = "select * from selecttitle".$where.$order.$limit;
                            $result = mysqli_query($link,$sql);
                            //限选选题数量3,查询department表
                            $sql4 = "select permit from department";
                            $result4 = mysqli_query($link,$sql4);
                            $row4 = mysqli_fetch_array($result4);
                            $permit = $row4['permit'];
                            //检查当前学生已选几个选题
                            $sql5 = "select * from preresult where stunum='{$_SESSION['stunum']}'";
                            $result5 = mysqli_query($link,$sql5);
                            $maxnum = mysqli_num_rows($result5);
                            //5.执行遍历解析输出操作
                            $num=0;
                            if(mysqli_num_rows($result)){

                            while($row = mysqli_fetch_assoc($result)){
                               $num++;
                                echo '<tr class="middle">';
                                echo '<td>'.(($page-1)*15+$num).'</td>';
                                echo '<td>'.$row['ID'].'</td>';
                                echo '<td>'.$row['titname'].'</td>';
                                //将教师编号转换为真实姓名,调用teacher数据库
                                $sql1 = "select teachname from teacher where teachnum='{$row['teachnum']}'";
                                $result1 = mysqli_query($link,$sql1);
                                $row1 = mysqli_fetch_array($result1);
                                $row['teachnum']=$row1['teachname'];
                                echo '<td>'.$row['teachnum'].'</td>';
                                //将专业代码转化为专业名称,调用proinf数据库
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
                                //------------------------------------------------------
                                echo '<td class="remark" title="'.$row['remark'].'">'.$row['remark'].'</td>';
                                //调取预选结果表,查看是否已经选择了
                                $sql_pre = "select * from preresult where stunum='{$_SESSION['stunum']}' and titname='{$row["titname"]}'";
                                $result_pre = mysqli_query($link,$sql_pre);
                                $row_pre = mysqli_fetch_array($result_pre);
                                //调取确选结果表,查看自己是否已经被确选了
                                $sql_conf = "select ID from finalresult where stunum='{$_SESSION['stunum']}' and titname='{$row["titname"]}'";
                                $result_conf = mysqli_query($link,$sql_conf);
                                //调取确选结果表,查看自己是否存在数据
                                $sql_max = "select ID from finalresult where stunum='{$_SESSION['stunum']}'";
                                $result_max = mysqli_query($link,$sql_max);
                                //再判断专业符不符合,有没有资格选择
                                //由学号查专业
                                $sql_one = "select profession from student where stunum='{$_SESSION['stunum']}'";
                                $result_one = mysqli_query($link,$sql_one);
                                $row_one = mysqli_fetch_array($result_one);
                                //由学生专业到专业表查专业代码
                                $sql_two = "select code from proinf where name='{$row_one['profession']}'";
                                $result_two = mysqli_query($link,$sql_two);
                                $row_two = mysqli_fetch_array($result_two);
                                //对比学生专业代码和选题专业代码,有相同部分则可以选择
                                //利用 explode 函数分割字符串到数组
                                //按|分离字符串
                                $zy = explode('|',$row['procode']);
                                $true=0;
                                for($i=0;$i<count($zy);$i++){
                                    if($zy[$i]==$row_two[0]){
                                        $true+=1;
                                    }
                                }
                                if($row['confirm']>=1){
                                    if(mysqli_num_rows($result_conf)>0){
                                        echo '<td style="" class="confirm" id="success">你被确选!</td>';
                                    }else{
                                        //调取确选结果表,查看该选题已经有几个确选了
                                        $sql_maxlimit = "select count(ID) from finalresult where titname='{$row['titname']}'";
                                        $result_maxlimit = mysqli_query($link,$sql_maxlimit);
                                        $row_maxlimit = mysqli_fetch_array($result_maxlimit);
                                        if($row_maxlimit[0]==$row['numlimit']){
                                            //若该选题确选已达上限,则学生无法继续选择
                                            echo '<td style="color:red;font-weight:bold;" class="confirm">已确选</td>';
                                        }else{
                                            //若该选题确选没有达上限,但自己已确选,则学生同样无法继续选择
                                            if(mysqli_num_rows($result_max)>0){
                                                echo '<td style="color:red;font-weight:bold;" class="confirm">已确选</td>';
                                            }else{

                                                if(count($row_pre)>0){
                                                    echo '<td>
                                                    <button class="quitselect" onclick="qu('.'\''.$row["titname"].'\''.')">退选</button>
                                                    </td>';
                                                }else{
                                                    if($true>0||$row['procode']=='all'){
                                                        echo '<td>
                                                       <button class="conselect" onclick="sel('.$row["ID"].')">选择</button>
                                                       </td>';
                                                    }else{
                                                        echo '<td>
                                                       专业不符
                                                       </td>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    if(mysqli_num_rows($result_max)>0){
                                        echo '<td>****</td>';
                                    }else{
                                        if(count($row_pre)>0){
                                            echo '<td>
                                            <button class="quitselect" onclick="qu('.'\''.$row["titname"].'\''.')">退选</button>
                                            </td>';
                                        }else{
                                            if($maxnum<$permit){
                                                if($true>0||$row['procode']=='all'){
                                                    echo '<td>
                                                    <button class="conselect" onclick="sel('.$row["ID"].')">选择</button>
                                                       </td>';
                                                }else{
                                                    echo '<td>
                                                       专业不符
                                                       </td>';
                                                }
                                            }else{
                                                echo '<td>MAX</td>';
                                            }
                                        }
                                    }
                                }
                                echo '</tr>';
                            }
                            }else{
                                echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="13">没有找到此信息</td></tr>';
                            }
                            //6.释放结果集并关闭数据库
                            mysqli_free_result($result);
                            mysqli_close($link);
                        ?>
                    </table>
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
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function sel(id){
        if(confirm ("确认选择吗？")){
            window.location="myaction.php?type=insert&id="+id;
        }
    }
    function qu(titname){
        if(confirm ("确认退选吗？")){
            window.location="myaction.php?type=delete&titname="+titname;
        }
    }
</script>