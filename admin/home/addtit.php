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
    $sql_teach = "select teachnum,teachname from teacher order by teachnum";
    $result_teach = mysqli_query($link,$sql_teach);
    $sql_pro = "select * from proinf order by code";
    $result_pro = mysqli_query($link,$sql_pro);
    mysqli_close($link);
?>
<!doctype html>
<html>
	<head>
		<title>添加选题</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position2">
					<span>您的位置:</span>
					<a href="selecttitle.php" target="iframe">选题管理</a>
					<span>>添加选题</span>
					<a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="addtit.php" target="iframe" class="add2">新增学生</a>
			    </div>
				<div id="content" name="content">
					<form action="titaction.php?type=insert" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>选题名称：</td>
							<td><input type="text" name="titname" value="" class="text-word2 ajax-titname" required placeholder=""><span class="ajax-error">选题名称已存在,请重新填写</span><span class="ajax-add">可以使用</span></td>
	    				</tr>
	    				<tr>
							<td>导师姓名：</td>
							<td>
                                <select name="teachnum" style="width:180px;height:30px">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_teach)>0){
                                            while($row_teach = mysqli_fetch_array($result_teach)){
                                               echo '<option value="'.$row_teach['teachnum'].'"';
                                               echo ($row_teach['teachnum']==$row['teachnum'])?"selected":"";
                                               echo '>'.$row_teach['teachname'].'</option>';
                                            }
                                        }else{
                                            echo "<option value=\"\">数据字典连接错误</option>";
                                        }
                                    ?>
                                </select>
                            </td>
	    				</tr>
	    				<tr>
							<td>所属专业：</td>
							<td>
                                <select name="procode" style="width:180px;height:30px">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_pro)>0){
                                            while($row_pro = mysqli_fetch_array($result_pro)){
                                               echo '<option value="'.$row_pro['code'].'"';
                                               echo ($row_pro['code']==$row['procode'])?"selected":"";
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
							<td>选题方向：</td>
							<td>
								<select name="direction" class="" style="width:180px;height:30px">
				                        <option value="">选题方向</option>
				                        <option value="工程设计">工程设计</option>
				                        <option value="技术开发">技术开发</option>
				                        <option value="软件工程">软件工程</option>
				                        <option value="理论研究和方法应用">理论研究和方法应用</option>
				                        <option value="管理模式设计">管理模式设计</option>
				                        <option value="其他">其他</option>
				                </select>
							</td>
	    				</tr>
	    				<tr>
							<td>选题难度：</td>
							<td>
								<select name="difcode" class="" style="width:180px;height:30px">
			                        <option value="">选题难度</option>
			                        <option value="level01">较容易</option>
			                        <option value="level02" selected>中等</option>
			                        <option value="level03">较难</option>
			                        <option value="level04">很难</option>
			                	</select>
							</td>
	    				</tr>
	    				<tr>
							<td>限选人数：</td>
							<td><input type="number" name="numlimit" value="1" class="text-word2" required></td>
	    				</tr>
	    				<tr>
							<td>是否确选：</td>
							<td><input type="radio" name="confirm" value="0" checked>否<input type="radio" name="confirm" value="1">是</td>
	    				</tr>
	    				<tr>
							<td>审核状态：</td>
							<td><input type="radio" name="pass" value="0" checked>等待 <input type="radio" name="pass" value="1">通过 <input type="radio" name="pass" value="2">不合格</td>
	    				</tr>
	    				<tr>
							<td>备注：</td>
							<td><textarea class="text-word2" name="remark" placeholder="选题要求,右下角可拉长文本框填写"></textarea></td>
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
        $('.ajax-titname').keyup(function(){
            ajaxcheck();
        });
        //二次检测是否同名,保证数据正确
        $('.ajax-titname').mouseout(function(){
            ajaxcheck();
        });
        function ajaxcheck(){
            var titvalue=$('.ajax-titname').val();
            if(titvalue!==''){
                $.get('ajax-titname.php','titname='+titvalue,function(data){
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