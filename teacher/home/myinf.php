<?php
    session_start();
    if(!$_SESSION['teachnum']){
        header("Location:../../index.php");
            exit();
    }
    error_reporting(E_ALL & ~E_NOTICE);
        require("./public/config.php");
        //2.连接数据库，并判断是否连接成功
        $link = @mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您的数据库连接失败，请重新配置连接。");
        //3.选择数据库并设置字符集
        mysqli_select_db($link,DBNAME);
        mysqli_set_charset($link,"utf8");
        //4.定义查询SQL语句，并执行
        $sql = "select * from teacher where teachnum='{$_SESSION['teachnum']}'";
        $result = mysqli_query($link,$sql);
        $sql_teachinf = "select name from teachinf order by code";
        $result_teachinf = mysqli_query($link,$sql_teachinf);
        $sql_proinf = "select depart from proinf group by depart order by code";
        $result_proinf = mysqli_query($link,$sql_proinf);
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
        <title>修改教师信息</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div class="position2">
                    <span>您的位置:个人中心</span>
                    <a href="myinf.php" target="iframe">>>修改资料</a>
                    <span>>修改我的信息</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div id="hotinf">
                    操作流程：①认真填写个人信息-->②导师添加选题(限时)-->③等待审核通过-->④导师确选学生选题-->⑤导师给学生发布毕设任务书并指导学生完成毕业设计
                </div>
                <div id="content" name="content">
                    <form action="teachinfaction.php?type=myinfupdate&id=<?php echo $row['ID'];?>" method="post" id="jsForm">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
                        <tr>
                            <td>教师ID：</td>
                            <td><input type="text" name="id" value="<?php echo $row['ID']; ?>" class="text-word2" disabled>
                                <span>(*唯一标识,不可修改!)</span>
                            </td>
                        </tr>
                        <tr>
                            <td>教师编号：</td>
                            <td><input type="text" name="teachnum" value="<?php echo $row['teachnum']; ?>" class="text-word2" placeholder="请输入六位以上的学号" required data-rule-usernumber="true" data-msg-required="请输入教师学号" data-msg-usernumber="请输入正确格式,合法字符:数字" minlength="5" maxlength="15"></td>
                        </tr>
                        <tr>
                            <td>教师姓名：</td>
                            <td><input type="text" name="teachname" value="<?php echo $row['teachname']; ?>" class="text-word2" required placeholder="请输入教师真实姓名"></td>
                        </tr>
                        <tr>
                            <td>性别：</td>
                            <td>
                                <input type="radio" name="sex" value="0" class="" <?php echo ($row['sex']=='0')?"checked":""; ?>> 男
                                <input type="radio" name="sex" value="1" class="" <?php echo ($row['sex']=='1')?"checked":""; ?>> 女
                            </td>
                        </tr>
                        <tr>
                            <td>密码：</td>
                            <td><input type="password" name="password" value="" class="text-word2" placeholder="请输入六位以上的密码" required data-rule-password="true" data-msg-required="请输入密码" data-msg-password="请输入正确格式,合法字符:数字 大小写字母 _!@#$%&." minlength="6" maxlength="20" id="password"></td>
                        </tr>
                        <tr>
                            <td>确认密码：</td>
                            <td><input type="password" name="repassword" value="" class="text-word2" placeholder="确认新密码" required equalTo="#password"></td>
                        </tr>
                        <tr>
                            <td>所在系：</td>
                            <td>
                                <select name="position" style="width:180px;height:30px">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_proinf)>0){
                                            while($row_proinf = mysqli_fetch_array($result_proinf)){
                                               echo '<option value="'.$row_proinf['depart'].'"';
                                               echo ($row_proinf['depart']==$row['position'])?"selected":"";
                                               echo '>'.$row_proinf['depart'].'</option>';
                                            }
                                        }else{
                                            echo "<option value=\"\">数据字典连接错误</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>教师职称：</td>
                            <td>
                                <select name="profession" style="width:180px;height:30px">
                                    <option value="">请选择</option>
                                    <?php
                                        if(count($result_teachinf)>0){
                                            while($row_teachinf = mysqli_fetch_array($result_teachinf)){
                                               echo '<option value="'.$row_teachinf['name'].'"';
                                               echo ($row_teachinf['name']==$row['profession'])?"selected":"";
                                               echo '>'.$row_teachinf['name'].'</option>';
                                            }
                                        }else{
                                            echo "<option value=\"\">数据字典连接错误</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>联系电话：</td>
                            <td><input type="number" name="tel" value="<?php echo $row['tel']; ?>" class="text-word2" placeholder="请务必填写有效联系方式" required data-rule-tel="true" data-msg-required="请输入手机号" data-msg-tel="请输入正确格式"></td>
                        </tr>
                        <tr>
                            <td>qq：</td>
                            <td><input type="number" name="qq" value="<?php echo $row['qq']; ?>" class="text-word2" minlength="5" maxlength="12" required placeholder="必填"></td>
                        </tr>
                        <tr>
                            <td>电子邮箱：</td>
                            <td><input type="text" name="email" value="<?php echo $row['email']; ?>" class="text-word2" required placeholder="请输入email地址" data-rule-email="true" data-msg-required="请输入email地址" data-msg-email="请输入正确的email地址"></td>
                        </tr>
                        <tr>
                            <td>限带人数：</td>
                            <td><input type="number" name="leadnum" value="<?php echo $row['leadnum']; ?>" class="text-word2" required></td>
                        </tr>
                        <tr>
                            <td>上次修改时间：</td>
                            <td><input type="text" name="time" value="<?php echo $row['time']; ?>" class="text-word2" disabled></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input name="" type="submit" value="修  改" class="text-but2">
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