<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    require("./public/error.php");
    header("Content-Type:text/html;charset=utf8");
    require("./public/config.php");
    $link = @mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您的数据库连接失败，请重新配置连接。");
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
    $sql_proinf = "select depart from proinf group by depart order by code";
    $result_proinf = mysqli_query($link,$sql_proinf);
    $sql_teachinf = "select name from teachinf order by code";
    $result_teachinf = mysqli_query($link,$sql_teachinf);
    mysqli_close($link);
?>
<!doctype html>
<html>
	<head>
		<title>添加教师</title>
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
					<a href="teacher.php" target="iframe">>>教师管理</a>
					<span>>添加教师</span>
					<a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="addteacher.php" target="iframe" class="add2">新增教师</a>
			    </div>
				<div id="content" name="content">
					<form action="teachaction.php?type=insert" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>教师编号：</td>
							<td>
								<input type="text" name="teachnum" value="" class="text-word2 ajax-teachnum" placeholder="请输入四位以上的编号" required data-rule-usernumber="true" data-msg-required="请输入教师编号" data-msg-usernumber="请输入正确格式,合法字符:数字、大小写字母、_" minlength="4" maxlength="15">
								<span class="ajax-error">该教师编号已存在,请重新填写</span>
								<span class="ajax-add">可以使用</span>
							</td>
	    				</tr>
	    				<tr>
							<td>教师姓名：</td>
							<td><input type="text" name="teachname" value="" class="text-word2" required placeholder="请输入真实姓名"></td>
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
							<td>职称：</td>
							<td>
                                <select name="profession" style="width:180px;height:30px">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_teachinf)>0){
                                            while($row_teachinf = mysqli_fetch_array($result_teachinf)){
                                               echo '<option value="'.$row_teachinf['name'].'"';
                                               echo '>'.$row_teachinf['name'].'</option>';
                                            }
                                        }else{
                                            echo "<option value=\"\">数据字典连接错误</option>";
                                        }
                                    ?>
                                </select>
                            </td>
	    				</tr>
	    				<tr>
							<td>所在系：</td>
							<td>
                                <select name="position" style="width:180px;height:30px">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_proinf)>0){
                                            while($row_proinf = mysqli_fetch_array($result_proinf)){
                                               echo '<option value="'.$row_proinf['depart'].'"';
                                               echo '>'.$row_proinf['depart'].'</option>';
                                            }
                                        }else{
                                            echo "<option value=\"\">数据字典连接错误</option>";
                                        }
                                    ?>
                                </select>
                            </td>
	    				</tr>
						<tr>
							<td>联系电话：</td>
							<td><input type="number" name="tel" value="" class="text-word2" placeholder="请输入11位手机号" required data-rule-tel="true" data-msg-required="请输入手机号" data-msg-tel="请输入正确格式"></td>
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
							<td>限带人数：</td>
							<td><input type="number" name="leadnum" value="" class="text-word2" required></td>
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
        $('.ajax-teachnum').keyup(function(){
            ajaxcheck();
        });
        //二次检测是否同名,保证数据正确
        $('.ajax-teachnum').mouseout(function(){
            ajaxcheck();
        });
        function ajaxcheck(){
            var ajaxvalue=$('.ajax-teachnum').val();
            if(ajaxvalue!==''){
                $.get('ajax-teachnum.php','teachnum='+ajaxvalue,function(data){
                    //console.log(ajaxvalue+'---'+data);
                    if(data==ajaxvalue){
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