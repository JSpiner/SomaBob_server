<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/15
    */

    include 'core.php';


    check_params(array(
            new HttpParam(HTTP_POST, $userToken),
    ));

    $userToken = md5($userToken);

    $query = "
            select * from REVIEW
            natural join USER
             order by 
                          `likeCount` DESC
             limit 4
    ";

    $result = mysql_query($query);

    $output['code'] = 0;
    $output['result'] = array();
    while($row = mysql_fetch_array($result)){
        $temp = array();
        $temp['reviewSeqNo'] = $row['reviewSeqNo'];
        $temp['userToken'] = $row['userToken'];
        $temp['userName'] = $row['userName'];
        $temp['userImage'] = $row['userImage'];
        $temp['storeName'] = $row['storeName'];
        $temp['reviewPoint'] = $row['reviewPoint'];
        $temp['reviewPrice'] = $row['reviewPrice'];
        $temp['foodType'] = $row['foodType'];
        $temp['reviewDetail'] = $row['reviewDetail'];
        $temp['reviewImage'] = $row['reviewImage'];
        $temp['likeCount'] = $row['likeCount'];
        $temp['commentCount'] = $row['commentCount'];
        $temp['writeTime'] = $row['writeTime'];

        $temp['isLike'] = get_like_row($userToken, $reviewSeqNo);

        array_push($output['result'], $temp);
    }

    echo json_encode($output);

    function get_like_row($userToken, $reviewSeqNo){
            $query = "
                        select * from `LIKE`
                         where 
                                 `userToken`    =       '$userToken' and
                                 `reviewSeqNo`  =       '$reviewSeqNo'
            ";
            $result = mysql_query($result);
            if(mysql_num_rows($result)!=0){
                    return true;
            }
            else{
                    return false;
            }
    }
?>