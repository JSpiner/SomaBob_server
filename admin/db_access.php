<?php
    $conn = mysql_connect("ja-cdbr-azure-east-a.cloudapp.net","b758add82ebb28","a95a4508");
    mysql_selectdb("somabobdb",$conn);
    mysql_query("set names utf8");
    
?>