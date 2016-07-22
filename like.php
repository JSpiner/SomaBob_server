<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/18
    */

    include 'core.php';

    check_params(array(
            new HttpParam(HTTP_POST, $reviewSeqNo),
            new HttpParam(HTTP_POST, $userToken)
    ));

    $userToken = md5($userToken);

    $query = "
                select * from `LIKE`
                 where 
                            `userToken`    =       '$userToken' and
                            `reviewSeqNo`  =       '$reviewSeqNo'
    ";
    $result = mysql_query($query);

    $writeTime = date('Y-m-d H:i:s', time());

    $type = 0;
    if(mysql_num_rows($result)==0){
        $query = "
                insert into `LIKE` 
                       (`userToken`, `reviewSeqNo`, `writeTime`)
                values 
                       ('$userToken', '$reviewSeqNo', '$writeTime')
        ";
        mysql_query($query);
        $query = "
                update REVIEW
                   set
                       `likeCount`      =   `likeCount` + 1
                 where 
                       `reviewSeqNo`    =   '$reviewSeqNo'
        ";
        mysql_query($query);
        $type = 1;
    }
    else{
        $query = "
                delete from `like`
                 where 
                            `userToken`     =   '$userToken' and
                            `reviewSeqNo`   =   '$reviewSeqNo'
        ";
        mysql_query($query);
        $query = "
                update REVIEW
                   set
                       `likeCount`      =   `likeCount` - 1
                 where 
                       `reviewSeqNo`    =   '$reviewSeqNo'
        ";
        mysql_query($query);
        $type = 2;
    }

    $output['code'] = 0;
    $output['type'] = $type;

    echo json_encode($output);



?>