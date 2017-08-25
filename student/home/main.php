<?php
	//header("Content-Type:text/html;charset=utf8")
	session_start();//开启session会话
	//验证用户有没有登陆
    if(!$_SESSION['stunum']){
        header("Location:../../index.php");
            exit();  //预防程序惯性输出
    }
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!doctype html>
<html>
	<head>
		<title>学生选题操作流程</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style/main.css">
		<link rel="stylesheet" type="text/css" href="style/reset.css">
	</head>
	<body>
		<div id="container">
			<div>
				<div id="title">
					<span>学生<?php echo "[";echo $_SESSION['stuname'];echo "]";?>
					</span>
					&nbsp;&nbsp;
					<span>您好,欢迎使用毕业论文选题系统！ 学生选题操作流程如下:</span>
				</div>
				<table id="table-main" cellspacing="0">
					<tr class="import">
						<td>重要说明一</td>
						<td>凡有实物(含IT类)作品的毕业设计，答辩后必须将实物作品上交给学院，若被评为院级优秀毕业设计，学院将按照一定比例给予报销实物作品耗材费用(发票齐全)；若只交论文不交作品的，将按毕业设计不完整处理(包括编程类的源代码刻盘上交)。</td>
						<td>注意</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr class="import">
						<td>重要说明二</td>
						<td>同学们登录系统后请修改密码和补全修改个人信息。学生最多可选三个预选选题，由老师确选。若选题被确选是自己的则无法再选题，若是别人的则可以重新选题。请刷新查看选题情况！</td>
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
						<td>进入个人中心,修改密码和个人资料(<span>请认真填写</span>)</td>
						<td></td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>课题浏览</td>
						<td>浏览“开始选题-选题列表”页面并选择自己感兴趣的课题。选题限选专业不符自己专业不可选题<span>最多可以选择三个候选选题,在个人中心的“我的选题”中可以查看或 <b>退选</b> 预选选题</span></td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>选题确选</td>
						<td>指导老师确选学生预选课题</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>查看选题情况</td>
						<td>若自己被确选请及时记载选题信息，若不是自己的预选课题已被别人确选可及时重新选题</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>论文制作</td>
						<td>确选后主动联系指导老师，明确毕业论文设计要求，待导师下发任务书后准备制作毕业设计(论文)</td>
						<td>限时完成</td>
					</tr>
					<tr>
						<td>↓</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>留言建议</td>
						<td>可以在留言建议页面签写留言建议，请文明用语！</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td style="text-align:center;">系统设计:2017物联网工程-王禹-毕业设计</td>
						<td></td>
					</tr>
				</table>
				<div id="footer">
					<div>学生学号:<?php echo '['.$_SESSION['stunum'].']';?></div>
					<div>学生姓名:<?php echo '['.$_SESSION['stuname'].']';?></div>
					<div>位置:学生选题</div>
					<div>系统状态:开放中...</div>
					<div id="time">
						<script>setInterval("time.innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());",1000);
						</script>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>