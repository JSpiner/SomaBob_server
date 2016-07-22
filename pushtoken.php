<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/15
    */

    include 'core.php';

    check_params(array(
            new HttpParam(HTTP_POST, $userToken),
            new HttpParam(HTTP_POST, $pushToken)
    ));

    $userToken = md5($userToken);

    $query = "
            update USER
               set 
                   `pushToken`      =   '$pushToken'
             where 
                   `userToken`      =   '$userToken'
    ";
    mysql_query($query);

    $output['code'] = 0;
    echo json_encode($output);




?>