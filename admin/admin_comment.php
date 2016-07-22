<?php
    include 'db_access.php';

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8"/>
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body style='margin-top:50px;margin-left:50px;margin-right:100px;'>
    <h1>SomaBob 관리자 패널</h1>
    <div class="navbar" style='margin-top:20px;'>
        <div class="navbar-inner">
            <a class="brand" href="#">SomaBob</a>
            <ul class="nav">
                <li><a href="admin_user.php">회원 관리</a></li>
                <li><a href="admin_review.php">게시글 관리</a></li>
                <li class="active"><a href="admin_comment.php">댓글 관리</a></li>
            </ul>
        </div>
    </div>

    <table class="table table-striped table-bordered" style='margin-top:50px;'>
        <thead>
            <tr>
                <th class="span1">댓글 고유번호</th>
                <th class="span2">이름</th>
                <th class="span1">게시글 번호</th>
                <th class="span5">댓글 내용</th>
                <th class="span2">작성 날짜</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
                $query = "
                            select * from COMMENT
                            natural   join `USER`
                            limit
                            0, 2000
                ";

                $result = mysql_query($query);
                while($row = mysql_fetch_array($result)){
                    echo "<tr>";
                    echo "<td>".$row['commentSeqNo']."</td>";
                    echo "<td>".$row['userName']."</td>";
                    echo "<td>".$row['reviewSeqNo']."</td>";
                    echo "<td>".$row['commentText']."</td>";
                    echo "<td>".$row['writeTime']."</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>

  </body>
</html>