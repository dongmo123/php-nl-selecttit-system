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
    $sql_proinf = "select name from proinf order by code";
    $result_proinf = mysqli_query($link,$sql_proinf);
    $sql_proinf_dep = "select college from proinf group by college order by code";
    $result_proinf_dep = mysqli_query($link,$sql_proinf_dep);
?>
<!doctype html>
<html>
	<head>
		<title>添加学生</title>
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
					<a href="student.php" target="iframe">>>学生管理</a>
					<span>>添加学生</span>
					<a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="addstu.php" target="iframe" class="add2">新增学生</a>
			    </div>
				<div id="content" name="content">
					<form action="stuaction.php?type=insert" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>学生学号：</td>
							<td>
								<input type="number" name="stunum" value="" class="text-word2 ajax-stunum" placeholder="请输入8位以上的学号,例:44213120" required data-rule-usernumber="true" data-msg-required="请输入学生学号" data-msg-usernumber="请输入正确格式,合法字符:数字" minlength="8" maxlength="15">
								<span class="ajax-error">该学号已存在,请重新填写</span>
                                <span class="ajax-add">可以使用</span>
							</td>
	    				</tr>
	    				<tr>
							<td>学生姓名：</td>
							<td><input type="text" name="stuname" value="" class="text-word2" required placeholder="请输入学生真实姓名"></td>
	    				</tr>
	    				<tr>
							<td>密码：</td>
							<td><input type="password" name="password" value="" class="text-word2" placeholder="请输入四位以上的密码" required data-rule-password="true" data-msg-required="请输入密码" data-msg-password="请输入正确格式,合法字符:数字 大小写字母 _!@#$%&." minlength="4" maxlength="20" id="password"></td>
	    				</tr>
	    				<tr>
							<td>确认密码：</td>
							<td><input type="password" name="repassword" value="" class="text-word2" placeholder="确认新密码" required equalTo="#password"></td>
	    				</tr>
	    				<tr>
							<td>性别：</td>
							<td><input type="radio" name="sex" value="0" class="" checked> 男 <input type="radio" name="sex" value="1" class=""> 女 </td>
	    				</tr>
	    				<tr>
							<td>学院：</td>
							<td>
                                <select name="college" required style="width:150px;height:30px">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_proinf_dep)>0){
                                            while($row_proinf_dep = mysqli_fetch_array($result_proinf_dep)){
                                               echo '<option value="'.$row_proinf_dep['college'].'"';
                                               echo '>'.$row_proinf_dep['college'].'</option>';
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
                                <select name="profession" required style="width:150px;height:30px">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_proinf)>0){
                                            while($row_proinf = mysqli_fetch_array($result_proinf)){
                                               echo '<option value="'.$row_proinf['name'].'"';
                                               echo '>'.$row_proinf['name'].'</option>';
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
							<td><input type="text" name="class" value="" class="text-word2" required placeholder="例:13101"></td>
	    				</tr>
	    				<tr>
							<td>年级/届：</td>
							<td><input type="number" name="year" value="" class="text-word2" minlength="4" required maxlength="4" placeholder="例:2013"></td>
	    				</tr>
	    				<tr>
							<td>联系电话：</td>
							<td><input type="number" name="tel" value="" class="text-word2" placeholder="手机号11位" data-rule-tel="true" data-msg-required="请输入手机号" data-msg-tel="请输入正确格式"></td>
	    				</tr>
	    				<tr>
							<td>qq：</td>
							<td><input type="number" name="qq" value="" class="text-word2" minlength="5" maxlength="12" placeholder="可不填"></td>
	    				</tr>
	    				<tr>
							<td>电子邮箱：</td>
							<td><input type="text" name="email" value="" class="text-word2" placeholder="请输入email地址,可不填" data-rule-email="true" data-msg-required="请输入email地址" data-msg-email="请输入正确的email地址"></td>
	    				</tr>
	    				<tr>
							<td>身份证：</td>
							<td><input type="text" name="ident" value="" class="text-word2" placeholder="可不填" data-rule-ident="true" data-msg-required="请输入email地址" data-msg-ident="请输入正确的email地址"></td>
	    				</tr>
                        <tr>
                            <td>状态：</td>
                            <td><input type="radio" name="state" value="0" class="" checked> 启用 <input type="radio" name="state" value="1" class=""> 禁用 </td>
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
<script type="text/javascript" charset="utf-8">
    $(function(){
        $('.ajax-stunum').keyup(function(){
            ajaxcheck();
        });
        //二次检测是否同名,保证数据正确
        $('.ajax-stunum').mouseout(function(){
            ajaxcheck();
        });
        function ajaxcheck(){
            var stuvalue=$('.ajax-stunum').val();
            if(stuvalue!==''){
                $.get('ajax-stunum.php','stunum='+stuvalue,function(data){
                    //console.log(stuvalue+'---'+data);
                    if(data==stuvalue){
                        $('.ajax-error').show();
                        $('.ajax-add').hide();
                    }else{
                        $('.ajax-error').hide();
                        $('.ajax-add').show();
                    }
                });
            }else{
                $('.ajax-error').hide();
                $('.ajax-add').hide();
            }
        }
        $('.ajax-reset').click(function(){
            $('.ajax-error').hide();
            $('.ajax-add').hide();
        });
    });
</script>