<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../index.php");
        exit();  //预防程序惯性输出
    }
    include('./public/error.php');
?>
<!doctype html>
<html>
	<head>
		<meta name="renderer" content="webkit">
		<!-- 避免IE使用兼容模式 -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>选题系统后台</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/main.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div>
					<div class="logo">
						<img src="../admin/imgs/logo.png" alt="加载失败">
					</div>
					<div>
						<div>
							<span>宁夏理工学院</span>&nbsp;
							<span>
								<?php
									header("Content-Type:text/html;charset=utf8");
									include("../public/conn.php");
									 // 表示提示除去 E_NOTICE 之外的所有错误信息
									error_reporting(E_ALL & ~E_NOTICE);
									$sql="select year,name from department";
									$re=mysql_query($sql);
									$row=mysql_fetch_array($re);
									$year=$row['year'];
									$name=$row['name'];
									echo $name;
									echo '</span>&nbsp;&nbsp;<span>';
									echo $year.'届';
								?>
							</span>&nbsp;
							<span>毕业论文选题系统后台管理</span>
						</div>
						<div>后台管理系统</div>
					</div>
				</div>
				<ul class="nav1">
					<li>
						<span>
							<!-- 日历 -->
							<script type="text/javascript" src="js/clock.js"></script>
							<script type="text/javascript">showcal();</script>
						</span>
					</li>
					<li><a href="index.php">后台管理首页</a></li>
					<li><a href="./home/main.php" target="iframe">操作流程</a></li>
					<li><a href="javascript:dodel()">注销</a></li>
				</ul>
				<!-- 在线人数统计 -->
				<span style="position:absolute;right:20px;top:40px;text-align:right;">
					<?php
						$filename='online.txt';//数据文件
						$cookiename='VGOTCN_OnLineCount';//cookie名称
						$onlinetime=300;//在线有效时间，单位：秒 (即600等于10分钟)

						$online=file($filename);
						//PHP file() 函数把整个文件读入一个数组中。与 file_get_contents() 类似，不同的是 file() 将文件作为一个数组返回。数组中的每个单元都是文件中相应的一行，包括换行符在内。如果失败，则返回 false
						$nowtime=$_SERVER['REQUEST_TIME'];
						$nowonline=array();
						//得到仍然有效的数据
						foreach($online as $line){
						  $row=explode('|',$line);
						  $sesstime=trim($row[1]);
						  if(($nowtime - $sesstime)<=$onlinetime){//如果仍在有效时间内，则数据继续保存，否则被放弃不再统计
						    $nowonline[$row[0]]=$sesstime;//获取在线列表到数组，会话ID为键名，最后通信时间为键值
						  }
						}
						/*
						@创建访问者通信状态
						使用cookie通信
						COOKIE 将在关闭浏览器时失效，但如果不关闭浏览器，此 COOKIE 将一直有效，直到程序设置的在线时间超时
						*/
						if(isset($_COOKIE[$cookiename])){//如果有COOKIE即并非初次访问则不添加人数并更新通信时间
						  $uid=$_COOKIE[$cookiename];
						}else{//如果没有COOKIE即是初次访问
						  $vid=0;//初始化访问者ID
						  do{//给用户一个新ID
						    $vid++;
						    $uid='U'.$vid;
						  }while(array_key_exists($uid,$nowonline));
						  setcookie($cookiename,$uid);
						}
						$nowonline[$uid]=$nowtime;//更新现在的时间状态
						//统计现在在线人数
						$total_online=count($nowonline);
						//写入数据
						if($fp=@fopen($filename,'w')){
						  if(flock($fp,LOCK_EX)){
						    rewind($fp);
						    foreach($nowonline as $fuid=>$ftime){
						      $fline=$fuid.'|'.$ftime."\n";
						      @fputs($fp,$fline);
						    }
						    flock($fp,LOCK_UN);
						    fclose($fp);
						  }
						}
						//1.导入数据库配置文件
						require("./public/config.php");
						    require("./public/error.php");
						//2.连接mysqli数据库并检测是否连接成功
						$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());

						//3.选择数据库并设置字符集
						mysqli_select_db($link,DBNAME);
						mysqli_set_charset($link,"utf8");

						$sqlnum="update online set id=null,admin='{$total_online}'";
						mysqli_query($link,$sqlnum);

						$sqltnum="select teacher,admin,student from online";
						$resulttnum=mysqli_query($link,$sqltnum);
						$rowtnum=mysqli_fetch_array($resulttnum);
						echo '当前在线：学生'.($rowtnum['student']).'人<br>教师'.($rowtnum['teacher']).'人<br>管理员'.$total_online.'人';
					?>
				</span>
			</div>
			<div id="content" name="availHeight">
				<div class="left" id="left">
					<div class="left-top">
						<div><img src="imgs/member.png" width="44" height="44"></div>
						<div>
							<div>编号：<?php echo $_SESSION['adnumber'];?></div>
							<div>姓名：<?php echo $_SESSION['adname'];?></div>
						</div>
					</div>
					<ul class="sdmenu" id="mymenu">
						<li>
							<span class="on">用户管理</span>
							<div>
								<a href="home/admin.php" target="iframe">管理员管理</a>
								<a href="home/teacher.php" target="iframe">教师管理</a>
								<a href="home/student.php" target="iframe">学生管理</a>
							</div>
						</li>
						<li>
							<span class="on">系院设置</span>
							<div>
								<a href="home/departset.php" target="iframe">院设置</a>
								<a href="home/xiset.php" target="iframe">系别设置</a>
							</div>
						</li>
						<li>
							<span class="on">数据字典维护</span>
							<div>
								<a href="home/teachinf.php" target="iframe">教师职称信息设置</a>
								<a href="home/proinf.php" target="iframe">专业信息设置</a>
								<a href="home/titinf.php" target="iframe">选题难度信息设置</a>
							</div>
						</li>
						<li>
							<span class="on">选题管理</span>
							<div>
								<a href="home/selecttitle.php" target="iframe">选题信息管理</a>
								<a href="home/allstuinf.php" target="iframe">所有学生选题列表</a>
								<a href="home/tongji.php" target="iframe">选题情况统计</a>
								<a href="home/teachtj.php" target="iframe">教师选题工作统计</a>
							</div>
						</li>
						<li>
							<span class="on">留言建议</span>
							<div>
								<a href="home/message.php" target="iframe">查看/添加留言</a>
							</div>
						</li>
					</ul>
				</div>
				<div id="leftbar">
						<img onclick="switchbar(i++)" id="switchimg" src="imgs/pic23.jpg" width="12" height="72" border="0" title="隐藏左侧菜单" alt="加载失败,浏览器不兼容">
				</div>
				<div id="main" scrolling="no">
					<iframe id="iframe" name="iframe" src="./home/main.php" id="iframe" ></iframe>
				</div>
			</div>
		</div>
		<div id="footer">
				<div>如果显示异常,请用浏览器兼容模式或使用谷歌浏览器!不支持IE8及以下浏览器。
				</div>
		</div>
	</body>
</html>
<script type="text/javascript" src="js/getscreen.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript">
    function dodel(){
        if(confirm ("确认要注销吗？")){
            window.location="../public/usersaction.php?element=thanksadmin";
        }
    }
</script>