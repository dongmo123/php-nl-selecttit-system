<!doctype html>
<html>
	<head>
		<title>宁夏理工学院毕业论文选题系统</title>
		<meta charset="UTF-8">
		<link href="style/reset.css" rel="stylesheet" type="text/css">
		<link href="style/main.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		<div id="container">
			<div>
				<div>
					<div class="logo">
						<form action="./public/usersaction.php?element=welcome" method="post">
							<h1>宁夏理工学院毕业论文选题系统</h1>
							<div class="main">
								<div class="logo-img" style="position:relative;left:15%;">
									<img src="imgs/logo.png">
									<span style="color:#333;position:absolute;left:10px;top:80%;">
										<?php
											error_reporting(E_ALL & ~E_NOTICE);
										    header("Content-Type:text/html;charset=utf8");
										    require("./public/config.php");
										    $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
										    mysqli_select_db($link,DBNAME);
										    mysqli_set_charset($link,"utf8");
										    //调查是否向学生开放系统
		                                    $sql = "select name,dispark from department";
		                                    $result = mysqli_query($link,$sql);
		                                    $row = mysqli_fetch_array($result);
		                                    if($row['dispark']==1){
		                                    	echo '*系统已向学生开放!<br>';
		                                    }else{
		                                    	echo '*系统暂未向学生开放！<br>';
		                                    }
		                                    echo '*当前为<span style="color:red;">['.$row['name'].']</span>选题时间，非该学院学生禁止进入系统!';
										?>

									</span>
								</div>
								<ul class="login">
									<li>
										<span>学号/编号：</span>
										<input class="number" type="text" name="usernumber" width="100" vlaue="" placeholder="请输入学号/编号">
										<span></span>
									</li>
									<li>
										<span>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</span>
										<input class="number" type="password" name="password" vlaue="" placeholder="请输入密码">
										<span></span>
									</li>
									<li style="line-height:30px;">
										<span>验&nbsp;&nbsp;证&nbsp;&nbsp;码：</span>
										<input type="text" size="6" maxlength="4" name="onlycode" class="number">
										<span class="yanzheng"><img src="public/code.php"  onclick="this.src='public/code.php?id='+Math.random()"></span>

									</li>
									<li>
										<div><input type="submit" name="submit" value="登录" class="but"></div>&nbsp;&nbsp;&nbsp;&nbsp;
										<div><input type="reset" name="submit" value="重置" class="but"></div>
									</li>
								</ul>
							</div>
						</form>

					</div>
					<div style="text-align:center;margin-top:30px;font-size:13px;color:white;">©2016-2017 王禹 All Rights Resvered</div>
				</div>
			</div>
		</div>
	</body>
</html>