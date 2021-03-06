<?php
header("Content-type:text/html;charset:utf-8");
$position=$_POST['position'];
$sex = array(0=>"男",1=>"女");
error_reporting(E_ALL);
date_default_timezone_set('PRC');

//数据库连接
require("../public/config.php");
$link=@mysqli_connect(HOST,USER,PASSWORD) or die ("对不起，您连接数据库出现了问题。"."错误原因：".mysqli_error()."错误行号：".mysqli_errno());
mysqli_select_db($link,DBNAME);
mysqli_set_charset($link,"utf8");
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
                             ->setTitle("teacher info")//标题
                             ->setSubject("excel")//主题
                             ->setDescription("教师信息 本系统由物联网13101 王禹设计")//备注
                             ->setKeywords("教师信息表")//关键字
                             ->setCategory("excel");//种类
$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');//合并单元格
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(9);
//设置单元格样式（水平/垂直居中）
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

 //============封装搜索条件===================================
$wherelist = array();  //定义一个用于封装搜索条件的数组
$urllist = array();
//判断是否有名字搜索
if(!empty($_POST['position'])){
    //搜索专业
    $wherelist[] = "position='{$_POST['position']}'";
}

//判断并拼装搜索条件
$where = "";
if(count($wherelist)>0){
    $where  =" where ".implode(" and ",$wherelist);
}

$sqlgroups="select * from teacher ".$where." order by position,teachnum";
$resultgroups=mysqli_query($link,$sqlgroups);
$numrows=mysqli_num_rows($resultgroups);

// 添加数据
$time=date('y-m-d h:i:s',time());
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $row_depart['name'].$position.' 教师信息表  导出时间:20'.$time.'    总计'.$numrows.'人')
            ->setCellValue('A2', '教师编码')
            ->setCellValue('B2', '姓名')
            ->setCellValue('C2', '性别')
            ->setCellValue('D2', '所在系')
            ->setCellValue('E2', '职称')
            ->setCellValue('F2', '手机号码')
            ->setCellValue('G2', 'qq号码')
            ->setCellValue('H2', 'email')
            ->setCellValue('I2', '限带人数');

    if ($numrows>0){
        $count=2;
        while($data=mysqli_fetch_array($resultgroups))
        {
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
                        ->setCellValue($l1, $data['teachnum'])
                        ->setCellValue($l2, $data['teachname'])
                        ->setCellValue($l3, $sex[$data['sex']])
                        ->setCellValue($l4, $data['position'])
                        ->setCellValue($l5, $data['profession'])
                        ->setCellValue($l6, $data['tel'])
                        ->setCellValue($l7, $data['qq'])
                        ->setCellValue($l8, $data['email'])
                        ->setCellValue($l9, $data['leadnum']);
        }
        //重命名表
        $objPHPExcel->getActiveSheet()->setTitle($row_depart['name'].$position.'教师信息表');

        //在表的第一页上显示
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$row_depart['name'].$position.'教师信息表.xls"');
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