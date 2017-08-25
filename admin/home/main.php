<?php
	//header("Content-Type:text/html;charset=utf8")
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['adnumber']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    require("./public/error.php");
?>
<!doctype html>
<html>
	<head>
		<title>管理员操作流程</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/main.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div id="title">
					<span>管理员<?php echo "[";echo $_SESSION['adname'];echo "]";?>
					</span>
					&nbsp;&nbsp;
					<span>您好,欢迎使用毕业论文选题系统！ 管理员操作流程如下:</span>
				</div>
				<table id="table" cellspacing="0">
					<tr>
						<td>操作</td>
						<td>内容</td>
						<td>备注</td>
					</tr>
					<tr>
						<td>院设置</td>
						<td>设置系统标题、当前届、是否开放系统、学生预选选题个数信息的设定，请不要经常修改！</td>
						<td></td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>数据字典信息维护</td>
						<td>添加所有专业相关信息、教师职称信息、选题难度信息，一旦添加在开放学生端之后请不要改动！</td>
						<td></td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>开户</td>
						<td>批量导入标准学生用户表，教师用户表，若没有请先下载各类表模板审核，个别用户单独添加</td>
						<td></td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>选题审核</td>
						<td>要求教师登录系统添加选题数据。然后相关管理员审核选题</td>
						<td></td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>添加选题</td>
						<td>通知指导老师登录系统，添加、管理课题信息。</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>选题审核</td>
						<td>系(院)管理员登录后审核选题。</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>学生选题</td>
						<td>向开放系统，通知学生登录系统进行完善个人信息并选题</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>选题确选</td>
						<td>通知教师及时上线确选学生预选</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>导出统计</td>
						<td>管理员查看选题统计页面和用户信息页面，及时导出相关信息表，或备份</td>
						<td></td>
					</tr>
				</table>
				<div id="footer">
					<div>管理员编号:<?php echo '['.$_SESSION['adnumber'].']';?></div>
					<div>管理员姓名:<?php echo '['.$_SESSION['adname'].']';?></div>
					<div>位置:后台管理</div>
					<div id="time">
						<script>setInterval("time.innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());",1000);
						</script>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>