<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/15
    */

    include 'core.php';

    check_params(array(
            new HttpParam(HTTP_POST, $userToken),
            new HttpParam(HTTP_POST, $reviewSeqNo),
            new HttpParam(HTTP_POST, $commentText)
    ));

    $userToken = md5($userToken);
    $writeTime = date("Y-m-d H:i:s", time());

    $query = "
            insert into COMMENT
                    (`userToken`        , `reviewSeqNo`     , `commentText`     , `writeTime`)
            values
                    ('$userToken'       , '$reviewSeqNo'    , '$commentText'    , '$writeTime');      
    ";
    
    mysql_query($query);

    $query = "
            update REVIEW
               set 
                   `commentCount`   =   `commentCount`  + 1
             where
                   `reviewSeqNo`    =   '$reviewSeqNo' 
    ";
    mysql_query($query);

    $query = "
            select * from COMMENT
            natural join USER 
             where 
                     `reviewSeqNo`      =   '$reviewSeqNo'
    ";
    
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)){
        sendpush($row['pushToken'], $row['commentText']);
    }

    $output['code'] = 0;
    echo json_encode($output);

    function sendpush($regid, $msg){

 
        // 헤더 부분
        $headers = array(
                'Content-Type:application/json',
                'Authorization:key=AIzaSyCBTX1a21re5fit42DQWHQ-JKw1EtKgnrk'
                );
    
        // 푸시 내용, data 부분을 자유롭게 사용해 클라이언트에서 분기할 수 있음.
        $arr = array();
        $arr['data'] = array();
        $arr['data']['data'] = $msg;
        $arr['registration_ids'] = array();
        $arr['registration_ids'][0] = $regid;


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
        $response = curl_exec($ch);
        curl_close($ch);
    
        // 푸시 전송 결과 반환.
        $obj = json_decode($response);
    
        // 푸시 전송시 성공 수량 반환.
        $cnt = $obj->{"success"};
    
    }
?>
