<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['stunum']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    error_reporting(E_ALL & ~E_NOTICE);
        //=====获取要修改的信息==========
        //1.导入配置文件
        require("./public/config.php");
        //2.连接数据库，并判断是否连接成功
        $link = @mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您的数据库连接失败，请重新配置连接。");
        //3.选择数据库并设置字符集
        mysqli_select_db($link,DBNAME);
        mysqli_set_charset($link,"utf8");
        //4.定义查询SQL语句，并执行
        $sql = "select * from student where stunum='{$_SESSION['stunum']}'";
        $result = mysqli_query($link,$sql);

        //5.解析结果集
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            //6.释放结果集
            mysqli_free_result($result);
        }else{
            die("对不起，没有找到您要修改的数据。非常抱歉");
        }
        //专业名称,添加了限制，只能选择系专业
        $sql_pro = "select name from proinf where type=0 order by code";
        $result_pro = mysqli_query($link,$sql_pro);
        //学院名称
        $sql_coll = "select college from proinf group by college";
        $result_coll = mysqli_query($link,$sql_coll);
        //7.关闭数据库
        mysqli_close($link);
    ?>
<!doctype html>
<html>
	<head>
		<title>修改学生信息</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position2">
					<span>您的位置:</span>
					<a href="myinf.php" target="iframe">个人中心</a>
					<a href="myinf.php" target="iframe">>>修改资料</a>
					<span>>修改我的信息</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="hotinf">
			   		选题流程：①认真填写个人信息-->②浏览选题-->③选择自己感兴趣的选题-->④等待导师确选-->⑤确选后联系指导老师完成毕业设计
			    </div>
				<div id="content" name="content">
					<form action="myaction.php?type=myinfupdate&id=<?php echo $row['ID'];?>" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
						<tr>
							<td>学生ID：</td>
							<td><input type="text" name="id" value="<?php echo $row['ID']; ?>" class="text-word2" disabled>
								<span>(*唯一标识,不可修改!)</span>
							</td>
	    				</tr>
	    				<tr>
							<td>学生学号：</td>
							<td><input type="text" name="stunum" value="<?php echo $row['stunum']; ?>" class="text-word2" disabled><span>(*不可修改,若学号错误可留言给管理员或告知班主任)</span></td>
	    				</tr>
	    				<tr>
							<td>学生姓名：</td>
							<td><input type="text" name="stuname" value="<?php echo $row['stuname']; ?>" class="text-word2" required placeholder="请输入学生真实姓名"></td>
	    				</tr>
	    				<tr>
							<td>性别：</td>
							<td>
								<input type="radio" name="sex" value="0" class="" <?php echo ($row['sex']=='0')?"checked":""; ?>> 男
								<input type="radio" name="sex" value="1" class="" <?php echo ($row['sex']=='1')?"checked":""; ?>> 女
							</td>
	    				</tr>
	    				<tr>
							<td>密码：</td>
							<td><input type="password" name="password" value="" class="text-word2" placeholder="请输入六位以上的密码" required data-rule-password="true" data-msg-required="请输入密码" data-msg-password="请输入正确格式,合法字符:数字 大小写字母 _!@#$%&." minlength="6" maxlength="20" id="password"></td>
	    				</tr>
	    				<tr>
							<td>确认密码：</td>
							<td><input type="password" name="repassword" value="" class="text-word2" placeholder="确认新密码" required equalTo="#password"></td>
	    				</tr>
	    				<tr>
							<td>学院：</td>
							<td>
                                <select name="college"  class="text-word2">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_coll)>0){
                                            while($row_coll = mysqli_fetch_array($result_coll)){
                                               echo '<option value="'.$row_coll['college'].'"';
                                               echo ($row_coll['college']==$row['college'])?"selected":"";
                                               echo '>'.$row_coll['college'].'</option>';
                                            }
                                        }else{
                                            echo "<option value=\"\">数据字典连接错误</option>";
                                        }
                                    ?>
                                </select>
                            </td>
	    				</tr>
	    				<tr>
							<td>专业：</td>
							<td>
                                <select name="profession"  class="text-word2">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_pro)>0){
                                            while($row_pro = mysqli_fetch_array($result_pro)){
                                               echo '<option value="'.$row_pro['name'].'"';
                                               echo ($row_pro['name']==$row['profession'])?"selected":"";
                                               echo '>'.$row_pro['name'].'</option>';
                                            }
                                        }else{
                                            echo "<option value=\"\">数据字典连接错误</option>";
                                        }
                                    ?>
                                </select>
                            </td>
	    				</tr>
	    				<tr>
							<td>班级：</td>
							<td><input type="number" name="class" value="<?php echo $row['class']; ?>" class="text-word2" required placeholder="例：13101"></td>
	    				</tr>
	    				<tr>
							<td>年级/届：</td>
							<td><input type="number" name="year" value="<?php echo $row['year']; ?>" class="text-word2" minlength="4" required maxlength="4"></td>
	    				</tr>
						<tr>
							<td>联系电话：</td>
							<td><input type="number" name="tel" value="<?php echo $row['tel']; ?>" class="text-word2" placeholder="请务必填写有效联系方式" required data-rule-tel="true" data-msg-required="请输入手机号" data-msg-tel="请输入正确格式"></td>
	    				</tr>
	    				<tr>
							<td>qq：</td>
							<td><input type="number" name="qq" value="<?php echo $row['qq']; ?>" class="text-word2" minlength="5" maxlength="12" required placeholder="必填"></td>
	    				</tr>
	    				<tr>
							<td>电子邮箱：</td>
							<td><input type="text" name="email" value="<?php echo $row['email']; ?>" class="text-word2" required placeholder="请输入email地址" data-rule-email="true" data-msg-required="请输入email地址" data-msg-email="请输入正确的email地址"></td>
	    				</tr>
	    				<tr>
							<td>身份证号：</td>
							<td><input type="text" name="ident" value="<?php echo $row['ident']; ?>" class="text-word2" required placeholder="请认真填写身份证号" data-rule-ident="true" data-msg-required="请输入身份证号" data-msg-ident="请输入正确的身份证格式"></td>
	    				</tr>
                        <tr>
                            <td>家庭住址：</td>
                            <td><input type="text" name="address" value="<?php echo $row['address']; ?>" class="text-word2" required placeholder="目前家庭具体住址"></td>
                        </tr>
	    				<tr>
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