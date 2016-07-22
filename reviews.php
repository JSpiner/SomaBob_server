<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/15
    */

    include 'core.php';

    check_params(array(
            new HttpParam(HTTP_POST, $page),
            new HttpParam(HTTP_POST, $optionPrice),
            new HttpParam(HTTP_POST, $optionPoint),
            new HttpParam(HTTP_POST, $optionType),
            new HttpParam(HTTP_POST, $userToken)
    ));

    $userToken = md5($userToken);

    $stPage = $page * 30;
    $edPage = 30;

    $query = "
            select * from REVIEW
            natural join `USER`
             where 
                     (`reviewPoint`      =   '$optionPoint'     or  '$optionPoint'      =   0 ) and 
                     (`reviewPrice`      =   '$optionPrice'     or  '$optionPrice'      =   0 ) and
                     (`foodType`         =   '$optionType'      or  '$optionType'       =   0 )

             order by `writeTime` DESC
             limit $stPage, $edPage
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

        $reviewSeqNo = $temp['reviewSeqNo'];
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
            $result = mysql_query($query);
            if(mysql_num_rows($result)==0){
                    return false;
            }
            else{
                    return true;
            }
    }
?>