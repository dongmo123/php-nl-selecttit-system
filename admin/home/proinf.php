<?php
	session_start();//开启session会话
	//验证用户有没有登陆
    require("./public/error.php");
    if(!$_SESSION['adnumber']&&!$_SESSION['teachnum']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }

    header("Content-Type:text/html;charset=utf8");
?>
<!doctype html>
<html>
	<head>
		<title>信息管理</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/public.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div class="position">
					<span>您的位置:</span>
					<a href="proinf.php" target="iframe">>>专业信息设置</a>
					<a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
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
                <a href="all-addpro.php" target="iframe" class="" style="padding-left:18px;background:url('./imgs/addinfoblack.jpg') no-repeat 0 6px ;position:absolute;top:2px;left:450px;font-size:14px;line-height:33px;">批量导入专业信息</a>
                <!-- 打印当前页 -->
                <div id="hotinf">注意:特殊专业代码是选题限选专业的专用代码,'所有专业'用代码'all'表示,此代码不可删除,若限选专业两个以上用其专业代码合并表示,中间用'|'分开,不得有空格</div>
				<div id="content" name="content">
					<form action="infaction.php?type=insertpro" method="post" id="jsForm">
					<table cellspacing="0" cellpaddind="0" width="100%" class="biaoge">
						<tr class="top">
					        <th>序号</th>
					        <th>专业代码</th>
					        <th>专业名称</th>
                            <th>所属系别</th>
                            <th>所属学院</th>
					        <th>专业类型</th>
					        <th>添加/修改时间</th>
					        <th>操作</th>
					    </tr>
                        <tr class="pass-tr" style="margin-top:30px;background:#EDF5FF;">
                            <td class="pass-td1" style="text-align:center;font-size:20px;">+</td>
                            <td class="pass-td2"><input type="text" name="code" value="" class="text-word4 ajax-procode" placeholder="专业代码" required minlength="4"><span class="ajax-error"></span>
                                <span class="ajax-add"></span></td>
                            <td class="pass-td2"><input type="text" name="name" value="" class="text-word4" required style="width:150px;" placeholder="请输入全称,例:物联网工程" minlength="2"></td>
                            <td class="pass-td2"><input type="text" name="depart" value="" class="text-word4" style="width:100px;" required placeholder="例:网络工程系"></td>
                            <td class="pass-td2"><input type="text" name="college" value="" class="text-word4" required style="width:120px;" placeholder="例:电气信息工程学院"></td>
                            <td class="pass-td2"><select name="type" class="text-word4" style="width:120px;" ><option value="0">系专业</option><option value="1">选题专用专业</option></select></td>
                            <td class="pass-td3"><input type="submit" name="" value="添加" class="text-but2"></td>
                            <td class="pass-td3"><input type="reset" name="" value="重置" class="text-but2"></td>
                        </tr>
					    <?php
				            require("./public/config.php");
				            $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
				            mysqli_select_db($link,DBNAME);
				            mysqli_set_charset($link,"utf8");
                            $ztype=array(0=>"系专业",1=>"选题专用专业");
				            $sql = "select * from proinf order by type desc,time desc";
				            $result = mysqli_query($link,$sql);
				            $num=0;
				            while($row = mysqli_fetch_assoc($result)){
				                $num++;
								echo '<tr class="middle">';
								echo '<td>'.$num.'</td>';
								echo '<td>'.$row['code'].'</td>';
								echo '<td>'.$row['name'].'</td>';
                                echo '<td>'.$row['depart'].'</td>';
                                echo '<td>'.$row['college'].'</td>';
								echo '<td>'.$ztype[$row['type']].'</td>';
								echo '<td>'.$row['time'].'</td>';
								echo '<td>
					                    <a href="editpro.php?id='.$row['ID'].'" target="iframe" class="link">编辑</a>
					                        <span class="gray">&nbsp;|&nbsp;</span>
					                    <a href="javascript:dodel('.$row["ID"].')" target="" class="link red">删除</a>
				               		</td>';
				               	echo '</tr>';
				            }
				            //6.释放结果集并关闭数据库
				            mysqli_free_result($result);
				            mysqli_close($link);
				        ?>
					</table>
					</form>

				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript">
    function dodel(id){
        if(confirm ("您确认删除吗？")){
            window.location="infaction.php?type=deletepro&id="+id;
        }
    }
</script>
<!-- 引用jquery.validate表单验证框架 -->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="js/jquery.zzcheck.js"></script>
<script type="text/javascript" charset="utf-8">
    $(function(){
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