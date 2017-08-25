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
        $sql = "select * from selecttitle where id=".($_GET['id']+0);
        $result = mysqli_query($link,$sql);
        $sql_pro = "select * from proinf order by code";
        $result_pro = mysqli_query($link,$sql_pro);
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        }else{
            die("对不起，没有找到您要修改的数据。非常抱歉");
        }
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
                    <span>>修改我的课题</span>
                    <a href="JavaScript:history.go(-1)" class="return"><img src="imgs/return.png"></a>
                    <a href="javascript:location.replace(location.href);" class="refrash"><img src="imgs/refrash30px.png"></a>
                </div>
                <div id="addinfo">
                    <a href="addmyclass.php" target="iframe" class="add2">新增选题</a>
                </div>
                <div id="content" name="content">
                    <form action="teachinfaction.php?type=updateall&id=<?php echo $_GET['id'];?>" method="post" id="jsForm">
                    <table cellspacing="0" cellpaddind="0" width="100%" class="repasstable">
                        <tr>
                            <td>选题名称：</td>
                            <td>
                                <?php
                                    echo '<input type="text" name="titname" value="'.$row['titname'].'" class="text-word2 ajax-titname" required placeholder="">';

                                ?>*
                                <span class="ajax-error">修改的选题名称已存在,请重新填写</span>
                                <span class="ajax-add">可以使用</span>
                                <span style="color:red;">若选题已通过审核，请不要更改带*号信息</span>
                            </td>
                        </tr>
                        <tr>
                            <td>限选专业：</td>
                            <td>
                                <select name="procode" style="width:180px;height:30px" required>
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
                                </select>*
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
                                </select>*
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
                            <td><input type="number" name="numlimit" value="<?php echo $row['numlimit']; ?>" class="text-word2" style="width:50px;" required min="1"></td>
                        </tr>
                        <tr>
                            <td>课题备注：</td>
                            <td><textarea class="text-word2" name="remark" placeholder="选题要求,右下角可拉长填写"><?php echo $row['remark']; ?></textarea></td>
                        </tr>
                        <tr >
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