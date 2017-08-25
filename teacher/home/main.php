<?php
	//header("Content-Type:text/html;charset=utf8")
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['teachnum']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!doctype html>
<html>
	<head>
		<title>教师设选操作流程</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/main.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div id="title">
					<span>教师<?php echo "[";echo $_SESSION['teachname'];echo "]";?>
					</span>
					&nbsp;&nbsp;
					<span>您好,欢迎使用毕业论文选题系统！ 教师选题操作流程如下:</span>
				</div>
				<table id="table-main" cellspacing="0">
					<tr class="import">
						<td>重要说明一</td>
						<td>请导师在规定时间内添加选题(课题),全部添加完后等待教务管理员审核,若不合格请在规定时间内重新修改选题!</td>
						<td>注意</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr class="import">
						<td>重要说明二</td>
						<td>1.选题一旦通过审核将不得修改选题名称和删除该选题;2.学生一旦被确选,教师没有删除被确选学生的权利(若双方同意,可联系管理员强制删除!强制删除后请立即联系该学生重新选题)</td>
						<td>注意</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>操作</td>
						<td>说明</td>
						<td>备注</td>
					</tr>
					<tr>
						<td>修改资料</td>
						<td>进入个人中心,修改个人资料,重设密码(<span>请认真填写</span>)</td>
						<td></td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>添加选题(课题)</td>
						<td>按要求填写<span>若一个选题(课题)允许被多个专业选择,请选择"所有专业"或"某系所有专业"</span></td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>等待审核</td>
						<td>教务管理员审核选题(课题)</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>重新编辑选题</td>
						<td>若审核不通过,请立即重新编辑选题</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>选题确选</td>
						<td>教师登录,进入:开始选题->学生预选页面,确选学生(只能看到选择自己的学生)<span>一旦确选请不要修改该选题的任何信息</span></td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>通知学生</td>
						<td>下发任务书,告知毕业设计要求等等</td>
						<td></td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>导出统计表</td>
						<td>在"所有选题情况"功能栏里,打开"所有学生选题统计"页面,筛选"专业","导师",导出学生选题信息表</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td style="text-align:center;">系统设计:2013届-物联网工程专业-王禹</td>
						<td></td>
					</tr>
				</table>
				<div id="footer">
					<div>教师编号:<?php echo '['.$_SESSION['teachnum'].']';?></div>
					<div>教师姓名:<?php echo '['.$_SESSION['teachname'].']';?></div>
					<div>位置:教师确选</div>
					<div>系统状态:暂时开放中...</div>
					<div id="time">
						<script>setInterval("time.innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());",1000);
						</script>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>