<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/15
    */

    include 'core.php';

    check_params(array(
            new HttpParam(HTTP_POST, $userToken),
            new HttpParam(HTTP_POST, $storeName),
            new HttpParam(HTTP_POST, $reviewPoint),
            new HttpParam(HTTP_POST, $reviewPrice),
            new HttpParam(HTTP_POST, $foodType),
            new HttpParam(HTTP_POST, $reviewDetail),
    ));
    $reviewImage = $_FILES['reviewImage'];
    $fileName = "img/".time()."_img.jpg";
    move_uploaded_file($reviewImage['tmp_name'], $fileName);

    $writeTime = date("Y-m-d H:i:s", time());
    $query = "
            insert into REVIEW
                    (`userToken`    , `storeName`   , `reviewPoint`     , `reviewPrice`     , `foodType`    , `reviewDetail`    , `reviewImage`     , `writeTime`)
            values 
                    ('$userToken'   , '$storeName'  , '$reviewPoint'    , '$reviewPrice'    , '$foodType'   , '$reviewDetail'   , '$fileName'       , '$writeTime')
            ";
    mysql_query($query);

    $output['code'] = 0;
    echo json_encode($output);


?>