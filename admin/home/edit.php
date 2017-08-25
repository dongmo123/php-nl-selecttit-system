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
		<title>编辑用户</title>
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
					<a href="admin.php" target="iframe">>>管理员管理</a>
					<span>>编辑用户</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="addadmin.php" target="iframe" class="add2">新增管理员</a>
			    </div>
				<div id="content" name="content">
					<form action="addaction.php?type=update&id=<?php echo $_GET['id'];?>" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>管理员编号：</td>
							<td>
                                <input type="text" name="adnumber" value="<?php echo $row['adnumber']; ?>" class="text-word2 ajaxadnumber" placeholder="请输入四位以上的用户名" required data-rule-usernumber="true" data-msg-required="请输入管理员编号" data-msg-usernumber="请输入正确格式,合法字符:数字、大小写字母、_" minlength="4" maxlength="12">
                                <span class="ajax-error">修改的管理员编号已存在,请重新填写</span>
                                <span class="ajax-add">可以添加</span>
                            </td>
	    				</tr>
	    				<tr>
							<td>管理员姓名：</td>
							<td><input type="text" name="adname" value="<?php echo $row['adname']; ?>" class="text-word2" required placeholder="请输入真实姓名"></td>
	    				</tr>
	    				<tr>
							<td>性别：</td>
							<td>
								<input type="radio" name="sex" value="0" class="" <?php echo ($row['sex']=='0')?"checked":""; ?>> 男
								<input type="radio" name="sex" value="1" class="" <?php echo ($row['sex']=='1')?"checked":""; ?>> 女
							</td>
	    				</tr>
						<tr>
							<td>联系电话：</td>
							<td><input type="text" name="tel" value="<?php echo $row['tel']; ?>" class="text-word2" placeholder="手机号11位" required data-rule-tel="true" data-msg-required="请输入手机号" data-msg-tel="请输入正确格式"></td>
	    				</tr>
	    				<tr>
							<td>qq：</td>
							<td><input type="number" name="qq" value="<?php echo $row['qq']; ?>" class="text-word2" minlength="5" maxlength="12" placeholder="可不填"></td>
	    				</tr>
	    				<tr>
							<td>电子邮箱：</td>
							<td><input type="text" name="email" value="<?php echo $row['email']; ?>" class="text-word2" placeholder="请输入email地址,可不填" data-rule-email="true" data-msg-required="请输入email地址" data-msg-email="请输入正确的email地址"></td>
	    				</tr>
	    				<tr>
							<td>状态：</td>
							<td><input type="radio" name="state" value="1" class="" <?php echo ($row['state']=='1')?"checked":""; ?>> 启用 <input type="radio" name="state" value="0" class="" <?php echo ($row['state']=='0')?"checked":""; ?>> 禁用 </td>
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
<script type="text/javascript" charset="utf-8">
    $(function(){
        var yuanajax=$('.ajaxadnumber').val();
        $('.ajaxadnumber').keyup(function(){
            ajaxcheck();
        });
        //二次检测是否同名,保证数据正确
        $('.ajaxadnumber').mouseout(function(){
            ajaxcheck();
        });
        function ajaxcheck(){
            var ajaxval=$('.ajaxadnumber').val();
            if(ajaxval!==''){
                $.get('ajax-adnumber.php','adnumber='+ajaxval,function(data){
                    //console.log(ajaxval+'---'+data);
                    if(data!==ajaxval||data==yuanajax){
                        $('.ajax-error').hide();
                        $('.ajax-add').show();
                    }else{
                        $('.ajax-error').show();
                        $('.ajax-add').hide();
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