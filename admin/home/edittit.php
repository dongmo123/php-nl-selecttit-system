<?php
	session_start();
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();
    }
    require("./public/error.php");
    header("Content-Type:text/html;charset=utf8");
        require("./public/config.php");
        $link = @mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您的数据库连接失败，请重新配置连接。");
        mysqli_select_db($link,DBNAME);
        mysqli_set_charset($link,"utf8");
        $sql = "select * from titinf where id=".($_GET['id']+0);
        $result = mysqli_query($link,$sql);
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        }else{
            die("对不起，没有找到您要修改的数据。非常抱歉");
        }
        mysqli_close($link);
?>
<!doctype html>
<html>
	<head>
		<title>修改</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position2">
					<span>您的位置:</span>
					<a href="teachinf.php" target="iframe">数据字典维护</a>
					<a href="titinf.php" target="iframe">>>选题难度信息设置</a>
					<span>>修改选题难度</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="teachinf.php" target="iframe" class="add2">新增选题难度</a>
			    </div>
				<div id="content" name="content">
					<form action="infaction.php?type=updatetit&id=<?php echo $_GET['id'];?>" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>选题难度编号：</td>
							<td><input type="text" name="code" value="<?php echo $row['code']; ?>" class="text-word2" placeholder="例:level01" required minlength="7" maxlength="8"></td>
	    				</tr>
	    				<tr>
							<td>选题难度名称：</td>
							<td><input type="text" name="name" value="<?php echo $row['name']; ?>" class="text-word2" required placeholder="例:中等"></td>
	    				</tr>
	    				<tr >
							<td></td>
							<td>
								<input name="" type="submit" value="添  加" class="text-but2">
								<input name="" type="reset" value="重  置" class="text-but2">
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