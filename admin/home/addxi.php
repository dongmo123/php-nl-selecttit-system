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
		<title>添加系别</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position2">
					<span>您的位置:</span>
					<a href="xiset.php" target="iframe">系院设置</a>
					<a href="xiset.php" target="iframe">>>系别设置</a>
					<span>>添加系别</span>
					<a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="addxi.php" target="iframe" class="add2">新增系别</a>
			    </div>
				<div id="content" name="content">
					<form action="xiaction.php?type=insert" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>系别代码：</td>
							<td><input type="text" name="code" value="" class="text-word2 ajax-code" placeholder="例:001" required data-rule-xicode="true" data-msg-required="请输入系院代码" data-msg-xicode="请输入正确格式,合法字符:数字、大小写字母" minlength="3" maxlength="12"><span class="ajax-error">已存在,请重新填写</span>
                                <span class="ajax-add">可以使用</span></td>
	    				</tr>
	    				<tr>
							<td>系别名称：</td>
							<td><input type="text" name="xiname" value="" class="text-word2" required placeholder="例:网络工程所"></td>
	    				</tr>
	    				<tr>
							<td>系院负责人：</td>
							<td><input type="text" name="head" value="" class="text-word2" required placeholder="请填入真实姓名" minlength="2"></td>
	    				</tr>
						<tr>
							<td>负责人联系电话：</td>
							<td><input type="text" name="tel" value="" class="text-word2" placeholder="手机号11位" required data-rule-tel="true" data-msg-required="请输入手机号" data-msg-tel="请输入正确格式"></td>
	    				</tr>
	    				<tr>
							<td>qq：</td>
							<td><input type="number" name="qq" value="" class="text-word2" minlength="5" maxlength="12" placeholder="可不填"></td>
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
        $('.ajax-code').keyup(function(){
            ajaxcheck();
        });
        //二次检测是否同名,保证数据正确
        $('.ajax-code').mouseout(function(){
            ajaxcheck();
        });
        function ajaxcheck(){
            var titvalue=$('.ajax-code').val();
            if(titvalue!==''){
                $.get('ajax-xicode.php','code='+titvalue,function(data){
                    //console.log(titvalue+'---'+data);
                    if(data==titvalue){
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