<?php
    session_start();//开启session会话
    //验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    require("./public/error.php");
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
        <title>管理员信息管理</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position">
                    <span>您的位置:留言</span>
                    <a href="message.php" target="iframe">>>查看留言</a>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div class="search">
                    <!---=======搜索表单信息========-->
                    <form action="message.php" method="get">
                        <span>留言人：</span>
                        <input class="text-word3" type="text" size="6" name="username" value="<?php  echo isset($_GET['username'])?($_GET['username']):""; ?>">
                        <span>&nbsp;用户类型：</span>
                        <span>
                            <select name="usertype" class="text-word" style="width:85px;font-size:14px;">
                                    <option value="" class="opt">全部</option>
                                    <option value="学生" class="opt" <?php echo ($_GET['usertype']=='学生')?"selected":""; ?>>学生</option>
                                    <option value="教师" class="opt" <?php echo ($_GET['usertype']=='教师')?"selected":""; ?>>教师</option>
                                    <option value="管理员" class="opt" <?php echo ($_GET['usertype']=='管理员')?"selected":""; ?>>管理员</option>
                            </select>
                        </span>
                        <input type="submit" value="搜索" class="text-but">
                        <input type="button" onclick="window.location='message.php'" value="全部" class="text-but">
                    </form>
                    <!--======================-->
                    <div><a href="addmess.php" target="iframe" class="add" style="">添加留言</a></div>
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
                            //============封装搜索条件===================================
                            $wherelist = array();  //定义一个用于封装搜索条件的数组
                            $urllist = array();
                            //判断是否有名字搜索
                            if(!empty($_GET['username'])){
                                //搜索username
                                $wherelist[] = "username like '%{$_GET['username']}%'";
                                $urllist[] = "username={$_GET['username']}";

                            }
                            if(!empty($_GET['usertype'])){
                                //用户类型
                                $wherelist[] = "usertype like '%{$_GET['usertype']}%'";
                                $urllist[] = "usertype={$_GET['usertype']}";

                            }

                            //判断并拼装搜索条件
                            $where = "";
                            $url = "";
                            if(count($wherelist)>0){
                                $where  =" where ".implode(" and ",$wherelist);
                                $url = "&".implode("&",$urllist);
                            }

                            //=============================================================

                            //1.导入配置文件
                            require("./public/config.php");

                            //2.连接mysqli,并判断是否连接成功过
                            $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");

                            //3.选择连接数据库并配置字符集
                            mysqli_select_db($link,DBNAME);
                            mysqli_set_charset($link,"utf8");

                            //==================做分页处理================================
                            //初始化变量
                            //$page = 1;  //当前那页(当前页)
                            $page = isset($_GET['page'])?$_GET['page']:1;  //当前页
                            $pagesize = 15;  //页大小
                            $maxrows = 0;   //总数据条数
                            $maxpages = 0;  //总页数

                            //获取总数据条数
                            $sql = "select * from message".$where;
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
                            $limit = " limit ".(($page-1)*$pagesize).",".$pagesize;
                            $order=" order by time desc";
                            $sql = "select * from message".$where.$order.$limit;
                            $result = mysqli_query($link,$sql);

                            //5.执行遍历解析输出操作
                            $num=0;
                            $zpass=array(0=>"待审核",1=>"通过",2=>"不合格");
                            if(mysqli_num_rows($result)){
                                while($row = mysqli_fetch_assoc($result)){
                                   $num++;
                                    echo '<tr class="middle">';
                                    echo '<td>'.(($page-1)*15+$num).'</td>';
                                    echo '<td>'.$row['username'].'</td>';
                                    echo '<td>'.$row['usertype'].'</td>';
                                    if($row['usertype']=='管理员'){
                                        echo '<td style="color:red;text-align:left;">'.$row['content'].'</td>';
                                    }else{
                                        echo '<td style="text-align:left;">'.$row['content'].'</td>';
                                    }

                                    echo '<td>'.$row['time'].'</td>';
                                    echo '<td>'.$row['targetuser'].'</td>';
                                    echo '<td>'.$zpass[$row['pass']].'</td>';
                                    echo '<td>
                                            <a href="javascript:pass('.$row["ID"].',1'.')" target="" class="link blue">通过</a>
                                            <span class="gray">&nbsp;|&nbsp;</span>
                                            <a href="javascript:pass('.$row["ID"].',2'.')" target="" class="link red">不合格</a>
                                            <span class="gray">&nbsp;|&nbsp;</span>
                                            <a href="javascript:dodel('.$row["ID"].')" target="" class="link red">删除</a>
                                            <span class="gray">&nbsp;|&nbsp;</span>
                                            <a href="addmess_r.php?targetuser='.$row["username"].'&usertype='.$row["usertype"].'" target="" class="link blue">回复</a>
                                        </td>';
                                    echo '</tr>';
                                }
                            }else{
                                echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="13">没有找到此信息</td></tr>';
                            }
                        ?>
                    </table>
                    <div align="left" valign="top" class="fenye">
                        <?php
                            echo "当前第{$page}/{$maxpages}页，共计{$maxrows}条 ";
                            echo " <a href='message.php?page=1{$url}'>首页</a> ";
                            echo " <a href='message.php?page=".($page-1)."{$url}'>上一页</a> ";
                            echo " <a href='message.php?page=".($page+1)."{$url}'>下一页</a> ";
                            echo " <a href='message.php?page={$maxpages}{$url}'>末页</a> ";
                        ?>
                    </div>
                    <form name="form2" method="post" onsubmit="return sumbit_sure()" enctype="multipart/form-data" action="messaction.php?type=deleteall">
                        <input type="hidden" name="leadExcel" value="true">
                        <table align="center" width="100% " id="upload" border="0" class="repasstable">
                            <tr style="font-size:13px;font-weight: bold;">
                                <td>批量删除留言</td>
                                <td>
                                    <span>留言人：</span>
                                    <input class="text-word3" type="text" size="6" name="username" placeholder="关键字" value="<?php  echo isset($_GET['username'])?($_GET['username']):""; ?>">
                                    <span>&nbsp;用户类型：</span>
                                    <span>
                                        <select name="usertype" class="text-word" style="width:85px;font-size:14px;">
                                                <option value="" class="opt">全部</option>
                                                <option value="学生" class="opt" <?php echo ($_GET['usertype']=='学生')?"selected":""; ?>>学生</option>
                                                <option value="教师" class="opt" <?php echo ($_GET['usertype']=='教师')?"selected":""; ?>>教师</option>
                                                <option value="管理员" class="opt" <?php echo ($_GET['usertype']=='管理员')?"selected":""; ?>>管理员</option>
                                        </select>
                                    </span>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="submit" name="import" value="删除筛选信息" class="text-word4" style="background:#FFE793;cursor:pointer;font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;
                                    (若筛选为空即表示所有该信息!)
                                </td>
                            </tr>
                        </table>
                    </form>
                    <?php
                        if(!empty($_GET['pass'])&&!empty($_GET['id'])){
                            //拼装SQL语句
                            $sqlup = "update message set pass='{$_GET['pass']}' where id={$_GET['id']}";
                            //执行SQL语句
                            mysqli_query($link,$sqlup);
                            //根据影响的行数来判断是否更新成功
                            if(mysqli_affected_rows($link)>0){
                                echo "<script type='text/javascript'>window.onload=function(){alert('更新数据成功!');history.go(0);}</script>";
                            }

                        }
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
    function pass(id,pass){
        if(confirm ("确定操作吗？")){
            window.location="message.php?pass="+pass+"&id="+id;
        }
    }
    function sumbit_sure(){
        var gnl=confirm("确定要批量删除吗?一旦删除无法恢复");
        if (gnl==true){
            return true;
        }else{
            return false;
        }
    }
</script>