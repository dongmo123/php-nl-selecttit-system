<?php
session_start();  //开启session会话跟踪

//验证码的绘制

//1. 定义初始化信息
$length=4; //验证码位数
$code = getCode($length);//使用自定义函数获取验证码值
$_SESSION['turecode']=$code;  //将随机的验证码放到session数组中
//验证码的图片大小
$width=$length*16;
$height=25;

//2.准备画布，和颜色
$im = imagecreatetruecolor($width,$height); //创建一个基于真彩的画布
$bg = imagecolorallocate($im,240,240,240); //背景
$c[0] = imagecolorallocate($im,0,0,255); //篮色
$c[1] = imagecolorallocate($im,106,23,106); 
$c[2] = imagecolorallocate($im,18,88,18); 
$c[3] = imagecolorallocate($im,109,20,34);
$c[4] = imagecolorallocate($im,20,61,71); 

//3.开始绘制
imagefill($im,0,0,$bg);
//绘制验证码
for($i=0;$i<$length;$i++){
    imagettftext($im,15,rand(-45,45),2+$i*15,18,$c[rand(0,4)],'msyh.ttf',$code[$i]);
}
//添加随机的干扰点和线
for($i=0;$i<100;$i++){
    $cc = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
    imagesetpixel($im,rand(0,$width),rand(0,$height),$cc);
}
for($i=0;$i<6;$i++){
    $cc = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
    imageline($im,rand(0,$width),rand(0,$height),rand(0,$width),rand(0,$height),$cc);
}
//4.输出验证码图片
header("Content-Type:image/png");
imagepng($im);

//5.释放资源
imagedestroy($im);

/*
 *自定义一个随机验证码的函数
 *@param int $length 验证码的位数
 *@param int $type 验证码的类型：1纯数字，2小写字母与数字混合  其他为大小写字母与数字混合 
 *@return string 验证码
 */
function getCode($length=4,$type=1){
    $str="0123456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJIKLMNOPQRSTUVWXYZ";
    $m = strlen($str)-1; //获取随机长度
    if($type==1){
        $m=9;//控制验证码随机长度为9表示纯数字
    }elseif($type==2){
        $m=33;
    }
    $code="";
    //开始循环随机验证码值
    for($i=0;$i<$length;$i++){
        $code.=$str[rand(0,$m)];
    }
    return $code;
}
