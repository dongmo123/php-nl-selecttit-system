<?php
    require("./public/config.php");
    $link=@mysqli_connect(HOST,USER,PASSWORD) or die("对不起，您连接数据库出现了问题。");
    mysqli_select_db($link,DBNAME);
    mysqli_set_charset($link,"utf8");
    $sql = "select code from xiset where code='{$_GET['code']}'";
    $result = mysqli_query($link,$sql);
    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_array($result)){
            echo $row['code'];
        }
    }else{
        echo '找不到该信息';
    }
    mysqli_close($link);
?>