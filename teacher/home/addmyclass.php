<?php
    session_start();
    if(!$_SESSION['teachnum']){
        header("Location:../../index.php");
            exit();
    }
    error_reporting(E_ALL & ~E_NOTICE);
    header("Content-Type:text/html;charset=utf8");
    require("./public/config.php");
    $link = @mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您的数据库连接失败，请重新配置连接。");
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
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
                    <span>您的位置:个人中心>></span>
                    <a href="myclass.php" target="iframe">我的课题</a>
                    <span>>添加我的课题</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div id="addinfo">
                    <a href="../../admin/home/proinf.php" target="iframe" class="add2">添加选题限选专业</a>
                    <a href="addmyclass.php" target="iframe" class="add2">新增选题</a>
                </div>
                <div id="content" name="content">
                    <form action="teachinfaction.php?type=insert" method="post" id="jsForm">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
                        <tr>
                            <td>选题名称：</td>
                            <td>
                                <input type="text" name="titname" value="" class="text-word2 ajax-titname" required placeholder="">
                                <span class="ajax-error">该选题名称已存在,请重新填写</span>
                                <span class="ajax-add">可以使用</span>
                            </td>
                        </tr>
                        <tr>
                            <td>限选专业：</td>
                            <td>
                                <select name="procode" style="width:180px;height:30px">
                                    <?php
                                        if(count($result_pro)>0){
                                            while($row_pro = mysqli_fetch_array($result_pro)){
                                               echo '<option value="'.$row_pro['code'].'"';
                                               echo '>'.$row_pro['name'].'</option>';
                                            }
                                        }else{
                                            echo "<option value=\"\">数据字典连接错误</option>";
                                        }
                                    ?>
                                </select><span style="color:red;">若没有想要的限选专业，请先通过上面的超链接添加特殊限选专业</span>
                            </td>
                        </tr>
                        <tr>
                            <td>选题方向：</td>
                            <td>
                                <select name="direction" class="" style="width:180px;height:30px">
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
                                    <option value="level01">较容易</option>
                                    <option value="level02">中等</option>
                                    <option value="level03">较难</option>
                                    <option value="level04">很难</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>限选人数：</td>
                            <td><input type="number" name="numlimit" value="1" class="text-word2" style="width:50px;" required></td>
                        </tr>
                        <tr>
                            <td>课题备注：</td>
                            <td><textarea class="text-word2" name="remark" placeholder="选题要求,右下角可拉长填写"></textarea></td>
                        </tr>
                        <tr >
                            <td></td>
                            <td>
                                <input name="" type="submit" value="添  加" class="text-but2">
                                <input name="" type="reset" value="重  置" class="text-but2 ajax-reset">
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