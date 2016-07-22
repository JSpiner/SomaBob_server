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
                <li class="active"><a href="admin_review.php">게시글 관리</a></li>
                <li><a href="admin_comment.php">댓글 관리</a></li>
            </ul>
        </div>
    </div>

    <table class="table table-striped table-bordered" style='margin-top:50px;'>
        <thead>
            <tr>
                <th class="span1">고유번호</th>
                <th class="span2">글쓴이 이름</th>
                <th class="span3">가게 이름</th>
                <th class="span1">평점 (point)</th>
                <th class="span1">평점 (price)</th>
                <th class="span1">평점 (type)</th>
                <th class="span2">이미지</th>
                <th class="span1">좋아요 수</th>
                <th class="span1">댓글 수</th>
                <th class="span3">글 작성 날짜</th>
                <th class="span1">댓글보기</th>
                <th class="span1">글 삭제</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
                $query = "
                            select * from REVIEW
                            natural join USER
                            limit
                            0, 2000
                ";

                $result = mysql_query($query);
                while($row = mysql_fetch_array($result)){
                    echo "<tr>";
                    echo "<td>".$row['reviewSeqNo']."</td>";
                    echo "<td>".$row['userName']."</td>";
                    echo "<td>".$row['storeName']."</td>";
                    echo "<td>".$row['reviewPoint']."</td>";
                    echo "<td>".$row['reviewPrice']."</td>";
                    echo "<td>".$row['foodType']."</td>";
                    echo "<td><a href='../api/".$row['reviewImage']."'>".$row['reviewImage']."</a></td>";
                    echo "<td>".$row['likeCount']."</td>";
                    echo "<td>".$row['commentCount']."</td>";
                    echo "<td>".$row['writeTime']."</td>";
                    echo "<td>";
                    ?>
                    <button class="btn btn-info" type="button">댓글보기</button>
                    <?php
                    echo "</td>";       
                    echo "<td>";
                    ?>
                    <button class="btn btn-danger" type="button">글삭제</button>
                    <?php
                    echo "</td>";       
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>

  </body>
</html>