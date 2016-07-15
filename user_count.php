<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/14
    */
    
    include 'core.php';
    
    $query = "
            select * from USER 
             limit 1000
             ";
             
    $result = mysql_query($query);
   
    $output['code'] = 0;
    $output['result'] = mysql_num_rows($result);
    
    echo json_encode($output);
    die();
       
?>