<?php
session_start();
header("Content-type:text/html;charset:utf-8");
require("./public/error.php");
//全局变量
$succ_result=0;
$error_result=0;
$file=$_FILES['filename'];
$max_size="2000000"; //最大文件限制（单位：byte）
$fname=$file['name'];
$ftype=strtolower(substr(strrchr($fname,'.'),1));
 //文件格式
$uploadfile=$file['tmp_name'];
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(is_uploaded_file($uploadfile)){
        if($file['size']>$max_size){
            echo "<script type='text/javascript'>window.onload=function(){alert('表格文件过大!不得大于2M');history.go(-1);}</script>";
            exit;
        }
        if($ftype!='xls'){
            echo "<script type='text/javascript'>window.onload=function(){alert('表格文件格式错误!请兼容成xls格式');history.go(-1);}</script>";
            exit;
        }
    }else{
        echo "<script type='text/javascript'>window.onload=function(){alert('没有选择文件!');history.go(-1);}</script>";
        exit;
    }
}
 //1.导入数据库配置文件
require("./public/config.php");
//2.连接mysqli数据库并检测是否连接成功
$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());

//3.选择数据库并设置字符集
mysqli_select_db($link,DBNAME);
mysqli_set_charset($link,"utf8");

//调用phpexcel类库
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Classes/PHPExcel/Reader/Excel5.php';

$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
$objPHPExcel = $objReader->load($uploadfile);
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow(); // 取得总行数
$highestColumn = $sheet->getHighestColumn(); // 取得总列数
$arr_result=array();
$strs=array();
$result_stunum=array();
$zstrs=array("选题专用专业"=>1,"系专业"=>0);
//用户名查重
for($j=2;$j<=$highestRow;$j++){
    unset($arr_result);
    unset($strs);
    for($k='A';$k<= $highestColumn;$k++)
    {
         //读取单元格
        $arr_result.=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().',';
    }
    $strs=explode(",",$arr_result);
    if($strs[0]!==''){
        $sql_stunum = "select code from proinf where code='{$strs[0]}'";
        $result_stunum = mysqli_query($link,$sql_stunum);
        if(mysqli_num_rows($result_stunum)){
            $repeat_result[]=$strs[0];
            $error_result+=1;
        }else{
            $sql = "insert into proinf (ID,code,name,depart,college,type,time) values (null,
                '{$strs[0]}',
                '{$strs[1]}',
                '{$strs[2]}',
                '{$strs[3]}',
                '{$zstrs[$strs[4]]}',
                now())";
            //echo $sql."<br/>";
            mysqli_query($link,$sql);
            //判断添加是否成功
            if(mysqli_insert_id($link)>0){
                $succ_result+=1;
            }else{
                $error_result+=1;
            }
        }
    }
}
//判断并拼装搜索条件
$countrepeat= "";
if(count($repeat_result)>0){
    $countrepeat="<textarea style=\'font-size:12px;width:90%;line-height:20px;height:100px;\'>专业代码".implode(",",$repeat_result)."已存在,不再导入此信息</textarea><br>";
}
echo "<script type='text/javascript'>window.onload=function(){update('插入成功".$succ_result."条数据！<br>插入失败".$error_result."条数据！<br>".$countrepeat."');}</script>";
mysqli_close($link);
?>
<!doctype html>
<html>
    <head>
        <title>编辑用户</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="style/public.css">
        <link rel="stylesheet" type="text/css" href="style/reset.css">
        <!-- <meta http-equiv="refresh" content="3;url=student.php"/> -->
    </head>
    <body>
        <div id="container">
            <div>
                <div id="al" style="height:400px;">
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function update(word){
        var al=document.getElementById('al');
        al.innerHTML=word+'<a href="all-addpro.php">返回上一页 &nbsp; &nbsp; &nbsp;    </a><a href="proinf.php">返回专业信息首页</a>';
    }
    function reaction(word){
        var al=document.getElementById('al');
        al.innerHTML=word;
        setTimeout(function(){
            al.innerHTML='';
        },10000);
    }
</script>