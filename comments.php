<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/15
    */

    include 'core.php';

    check_params(array(
            new HttpParam(HTTP_POST, $reviewSeqNo)
    ));

    $query = "
            select * from COMMENT
           natural   join `USER`
             where   
                     `reviewSeqNo`      =   '$reviewSeqNo'
    ";

    $result = mysql_query($query);

    $output['code'] = 0;
    $output['result'] = array();
    while($row = mysql_fetch_array($result)){
        $temp['commentSeqNo'] = $row['commentSeqNo'];
        $temp['reviewSeqNo'] = $row['reviewSeqNo'];
        $temp['commentText'] = $row['commentText'];
        $temp['userName'] = $row['userName'];
        $temp['userImage'] = $row['userImage'];
        $temp['writeTime'] = $row['writeTime'];

        array_push($output['result'], $temp);
    }

    echo json_encode($output);


?>