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
        <title>批量导入/删除学生</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position2">
                    <span>您的位置:用户管理</span>
                    <a href="student.php" target="iframe">>>学生管理</a>
                    <span>>批量导入/删除学生信息</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div id="addinfo">
                    <a href="addstu.php" target="iframe" class="add2">新增学生</a>
                </div>
                <div id="content" name="content">
                    <!-- 设置表单的MIME编码 -->
                    <form name="form2" method="post" enctype="multipart/form-data" action="upload_excel.php">
                        <input type="hidden" name="leadExcel" value="true">
                        <table align="center" width="100% " id="upload" border="0" class="repasstable">
                            <tr>
                                <td>导入学生数据</td>
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
                                        4.学号,姓名,密码必填,其余若无信息则可不填,但字段不可删除<br>
                                        5.字段顺序严格按模板顺序,不可更改<br>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>模板样式表下载</td>
                                <td>
                                    <span style="width:50%;font-size:14px;">
                                        预览图:<img src="imgs/mubanstudent.png" style="width:80%;">
                                    </span>
                                    <div><a class="download" href="./download/files/导入学生模板.xls">下  载</a></div>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <form name="form3" method="post" enctype="multipart/form-data" action="stuaction.php?type=deleteall"  onsubmit="return sumbit_sure()">
                        <input type="hidden" name="leadExcel" value="true">
                        <table align="center" width="100% " id="upload" border="0" class="repasstable">
                            <tr>
                                <td>批量删除注意事项</td>
                                <td>
                                    <span style="font-size:14px;color:red;">
                                        1.一旦删除不可恢复,请谨慎操作<br>
                                        2.建议删除前备份导出数据<br>
                                        3.警告:若都不筛选,将全部删除!!!
                                    </span>
                                </td>
                            </tr>
                            <tr style="font-size:13px;font-weight: bold;">
                                <td>批量删除学生数据</td>
                                <td>
                                    <span>&nbsp;专业：</span>
                                    <select name="profession" style="width:180px;height:30px">
                                        <option value="">请选择</option>
                                        <?php
                                            $sql_proinf = "select name from proinf order by code";
                                            $result_proinf = mysqli_query($link,$sql_proinf);
                                            if(count($result_proinf)>0){
                                                while($row_proinf = mysqli_fetch_array($result_proinf)){
                                                   echo '<option value="'.$row_proinf['name'].'"';
                                                   echo ($row_proinf['name']==$_GET['profession'])?"selected":"";
                                                   echo '>'.$row_proinf['name'].'</option>';
                                                }
                                            }else{
                                                echo "<option value=\"\">数据字典连接错误</option>";
                                            }
                                        ?>
                                    </select>
                                    <span>&nbsp;班级：</span>
                                    <input class="text-word3" type="text" size="6" name="class" value="<?php  echo isset($_GET['class'])?($_GET['class']):""; ?>" placeholder="请选择">
                                    <span>&nbsp;年级：</span>
                                    <input class="text-word3" type="number" size="6" name="year" value="<?php  echo isset($_GET['year'])?($_GET['year']):""; ?>" placeholder="请选择">&nbsp;&nbsp;&nbsp;
                                    <input type="submit" name="import" value="批量删除筛选信息" class="text-word4" style="background:url('./imgs/del.png') no-repeat 10px 10px;cursor:pointer;font-weight:bold;color:#FF748D;box-shadow:2px 2px 1px #666;">&nbsp;&nbsp;&nbsp;&nbsp;
                                    (若筛选为空即表示所有该信息!)
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<script language="javascript">
function sumbit_sure(){
    var gnl=confirm("确定要批量删除吗?一旦删除无法恢复,建议请先导出备份");
    if (gnl==true){
        return true;
    }else{
        return false;
    }
}
</script>