<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    require("./public/error.php");
    header("Content-Type:text/html;charset=utf8");
	include("./public/conn.php");
?>
<!doctype html>
<html>
	<head>
		<title>添加管理员</title>
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
					<span>>添加管理员</span>
					<a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="addadmin.php" target="iframe" class="add2">新增管理员</a>
			    </div>
				<div id="content" name="content">
					<form action="addaction.php?type=insert" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>管理员编号：</td>
							<td><input type="text" name="adnumber" value="" class="text-word2 ajax-adnumber" placeholder="请输入四位以上的编号" required data-rule-usernumber="true" data-msg-required="请输入管理员编号" data-msg-usernumber="请输入正确格式,合法字符:数字、大小写字母、_" minlength="4" maxlength="15" id="adnumber"><span class="ajax-error">该管理员编号已存在,请重新填写</span><span class="ajax-add">可以添加</span></td>
	    				</tr>
	    				<tr>
							<td>管理员姓名：</td>
							<td><input type="text" name="adname" value="" class="text-word2" required placeholder="请输入真实姓名"></td>
	    				</tr>
	    				<tr class="pass-tr">
							<td>密码：</td>
							<td><input type="password" name="password" value="" class="text-word2" placeholder="请输入四位以上的密码" required data-rule-password="true" data-msg-required="请输入密码" data-msg-password="请输入正确格式,合法字符:数字 大小写字母 _!@#$%&." minlength="4" maxlength="20" id="password"></td>
	    				</tr>
	    				<tr class="pass-tr">
							<td>确认密码：</td>
							<td><input type="password" name="repassword" value="" class="text-word2" placeholder="确认新密码" required equalTo="#password"></td>
	    				</tr>
	    				<tr class="pass-tr">
							<td>性别：</td>
							<td><input type="radio" name="sex" value="0" class="" checked> 男 <input type="radio" name="sex" value="1" class=""> 女 </td>
	    				</tr>
						<tr class="pass-tr">
							<td>联系电话：</td>
							<td><input type="text" name="tel" value="" class="text-word2" placeholder="手机号11位" required data-rule-tel="true" data-msg-required="请输入手机号" data-msg-tel="请输入正确格式"></td>
	    				</tr>
	    				<tr class="pass-tr">
							<td>qq：</td>
							<td><input type="number" name="qq" value="" class="text-word2" minlength="5" maxlength="12" placeholder="可不填"></td>
	    				</tr>
	    				<tr class="pass-tr">
							<td>电子邮箱：</td>
							<td><input type="email" name="email" value="" class="text-word2" placeholder="请输入email地址,可不填" data-rule-email="true" data-msg-required="请输入email地址" data-msg-email="请输入正确的email地址"></td>
	    				</tr>
	    				<tr  class="pass-tr">
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
        $('.ajax-adnumber').keyup(function(){
            ajaxcheck();
        });
        //二次检测是否同名,保证数据正确
        $('.ajax-adnumber').mouseout(function(){
            ajaxcheck();
        });
        function ajaxcheck(){
            var ajaxval=$('.ajax-adnumber').val();
            if(ajaxval!==''){
                $.get('ajax-adnumber.php','adnumber='+ajaxval,function(data){
                    if(data==ajaxval){
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