<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    require("./public/error.php");
    header("Content-Type:text/html;charset=utf8");
?>
<!doctype html>
<html>
	<head>
		<title>信息</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position">
					<span>您的位置:</span>
					<a href="teachinf.php" target="iframe">数据字典维护</a>
					<a href="titinf.php" target="iframe">>>选题难度信息设置</a>
					<a href="" class="refrash"><img src="imgs/refrash30px.png"></a>
				</div>
				<!-- 打印当前页 -->
                <script>
                    function myPrint(obj){
                        document.getElementsByClassName('biaoge')[0].border='1';
                        var newWindow=window.open("打印窗口","_blank");
                        var docStr = obj.innerHTML;
                        newWindow.document.write(docStr);
                        newWindow.document.close();
                        newWindow.print();
                        newWindow.close();
                        document.getElementsByClassName('biaoge')[0].border='0';
                    }
                </script>
                <button class="text-but2" style="position:absolute;top:2px;left:300px;" onclick="myPrint(document.getElementById('content'))">打 印</button>
                <!-- 打印当前页 -->
				<div id="content" name="content">
					<form action="infaction.php?type=inserttit" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
						<tr class="top">
					        <th>序号</th>
					        <th>选题难度编号</th>
					        <th>选题难度名称</th>
					        <th>添加/修改时间</th>
					        <th>操作</th>
					    </tr>
					    <?php
				            require("./public/config.php");
				            $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
				            mysqli_select_db($link,DBNAME);
				            mysqli_set_charset($link,"utf8");
				            $sql = "select * from titinf";
				            $result = mysqli_query($link,$sql);
				            $num=0;
				            while($row = mysqli_fetch_assoc($result)){
				                $num++;
								echo '<tr class="middle">';
								echo '<td>'.$num.'</td>';
								echo '<td>'.$row['code'].'</td>';
								echo '<td>'.$row['name'].'</td>';
								echo '<td>'.$row['time'].'</td>';
								echo '<td>
					                    <a href="edittit.php?id='.$row['ID'].'" target="iframe" class="link">编辑</a>
					                        <span class="gray">&nbsp;|&nbsp;</span>
					                    <a href="javascript:dodel('.$row["ID"].')" target="" class="link red">删除</a>
				               		</td>';
				               	echo '</tr>';
				            }

				            //6.释放结果集并关闭数据库
				            mysqli_free_result($result);
				            mysqli_close($link);
				        ?>
				        <tr style="margin-top:30px;">
							<td style="text-align:center;font-size:20px;">+</td>
							<td><input type="text" name="code" value="" class="text-word2" style="width:80px;" placeholder="例:level01" required minlength="7" maxlength="8"></td>
							<td><input type="text" name="name" value="" style="width:50px;" class="text-word2" required placeholder="例:中等"></td>
							<td><input type="submit" name="" value="添加" class="text-but2"></td>
							<td><input type="reset" name="" value="重置" class="text-but2"></td>
				        </tr>
					</table>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
    function dodel(id){
        if(confirm ("大人，您确认删除吗？")){
            window.location="infaction.php?type=deletetit&id="+id;
        }
    }
</script>
<!-- 引用jquery.validate表单验证框架 -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/jquery.zzcheck.js"></script>