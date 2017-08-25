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

    //2.连接mysqli,并判断是否连接成功过
    $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");

    //3.选择连接数据库并配置字符集
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
?>
<!doctype html>
<html>
    <head>
        <title>批量导入选题</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position2">
                    <span>您的位置:选题管理</span>
                    <a href="selecttitle.php" target="iframe">>>选题信息管理</a>
                    <span>>批量导入/删除选题信息</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div id="addinfo">
                    <a href="addtit.php" target="iframe" class="add2">新增单个选题</a>
                </div>
                <div id="content" name="content">
                    <!-- 设置表单的MIME编码 -->
                    <form name="form2" method="post" enctype="multipart/form-data" action="upload_excel_tit.php">
                        <input type="hidden" name="leadExcel" value="true">
                        <table align="center" width="100% " id="upload" border="0" class="repasstable">
                            <tr>
                                <td>导入选题数据</td>
                                <td>
                                    <input type="file" name="filename"><input type="submit" name="import" value="导入数据 " class="text-word2" style="background:#FFE793;cursor:pointer;">
                                </td>
                            </tr>
                            <tr>
                                <td>表格导入注意事项</td>
                                <td>
                                    <span style="font-size:14px;color:red;">
                                        1.格式必须是.xls<br>
                                        2.表格文件大小不等大于2000000byte,大约2M,若文件过大建议分批导入<br>
                                        3.请严格按照模板样式表填充信息<br>
                                        4.选题名称,限选专业,指导老师等必填,任何字段不可删除<br>
                                        5.字段顺序严格按模板顺序,不可更改<br>
                                        6.导师编号以系统录入的教师编号为准<a href="teacher.php">  -->教师用户信息</a><br>
                                        7.选题难度代码以系统录入的选题难度信息为准<a href="titinf.php">  -->选题难度字典</a><br>
                                        8.限选专业代码以系统录入的专业字典为准<a href="proinf.php">  -->专业信息字典</a><br>
                                        9."是否确选"只有两种情况：是/否<br>
                                        10."审核状态"只有三种情况：等待/通过/不合格<br>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>模板样式表下载</td>
                                <td>
                                    <span style="width:50%;font-size:14px;">
                                        预览图:<img src="imgs/mubantit.png" style="width:80%;">
                                    </span>
                                    <div><a class="download" href="./download/files/导入选题模板.xls">下  载</a></div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>