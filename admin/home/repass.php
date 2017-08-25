<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    require("./public/error.php");
    //=====获取要修改的信息==========
    //1.导入配置文件
    require("./public/config.php");
    //2.连接数据库，并判断是否连接成功
    $link = @mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您的数据库连接失败，请重新配置连接。");
    //3.选择数据库并设置字符集
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
    //4.定义查询SQL语句，并执行
    $sql = "select * from admin where id=".($_GET['id']+0);
    $result = mysqli_query($link,$sql);
    //5.解析结果集
    if(mysqli_num_rows($result)>0){
        $users = mysqli_fetch_assoc($result);
        //6.释放结果集
        mysqli_free_result($result);
    }else{
        die("对不起，没有找到您要修改的数据。非常抱歉");
    }
    //7.关闭数据库
    mysqli_close($link);
?>
<!doctype html>
<html>
	<head>
		<title>密码重置</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position2">
					<span>您的位置:</span>
					<a href="admin.php" target="iframe">用户管理</a>
					<a href="admin.php" target="iframe">管理员管理</a>
					<span>>密码重置</span>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="addadmin.php" target="iframe" class="add2">新增管理员</a>
			    </div>
				<div id="content" name="content">
				<form action="addaction.php?type=repassword" method="post" id="jsForm">
					<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr class="pass-tr">
							<td class="pass-td1">原始密码：</td>
							<td class="pass-td2"><input type="text" name="pastpass" value="" class="text-word2" placeholder="请输入原密码" required></td>;
	    				</tr>
	    				<tr class="pass-tr">
							<td class="pass-td1">修改密码：</td>
							<td class="pass-td2"><input type="password" name="password" value="" class="text-word2" placeholder="请输入六位以上的新密码" required data-rule-password="true" data-msg-required="请输入密码" data-msg-password="请输入正确格式,合法字符:数字 大小写字母 _!@#$%&." minlength="4" maxlength="20" id="password"></td>
	    				</tr>
	    				<tr class="pass-tr">
							<td class="pass-td1">确认密码：</td>
							<td class="pass-td2"><input type="password" name="repassword" value="" class="text-word2" placeholder="确认新密码" required equalTo="#password"></td>
	    				</tr>
	    				<tr  class="pass-tr">
							<td class="pass-td1"></td>
							<td class="pass-td2 flex">
								<input name="" type="submit" value="修  改" class="text-but2">
								<input name="" type="reset" value="重  置" class="text-but2">
								<div class="text-but2" style="border:none;"><a href="addaction.php?type=initial&id=<?php echo $_GET['id']?>" target="iframe" style="background:#FFE793;padding:5px;">重置为初始密码</a></div>(管理员初始密码为admin)
							</td>
	    				</tr>
					</table>
				</form>
				</div>
			</div>
		</div>
	</body>
</html>
<!-- 引用jquery.validate表单验证框架 -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/jquery.zzcheck.js"></script>