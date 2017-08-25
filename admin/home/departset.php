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
        $sql = "select * from department where id=1";
        $result = mysqli_query($link,$sql);

        //5.解析结果集
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
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
		<title>院系设置</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position2">
					<span>您的位置:院系设置</span>
					<a href="departset.php" target="iframe">>>院设置</a>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="content" name="content">
					<form action="xiaction.php?type=departset&id=1" method="post" id="jsForm">
					<div class="title">当前标题:<span><span>&nbsp;&nbsp;<?php echo $row['name']; ?>&nbsp;&nbsp;</span><?php echo $row['year']; ?>届</span></div>
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>学院标题：</td>
							<td>
                                <input type="text" name="name" value="<?php echo $row['name']; ?>" class="text-word2"  maxlength="20" placeholder="如：电气信息工程学院，最多20字" required>
                            </td>
	    				</tr>
	    				<tr>
							<td>当前届：</td>
							<td><input type="number" name="year" value="<?php echo $row['year']; ?>" class="text-word2" minlength="4" maxlength="4" placeholder="2013" required></td>
	    				</tr>
	    				<tr>
							<td>是否向学生开放：</td>
							<td><input type="radio" name="dispark" value="1" class="" <?php echo ($row['dispark']=='1')?"checked":""; ?>> 是 <input type="radio" name="dispark" value="0" class="" <?php echo ($row['dispark']=='0')?"checked":""; ?>> 否 </td>
	    				</tr>
	    				<tr>
							<td>学生最多预选选题：</td>
							<td><input type="number" name="permit" value="<?php echo $row['permit']; ?>" class="text-word2" minlength="1" maxlength="5" placeholder="3" required>个选题/人</td>
	    				</tr>
                        <tr>
                            <td>学生留言审核：</td>
                            <td><input type="radio" name="pass" value="0" class="" <?php echo ($row['pass']=='0')?"checked":""; ?>> 需要审核 <input type="radio" name="pass" value="1" class="" <?php echo ($row['pass']=='1')?"checked":""; ?>> 无需审核 </td>
                        </tr>
                        <tr>
                            <td>教师选题审核：</td>
                            <td><input type="radio" name="titpass" value="0" class="" <?php echo ($row['titpass']=='0')?"checked":""; ?>> 需要审核 <input type="radio" name="titpass" value="1" class="" <?php echo ($row['titpass']=='1')?"checked":""; ?>> 无需审核 </td>
                        </tr>
	    				<tr>
							<td>上次修改时间</td>
							<td><?php echo $row['time']; ?></td>
	    				</tr>
	    				<tr >
							<td></td>
							<td>
								<input name="" type="submit" value="修  改" class="text-but2">
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