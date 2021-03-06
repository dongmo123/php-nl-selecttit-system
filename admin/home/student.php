<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    //require("./public/error.php");
    header("Content-Type:text/html;charset=utf8");
    //1.导入配置文件
    require("./public/config.php");
    require("./public/error.php");

    //2.连接mysqli,并判断是否连接成功过
    $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");

    //3.选择连接数据库并配置字符集
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
    $sql_proinf = "select name from proinf order by code";
    $result_proinf = mysqli_query($link,$sql_proinf);
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
					<span>您的位置:</span>
					<a href="admin.php" target="iframe">用户管理</a>
					<a href="student.php" target="iframe">>>学生管理</a>
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
					<form action="student.php" method="get">
			            <span>学号：</span>
			            <input class="text-word3" type="text" size="6" name="stunum" value="<?php  echo isset($_GET['stunum'])?($_GET['stunum']):""; ?>">
			            <span>&nbsp;姓名：</span>
			            <input class="text-word3" type="text" size="6" name="stuname" value="<?php  echo isset($_GET['stuname'])?($_GET['stuname']):""; ?>">
			            <span>&nbsp;性别：</span>
			            <span>
			                <select name="sex" class="text-word" style="width:50px;font-size:12px;">
			                        <option value="" class="opt">全部</option>
			                        <option value="0" class="opt" <?php echo ($_GET['sex']=='0')?"selected":""; ?>>男</option>
			                        <option value="1" class="opt" <?php echo ($_GET['sex']=='1')?"selected":""; ?>>女</option>
			                </select>
			            </span>
			            <span>&nbsp;专业：</span>
                        <select name="profession" style="width:120px;height:30px">
                            <option value="">请选择</option>
                            <?php
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
			            <span>&nbsp;班级：</span>
			            <input class="text-word3" type="text" size="6" name="class" value="<?php  echo isset($_GET['class'])?($_GET['class']):""; ?>">
			            <span>&nbsp;年级：</span>
			            <input class="text-word3" type="number" size="6" name="year" value="<?php  echo isset($_GET['year'])?($_GET['year']):""; ?>">
                        <span>&nbsp;状态：</span>
                        <span>
                            <select name="state" class="text-word" style="width:60px;font-size:12px;">
                                    <option value="" class="opt">全部</option>
                                    <option value="0" class="opt" <?php echo ($_GET['state']=='0')?"selected":""; ?>>启用</option>
                                    <option value="1" class="opt" <?php echo ($_GET['state']=='1')?"selected":""; ?>>禁用</option>
                            </select>
                        </span>
			            <input type="submit" value="搜索" class="text-but">
			            <input type="button" onclick="window.location='student.php'" value="全部" class="text-but">
			        </form>
			        <!--======================-->
			        <div>
                        <a href="addstu.php" target="iframe" class="add">新增学生</a>
                    </div>
				</div>
				<div id="content" name="content">
					<table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
						<tr class="top">
					        <th>序号</th>
                            <th>学号</th>
                            <th>姓名</th>
                            <th>性别</th>
                            <th>学院</th>
                            <th>专业</th>
                            <th>班级</th>
                            <th>年级</th>
                            <th>联系电话</th>
                            <th>qq</th>
                            <th>身份证号</th>
                            <th>状态</th>
                            <th>添加/修改时间</th>
                            <th>操作</th>
					    </tr>
					    <?php
				            date_default_timezone_set("PRC");//设置中国时区
				            //============封装搜索条件===================================
				            $wherelist = array();  //定义一个用于封装搜索条件的数组
				            $urllist = array();
				            //判断是否有名字搜索
				            if(!empty($_GET['stunum'])){
				                //搜索stunum编号里有某某数字
				                $wherelist[] = "stunum like '%{$_GET['stunum']}%'";
				                $urllist[] = "stunum={$_GET['stunum']}";

				            }
				            if(!empty($_GET['stuname'])){
				                //搜索名字
				                $wherelist[] = "stuname like '%{$_GET['stuname']}%'";
				                $urllist[] = "stuname={$_GET['stuname']}";
				            }
				            if(!empty($_GET['sex']) || $_GET['sex']=='0'){
				             $wherelist[]="sex='{$_GET['sex']}'";
				             $urllist[] = "sex={$_GET['sex']}";
				            }
                            if(!empty($_GET['state']) || $_GET['state']=='0'){
                             $wherelist[]="state='{$_GET['state']}'";
                             $urllist[] = "state={$_GET['state']}";
                            }
				            if(!empty($_GET['profession'])){
				                //搜索专业
				                $wherelist[] = "profession ='{$_GET['profession']}'";
				                $urllist[] = "profession={$_GET['profession']}";
				            }
				            if(!empty($_GET['class'])){
				                //搜索班级
				                $wherelist[] = "class like '%{$_GET['class']}%'";
				                $urllist[] = "class={$_GET['class']}";
				            }
				            if(!empty($_GET['year'])){
				                //搜索名字
				                $wherelist[] = "year = '{$_GET['year']}'";
				                $urllist[] = "year={$_GET['year']}";
				            }
				            //判断并拼装搜索条件
				            $where = "";
				            $url = "";
				            if(count($wherelist)>0){
				                $where  =" where ".implode(" and ",$wherelist);
				                $url = "&".implode("&",$urllist);
				            }

				            //=============================================================
				            //定义数组选择器
                            $sex = array(0=>"男",1=>"女");
				            $state = array(0=>"启用",1=>"禁用");

				            //==================做分页处理================================
				            //初始化变量
				            //$page = 1;  //当前那页(当前页)
				            $page = isset($_GET['page'])?$_GET['page']:1;  //当前页
				            $pagesize = 15;  //页大小
				            $maxrows = 0;   //总数据条数
				            $maxpages = 0;  //总页数

				            //获取总数据条数
				            $sql = "select * from student".$where;
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
				            //==================================================
				                //4.拼装SQL语句并发送服务器执行
                            $order=" order by stunum ";
				                $sql = "select * from student".$where.$order.$limit;
				                $result = mysqli_query($link,$sql);

				            //5.执行遍历解析输出操作
				            $num=0;
                            if(mysqli_num_rows($result)){
    				            while($row = mysqli_fetch_assoc($result)){
    				               $num++;
                                    echo '<tr class="middle">';
                                    echo '<td>'.(($page-1)*15+$num).'</td>';
                                    echo '<td>'.$row['stunum'].'</td>';
                                    echo '<td>'.$row['stuname'].'</td>';
                                    echo '<td>'.$sex[$row['sex']].'</td>';
                                    echo '<td>'.$row['college'].'</td>';
                                    echo '<td>'.$row['profession'].'</td>';
                                    echo '<td>'.$row['class'].'</td>';
                                    echo '<td>'.$row['year'].'</td>';
                                    echo '<td>'.$row['tel'].'</td>';
                                    echo '<td>'.$row['qq'].'</td>';
                                    echo '<td>'.$row['ident'].'</td>';
                                    if($row['state']==0){
                                        echo '<td>'.$state[$row['state']].'</td>';
                                    }else{
                                        echo '<td style="color:red;">'.$state[$row['state']].'</td>';
                                    }

                                    echo '<td>'.$row['time'].'</td>';
                                    echo '<td>
                                            <a href="editstu.php?id='.$row['ID'].'" target="iframe" class="link">查看/编辑</a>
                                                <span class="gray">&nbsp;|&nbsp;</span>
                                            <a href="javascript:dodel('.$row["ID"].')" target="" class="link red">删除</a>
                                                <span class="gray">&nbsp;|&nbsp;</span>
                                            <a href="repassstu.php?id='.$row['ID'].'" target="iframe" class="link">重置密码</a>
                                        </td>';
                                    echo '</tr>';
    				            }
                            }else{
                                echo '<tr class="middle" style="height:100px;"><td class="error-2" colspan="13">没有找到此信息</td></tr>';
                            }
				        ?>
					</table>

				</div>
                <div style="display:flex;justify-content: space-between;">
                    <div class="change">
                        <a href="all-addstu.php?profession=<?php echo $_GET['profession']; ?>&class=<?php echo $_GET['class'];?>&year=<?php echo $_GET['year']; ?>" target="iframe" style="padding-left:18px;background:url('./imgs/addinfoblack.jpg') no-repeat 0 1px ;">批量导入/删除</a>
                        <a href="javascript:change0()" target="">一键启用</a>
                        <a href="javascript:change1()" target="">一键禁用</a>
                        <span>(*只改变当前筛选后的结果，若不筛选默认全部)</span>
                        <a href="javascript:change()" target="" style="color:red;">一键重置密码</a>
                    </div>
                    <div align="left" valign="top" class="fenye" >
                        <?php
                            echo "当前第{$page}/{$maxpages}页，共计{$maxrows}条 ";
                            echo " <a href='student.php?page=1{$url}'>首页</a> ";
                            echo " <a href='student.php?page=".($page-1)."{$url}'>上一页</a> ";
                            echo " <a href='student.php?page=".($page+1)."{$url}'>下一页</a> ";
                            echo " <a href='student.php?page={$maxpages}{$url}'>末页</a> ";
                        ?>
                    </div>
                </div>
                <form name="form2" method="post" enctype="multipart/form-data" action="./download/export_excel_stu.php">
                    <input type="hidden" name="leadExcel" value="true">
                    <table align="center" width="100% " id="upload" border="0" class="repasstable">
                            <tr style="font-size:13px;font-weight: bold;">
                                <td>导出学生数据表</td>
                                <td>
                                    <span>&nbsp;专业：</span>
                                    <select name="profession" style="width:180px;height:30px">
                                        <option value="">请选择</option>
                                        <?php
                                            $sql_proinf = "select name from proinf order by code";
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
                                    <span>&nbsp;班级：</span>
                                    <input class="text-word3" type="text" size="6" name="class" value="<?php  echo isset($_GET['class'])?($_GET['class']):""; ?>" placeholder="请选择">
                                    <span>&nbsp;年级：</span>
                                    <input class="text-word3" type="number" size="6" name="year" value="<?php  echo isset($_GET['year'])?($_GET['year']):""; ?>" placeholder="请选择">&nbsp;&nbsp;&nbsp;
                                    <input type="submit" name="import" value="导 出 数 据 表" class="text-word4" style="background:#FFE793;cursor:pointer;font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;
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
    function dodel(id){
        if(confirm ("您确认删除吗？")){
            window.location="stuaction.php?type=delete&id="+id;
        }
    }
    function change0(){
        if(confirm ("您确认一键启用当前用户吗？")){
            window.location="stuaction.php?type=userstate&state=0&where=<?php echo $where; ?>";
        }
    }
    function change1(){
        if(confirm ("您确认一键禁用当前用户吗？")){
            window.location="stuaction.php?type=userstate&state=1&where=<?php echo $where; ?>";
        }
    }
    function change(){
        if(confirm ("您确认一键改密吗？")){
            window.location="stuaction.php?type=updatepass&where=<?php echo $where; ?>";
        }
    }
</script>