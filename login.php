<?php

    /*
    @Author : JSpiner
    @Date : 2016/07/14
    */
    
    include 'core.php';
	
	check_params(array(
		new HttpParam(HTTP_POST, $userToken),
                new HttpParam(HTTP_POST, $userName),
                new HttpParam(HTTP_POST, $userImage),
	));
    $userToken = md5($userToken);
    $nowTime = date("Y-m-d H:i:s", time());
    
    $query = "
            select * from USER
             where   
                     `userToken`    =   '$userToken'
             limit 1
             ";
    $result = mysql_query($query);
    
    if(mysql_num_rows($result)==0){
        //신규 회원 
        $query = "
                insert into USER 
                        (`userToken`,
                         `userName`,
                         `userSignTime`,
                         `userLastLoginTime`)
                values 
                        ('$userToken',
                         '$userName',
                         '$nowTime',
                         '$nowTime')
                    ";
        mysql_query($query);
    }
    
    //마지막로그인 업데이트 
    
    $query = "
            update USER 
               set 
                   `userName`               =   '$userName',
                   `userImage`              =   '$userImage',
                   `userLastLoginTime`      =   '$nowTime'
             where 
                   `userToken`              =   '$userToken'
            ";
    mysql_query($query);
    
    
    $output['code'] = 0;
    echo json_encode($output);
    die();


?>