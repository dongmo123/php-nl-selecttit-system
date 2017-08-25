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
        //2.连接数据库，并判断是否连接成功
        $link = @mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您的数据库连接失败，请重新配置连接。");
        //3.选择数据库并设置字符集
        mysqli_select_db($link,DBNAME);
        mysqli_set_charset($link,"utf8");
        //4.定义查询SQL语句，并执行
        $sql = "select * from selecttitle where id=".($_GET['id']+0);
        $result = mysqli_query($link,$sql);
        $sql_pro = "select * from proinf order by code";
        $result_pro = mysqli_query($link,$sql_pro);
        $sql_teach = "select teachnum,teachname from teacher order by teachnum";
        $result_teach = mysqli_query($link,$sql_teach);
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
					<span>>编辑选题</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
					<a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<div id="addinfo">
			   		<a href="addtit.php" target="iframe" class="add2">新增选题</a>
			    </div>
				<div id="content" name="content">
					<form action="titaction.php?type=update&id=<?php echo $_GET['id'];?>" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
	    				<tr>
							<td>选题名称：</td>
							<td><input type="text" name="titname" value="<?php echo $row['titname']; ?>" class="text-word2 ajax-titname"><span class="ajax-error">修改的选题名称已存在,请重新填写</span><?php if($row['pass']=='1'){echo " 已审核通过,不可更改!";} ?>
                                <span class="ajax-add">可以使用</span></td>

	    				</tr>
	    				<tr>
                            <td>指导老师：</td>
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
							<td>限选专业：</td>
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
								<select name="direction" class="" style="width:180px;height:30px" required>
				                        <option value="" <?php echo ($row['direction']=='')?"selected":""; ?>>选题方向</option>
				                        <option value="工程设计" <?php echo ($row['direction']=='工程设计')?"selected":""; ?>>工程设计</option>
				                        <option value="技术开发" <?php echo ($row['direction']=='技术开发')?"selected":""; ?>>技术开发</option>
				                        <option value="软件工程" <?php echo ($row['direction']=='软件工程')?"selected":""; ?>>软件工程</option>
				                        <option value="理论研究和方法应用" <?php echo ($row['direction']=='理论研究和方法应用')?"selected":""; ?>>理论研究和方法应用</option>
				                        <option value="管理模式设计" <?php echo ($row['direction']=='管理模式设计')?"selected":""; ?>>管理模式设计</option>
				                        <option value="其他" <?php echo ($row['direction']=='其他')?"selected":""; ?>>其他</option>
				                </select>
							</td>
	    				</tr>
	    				<tr>
							<td>选题难度：</td>
							<td>
								<select name="difcode" class="" style="width:180px;height:30px" required>
			                        <option value="" <?php echo ($row['difcode']=='0')?"selected":""; ?>>选题难度</option>
			                        <option value="level01" <?php echo ($row['difcode']=='level01')?"selected":""; ?>>较容易</option>
			                        <option value="level02" <?php echo ($row['difcode']=='level02')?"selected":""; ?>>中等</option>
			                        <option value="level03" <?php echo ($row['difcode']=='level03')?"selected":""; ?>>较难</option>
			                        <option value="level04" <?php echo ($row['difcode']=='level04')?"selected":""; ?>>很难</option>
			                	</select>
							</td>
	    				</tr>
	    				<tr>
							<td>限选人数：</td>
							<td><input style="width:50px;" type="number" name="numlimit" value="<?php echo $row['numlimit']; ?>" class="text-word2" required></td>
	    				</tr>
	    				<tr>
							<td>是否确选：</td>
							<td><input type="radio" name="confirm" value="0" <?php echo ($row['confirm']=='0')?"checked":""; ?>>否<input type="radio" name="confirm" value="1" <?php echo ($row['confirm']=='1')?"checked":""; ?>>是</td>
	    				</tr>
	    				<tr>
							<td>审核状态：</td>
							<td><input type="radio" name="pass" value="0" <?php echo ($row['pass']=='0')?"checked":""; ?>>等待 <input type="radio" name="pass" value="1" <?php echo ($row['pass']=='1')?"checked":""; ?>>通过 <input type="radio" name="pass" value="2" <?php echo ($row['pass']=='2')?"checked":""; ?>>不合格</td>
	    				</tr>
	    				<tr>
							<td>选题要求：</td>
							<td><textarea class="text-word2" name="remark" placeholder="右下角可拉长文本框填写"><?php echo $row['remark']; ?></textarea></td>
	    				</tr>
	    				<tr >
							<td></td>
							<td>
								<input name="" type="submit" value="修  改" class="text-but2">
								<input name="" type="reset" value="重  置" class="text-but2">
                                <?php
                                    if($row['confirm']=='1'){
                                        echo '<a class="" style="background:#FFE793;cursor:pointer;font-weight:bold;color:red;padding:10px 20px;font-size:14px;margin-left:50px;" href="titaction.php?type=deltit&id='.$_GET['id'].'">强制退选</a>(请谨慎操作!强制退选已确选该选题的所有学生，预选该选题的数据不受影响)';
                                    }
                                ?>

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
        var yuantit=$('.ajax-titname').val();
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