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
        $sql = "select * from proinf where id=".($_GET['id']+0);
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
					<a href="proinf.php" target="iframe">>>专业信息设置</a>
					<span>>修改专业信息</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="proinf.php" target="iframe" class="add2">新增专业</a>
			    </div>
				<div id="content" name="content">
					<form action="infaction.php?type=updatepro&id=<?php echo $_GET['id'];?>" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>专业代码：</td>
							<td><input type="text" name="code" value="<?php echo $row['code']; ?>" class="text-word2 ajax-procode" placeholder="请填写标准代码" required><span class="ajax-error">修改的专业代码已存在,请重新填写</span>
                                <span class="ajax-add">可以使用</span></td>
	    				</tr>
	    				<tr>
							<td>专业名称：</td>
							<td><input type="text" name="name" value="<?php echo $row['name']; ?>" class="text-word2" required placeholder="例:物联网工程"></td>
	    				</tr>
	    				<tr>
							<td>所属系别：</td>
							<td><input type="text" name="depart" value="<?php echo $row['depart']; ?>" class="text-word2" required placeholder="例:网络工程所"></td>
	    				</tr>
                        <tr>
                            <td>所属学院：</td>
                            <td><input type="text" name="college" value="<?php echo $row['college']; ?>" class="text-word2" required placeholder="例:电气信息工程学院"></td>
                        </tr>
                        <tr>
                            <td>专业类型：</td>
                            <td><select name="type" class="text-word4"><option value="0" <?php echo ($row['type']==0)?"selected":"";?>>系专业</option><option value="1" <?php echo ($row['type']==1)?"selected":"";?>>选题专用专业</option></select></td>
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
        var yuantit=$('.ajax-procode').val();
        $('.ajax-procode').keyup(function(){
            ajaxcheck();
        });
        //二次检测是否同名,保证数据正确
        $('.ajax-procode').mouseout(function(){
            ajaxcheck();
        });
        function ajaxcheck(){
            var titvalue=$('.ajax-procode').val();
            if(titvalue!==''){
                $.get('ajax-procode.php','code='+titvalue,function(data){
                    //console.log(titvalue+'---'+data);
                    if(data!==titvalue||data==yuantit){
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