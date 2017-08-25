<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    require("./public/error.php");
    header("Content-Type:text/html;charset=utf8");
?>
<!doctype html>
<html>
	<head>
		<title>系别信息管理</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position">
					<span>您的位置:</span>
					<a href="departset.php" target="iframe">系院设置</a>
					<a href="xiset.php" target="iframe">>>系别设置</a>
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
					<form action="xiset.php" method="get">
			            <span>系别代码：</span>
			            <input class="text-word" type="text" size="6" name="code" value="<?php  echo isset($_GET['code'])?($_GET['code']):""; ?>">
			            <span>系别名称：</span>
			            <input class="text-word" type="text" size="6" name="xiname" value="<?php  echo isset($_GET['xiname'])?($_GET['xiname']):""; ?>">
			            <span>系院负责人：</span>
			            <input class="text-word" type="text" size="6" name="head" value="<?php  echo isset($_GET['head'])?($_GET['head']):""; ?>">
			            <input type="submit" value="搜索" class="text-but">
			            <input type="button" onclick="window.location='xiset.php'" value="全部" class="text-but">
			        </form>
			        <!--======================-->
			        <div><a href="addxi.php" target="iframe" class="add">新增系别</a></div>
				</div>
				<div id="content" name="content">
					<table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
						<tr class="top">
					        <th>序号</th>
					        <th>系别代码</th>
					        <th>系别名称</th>
					        <th>系院负责人</th>
					        <th>联系电话</th>
					        <th>qq</th>
					        <th>添加/修改时间</th>
					        <th>操作</th>
					    </tr>
					    <?php
				            date_default_timezone_set("PRC");//设置中国时区
				            //============封装搜索条件===================================
				            $wherelist = array();  //定义一个用于封装搜索条件的数组
				            $urllist = array();
				            //判断是否有名字搜索
				            if(!empty($_GET['code'])){
				                //搜索code编号里有某某数字
				                $wherelist[] = "code like '%{$_GET['code']}%'";
				                $urllist[] = "code={$_GET['code']}";

				            }
				            if(!empty($_GET['xiname'])){
				                //搜索名字
				                $wherelist[] = "xiname like '%{$_GET['xiname']}%'";
				                $urllist[] = "xiname={$_GET['xiname']}";

				            }
				            if(!empty($_GET['head'])){
				                //搜索名字
				                $wherelist[] = "head like '%{$_GET['head']}%'";
				                $urllist[] = "head={$_GET['head']}";

				            }
				            //判断并拼装搜索条件
				            $where = "";
				            $url = "";
				            if(count($wherelist)>0){
				                $where  =" where ".implode(" and ",$wherelist);
				                $url = "&".implode("&",$urllist);
				            }
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
				            $pagesize = 10;  //页大小
				            $maxrows = 0;   //总数据条数
				            $maxpages = 0;  //总页数

				            //获取总数据条数
				            $sql = "select * from xiset".$where;
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

				                //4.拼装SQL语句并发送服务器执行
				                $sql = "select * from xiset".$where.$limit;
				                $result = mysqli_query($link,$sql);

				            //5.执行遍历解析输出操作
				            $num=0;
				            while($row = mysqli_fetch_assoc($result)){
				                $num++;
								echo '<tr class="middle">';
								echo '<td>'.(($page-1)*10+$num).'</td>';
								echo '<td>'.$row['code'].'</td>';
								echo '<td>'.$row['xiname'].'</td>';
								echo '<td>'.$row['head'].'</td>';
								echo '<td>'.$row['tel'].'</td>';
								echo '<td>'.$row['qq'].'</td>';
								echo '<td>'.$row['time'].'</td>';
								echo '<td>
					                    <a href="editxi.php?id='.$row['ID'].'" target="iframe" class="link">查看/编辑</a>
					                        <span class="gray">&nbsp;|&nbsp;</span>
					                    <a href="javascript:dodel('.$row["ID"].')" target="" class="link red">删除</a>
				               		</td>';
				               	echo '</tr>';
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
				            echo " <a href='xiset.php?page=1{$url}'>首页</a> ";
				            echo " <a href='xiset.php?page=".($page-1)."{$url}'>上一页</a> ";
				            echo " <a href='xiset.php?page=".($page+1)."{$url}'>下一页</a> ";
				            echo " <a href='xiset.php?page={$maxpages}{$url}'>末页</a> ";
				        ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
    function dodel(id){
        if(confirm ("大人，您确认删除吗？")){
            window.location="xiaction.php?type=delete&id="+id;
        }
    }
</script>