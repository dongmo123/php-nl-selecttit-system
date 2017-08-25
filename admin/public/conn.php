<?php
$link=mysql_connect('localhost','root','root') or die("连接服务器失败：".mysql_error());
mysql_select_db('bsxuanti',$link) or die("找不到数据库：".mysql_error());
?>