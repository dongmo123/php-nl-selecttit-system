<?php
header("Content-type:text/html;charset:utf-8");
/*$procode=$_POST['procode'];
$teachnum=$_POST['teachnum'];
$confirm=$_POST['confirm'];
$pass=$_POST['pass'];*/
//定义数组选择器
$zconfirm = array(0=>"未确选",1=>"确选");
$zpass = array(0=>"等待",1=>"通过",2=>"不合格");
$zdif = array(level01=>"较容易",level02=>"中等",level03=>"较难",level04=>"很难");
error_reporting(E_ALL);
date_default_timezone_set('PRC');
//数据库连接

require("../public/config.php");
$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());
mysqli_select_db($link,DBNAME);
mysqli_set_charset($link,"utf8");
//判断是否有名字搜索
if(!empty($_POST['procode'])){
    //搜索专业
    //字典转换中文
    $sql_zpro="select name from proinf where code='{$_POST['procode']}'";
    $result_zpro=mysqli_query($link,$sql_zpro);
    $row_zpro=mysqli_fetch_array($result_zpro);
    $proname=$row_zpro[0]."专业 ";
}else{
    $proname='';
}
if(!empty($_POST['teachnum'])){
    //搜索教师
    $sql_teacher="select teachname from teacher where teachnum='{$_POST['teachnum']}'";
    $result_teacher=mysqli_query($link,$sql_teacher);
    $row_teacher=mysqli_fetch_array($result_teacher);
    $teachname=' 导师 '.$row_teacher[0];
}else{
    $teachname='';
}
if(!empty($_POST['confirm'])||$_POST['confirm']=='0'){
    //搜索是否确选
    $confirm=' '.$zconfirm[$_POST['confirm']];
}else{
    $confirm="";
}
if(!empty($_POST['pass'])||$_POST['pass']=='0'){
    //搜索审核通过
    $pass=' 审核'.$zpass[$_POST['pass']];
}else{
    $pass='';
}

//信息所属学院
$sql_depart="select * from department";
$result_depart=mysqli_query($link,$sql_depart);
$row_depart=mysqli_fetch_array($result_depart);

/** PHPExcel */
require_once '../Classes/PHPExcel.php';
//创建新的PHPExcel对象
$objPHPExcel = new PHPExcel();

// 设置表格属性
$objPHPExcel->getProperties()->setCreator("bsxuanti admin")//作者
                             ->setLastModifiedBy("bsxuantiadmin")//最后一次保存者
                             ->setTitle("xuanti info")//标题
                             ->setSubject("excel")//主题
                             ->setDescription("选题信息 本系统由物联网13101 王禹设计")//备注
                             ->setKeywords("选题信息表")//关键字
                             ->setCategory("excel");//种类
$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');//合并单元格
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(6);
//设置单元格样式（水平/垂直居中）
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

 //============封装搜索条件===================================
$wherelist = array();  //定义一个用于封装搜索条件的数组
$urllist = array();
//判断是否有名字搜索
if(!empty($_POST['procode'])){
    //搜索专业
    $wherelist[] = "procode like '%{$_POST['procode']}%'";
}
if(!empty($_POST['teachnum'])){
    //搜索教师
    $wherelist[] = "teachnum like '%{$_POST['teachnum']}%'";
}
if(!empty($_POST['confirm'])||$_POST['confirm']=='0'){
    //搜索是否确选
    $wherelist[] = "confirm ='{$_POST['confirm']}'";
}
if(!empty($_POST['pass'])||$_POST['pass']=='0'){
    //搜索审核通过
    $wherelist[] = "pass ='{$_POST['pass']}'";
}
//判断并拼装搜索条件
$where = "";
if(count($wherelist)>0){
    $where  =" where ".implode(" and ",$wherelist);
}

$sqlgroups="select * from selecttitle ".$where." order by teachnum,confirm desc,procode,pass desc";
$resultgroups=mysqli_query($link,$sqlgroups);
$numrows=mysqli_num_rows($resultgroups);

// 添加数据
$time=date('y-m-d',time());
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $row_depart['name'].$row_depart['year'].'届 '.$proname.$teachname.$confirm.$pass.' 选题信息表  导出时间:20'.$time.'    总计'.$numrows.'个选题')
            ->setCellValue('A2', '选题名称')
            ->setCellValue('B2', '指导老师')
            ->setCellValue('C2', '选题难度')
            ->setCellValue('D2', '选题方向')
            ->setCellValue('E2', '限选专业')
            ->setCellValue('F2', '限选人数')
            ->setCellValue('G2', '选题要求')
            ->setCellValue('H2', '是否确选')
            ->setCellValue('I2', '审核');

    if ($numrows>0){
        $count=2;
        while($data=mysqli_fetch_array($resultgroups))
        {
            //教师
            $sql_teach="select teachname from teacher where teachnum='{$data['teachnum']}'";
            $result_teach=mysqli_query($link,$sql_teach);
            $row_teach=mysqli_fetch_array($result_teach);
            //字典转换中文
            $sql_pro="select name from proinf where code='{$data['procode']}'";
            $result_pro=mysqli_query($link,$sql_pro);
            $row_pro=mysqli_fetch_array($result_pro);
            $count+=1;
            $l1="A"."$count";
            $l2="B"."$count";
            $l3="C"."$count";
            $l4="D"."$count";
            $l5="E"."$count";
            $l6="F"."$count";
            $l7="G"."$count";
            $l8="H"."$count";
            $l9="I"."$count";
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($l1, $data['titname'])
                        ->setCellValue($l2, $row_teach[0])
                        ->setCellValue($l3, $zdif[$data['difcode']])
                        ->setCellValue($l4, $data['direction'])
                        ->setCellValue($l5, $row_pro[0])
                        ->setCellValue($l6, $data['numlimit'])
                        ->setCellValue($l7, $data['remark'])
                        ->setCellValue($l8, $zconfirm[$data['confirm']])
                        ->setCellValue($l9, $zpass[$data['pass']]);
        }
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle($proname.$teachname.$confirm.$pass.'选题信息表');

        //在表的第一页上显示
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$row_depart['name'].$row_depart['year'].'届'.$proname.$teachname.$confirm.'选题信息表.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }else{
        echo "<script type='text/javascript'>window.onload=function(){update('没有找到此信息,请重新筛选!');history.go(-1);}</script>";
    }
?>
<!doctype html>
<html>
    <head>
        <title>编辑用户</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="../style/public.css">
        <link rel="stylesheet" type="text/css" href="../style/reset.css">
    </head>
    <body>
        <div id="container">
            <div>
                <div id="al">

                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function update(word){
        var al=document.getElementById('al');
        al.innerHTML=word;
        setTimeout(function(){
            al.innerHTML='';
        },10000);
    }
</script>