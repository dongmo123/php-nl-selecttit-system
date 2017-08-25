<?php
header("Content-type:text/html;charset:utf-8");
/*$profession=$_POST['profession'];//专业名称
$confirm=$_POST['confirm'];//哪个表
$teachnum=$_POST['teachnum'];*/
//定义数组选择器
$zconfirm = array(preresult=>"未确选",finalresult=>"已确选");
$zdif = array(level01=>"较容易",level02=>"中等",level03=>"较难",level04=>"很难");
error_reporting(E_ALL);
date_default_timezone_set('PRC');
//数据库连接

require("../public/config.php");
$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());
mysqli_select_db($link,DBNAME);
mysqli_set_charset($link,"utf8");
//判断是否有名字搜索
if(!empty($_POST['teachnum'])){
    //搜索教师
    $sql_teacher="select teachname from teacher where teachnum='{$_POST['teachnum']}'";
    $result_teacher=mysqli_query($link,$sql_teacher);
    $row_teacher=mysqli_fetch_array($result_teacher);
    $teachname=' 导师 '.$row_teacher[0];
}else{
    $teachname='';
}
if(!empty($_POST['confirm'])){
    $biao=' '.$_POST['confirm'].' ';
    $confirm=$_POST['confirm'];
}else{
    $biao=' finalresult ';
    $confirm='finalresult';
}
if(!empty($_POST['profession'])){
    $profession=$_POST['profession'].'专业';
}else{
    $profession=' ';
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
                             ->setDescription("学生选题信息 本系统由物联网13101 王禹设计")//备注
                             ->setKeywords("学生选题信息表")//关键字
                             ->setCategory("excel");//种类
$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');//合并单元格
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
//设置单元格样式（水平/垂直居中）
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

 //============封装搜索条件===================================
$wherelist = array();  //定义一个用于封装搜索条件的数组
$urllist = array();
//判断是否有名字搜索
if(!empty($_POST['teachnum'])){
    //搜索教师
    $wherelist[] = "teachnum like '%{$_POST['teachnum']}%'";
}
//判断并拼装搜索条件
$where = "";
if(count($wherelist)>0){
    $where  =" where ".implode(" and ",$wherelist);
}

$sqlgroups="select * from ".$biao.$where." order by teachnum,stunum";
$resultgroups=mysqli_query($link,$sqlgroups);
$numrows=mysqli_num_rows($resultgroups);

// 添加数据
$time=date('y-m-d',time());
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $row_depart['name'].$row_depart['year'].'届  '.$profession.$teachname.$zconfirm[$confirm].' 学生选题统计表  导出时间:20'.$time.'    总计'.$numrows.'条信息')
            ->setCellValue('A2', '学生学号')
            ->setCellValue('B2', '姓名')
            ->setCellValue('C2', '所属专业')
            ->setCellValue('D2', '班级')
            ->setCellValue('E2', '选题名称')
            ->setCellValue('F2', '指导老师')
            ->setCellValue('G2', '限选专业')
            ->setCellValue('H2', '选题方向')
            ->setCellValue('I2', '选题难度')
            ->setCellValue('J2', '是否确选');
while($data1=mysqli_fetch_array($resultgroups)){
    //信息处理转换
    //学生
    $sql_stu1="select profession from student where stunum='{$data1['stunum']}'";
    $result_stu1=mysqli_query($link,$sql_stu1);
    $row_stu1=mysqli_fetch_array($result_stu1);
    if($_POST['profession']!==$row_stu1['profession']&&$_POST['profession']!==''){
        $numrows-=1;
    }
}
$sqlgroups="select * from ".$biao.$where." order by teachnum,stunum";
$resultgroups=mysqli_query($link,$sqlgroups);
    if($numrows>0){
        $count=2;
        while($data=mysqli_fetch_array($resultgroups))
        {
            //信息处理转换
            //学生
            $sql_stu="select stuname,profession,class from student where stunum='{$data['stunum']}'";
            $result_stu=mysqli_query($link,$sql_stu);
            $row_stu=mysqli_fetch_array($result_stu);
            if($_POST['profession']==$row_stu['profession']||$_POST['profession']==''){
                //教师
                $sql_teach="select teachname from teacher where teachnum='{$data['teachnum']}'";
                $result_teach=mysqli_query($link,$sql_teach);
                $row_teach=mysqli_fetch_array($result_teach);
                //选题
                $sql_tit="select procode,direction,difcode from selecttitle where titname='{$data['titname']}'";
                $result_tit=mysqli_query($link,$sql_tit);
                $row_tit=mysqli_fetch_array($result_tit);

                //专业代码转换中文
                $sql_pro="select name from proinf where code='{$row_tit['procode']}'";
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
                $l10="J"."$count";
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($l1, $data['stunum'])
                            ->setCellValue($l2, $row_stu['stuname'])
                            ->setCellValue($l3, $row_stu['profession'])
                            ->setCellValue($l4, $row_stu['class'])
                            ->setCellValue($l5, $data['titname'])
                            ->setCellValue($l6, $row_teach['teachname'])
                            ->setCellValue($l7, $row_pro['name'])
                            ->setCellValue($l8, $row_tit['direction'])
                            ->setCellValue($l9, $zdif[$row_tit['difcode']])
                            ->setCellValue($l10, $zconfirm[$confirm]);
            }
        }
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle($profession.$teachname.$zconfirm[$confirm].'学生选题统计表');

        //在表的第一页上显示
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$row_depart['name'].$row_depart['year'].'届'.$profession.$teachname.$zconfirm[$confirm].'学生选题统计表.xls"');
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