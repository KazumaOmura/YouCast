<?php
ob_start();
session_start();
if(!isset($_SESSION['user']) != "") {
  header("Location: index.php");
}
include_once 'dbconnect.php';

// ユーザーIDからユーザー名を取り出す
$query = "SELECT * FROM user WHERE id=".$_SESSION['user']."";
$result = $mysqli->query($query);

if (!$result) {
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}

// ユーザー情報の取り出し
while ($row = $result->fetch_assoc()) {
  $user_id = $row['id'];
  $user_email = $row['email'];
  $user_prof_done = $row['prof_done'];
  $user_authority = $row['authority'];
}

if($user_prof_done == 0){
  header("Location: prof_insert.php");
}


if($user_authority == 0){
  $query = "SELECT * FROM client_prof WHERE user_id='$user_id'";
  $result = $mysqli->query($query);
}
else if($user_authority == 1) {
  $query = "SELECT * FROM editer_prof WHERE user_id='$user_id'";
  $result = $mysqli->query($query);
}
// ユーザーIDからユーザー名を取り出す

if (!$result) {
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}

// ユーザー情報の取り出し
while ($row = $result->fetch_assoc()) {
  $user_username = $row['username'];
  $user_gender = $row['gender'];
  $user_birth_year = $row['birth_year'];
  $user_birth_month = $row['birth_month'];
  $user_birth_day = $row['birth_day'];
  $user_image_name = $row['image_name'];
}

// データベースの切断
$result->close();
?>

<!DOCTYPE HTML>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link rel="stylesheet" href="css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>TOP - YouCast</title>
  <link rel="stylesheet" href="css/mypage.css">
</head>

<body>
<?php include('header.php'); ?>
<?php
  // ユーザーIDからユーザー名を取り出す
  $query = "SELECT * FROM user WHERE authority = 0" ;
  $result = $mysqli->query($query);

  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
?>
          <div id="editer_list">
            <h2>EDITER</h2>
            <h1>動画編集者 一覧</h1>
            <?php
                $query3 = "SELECT * FROM user WHERE authority = 1 ORDER BY id DESC" ;
                $result3 = $mysqli->query($query3);

                if (!$result3) {
                print('クエリーが失敗しました。' . $mysqli->error);
                $mysqli->close();
                exit();
                }
                // ユーザー情報の取り出し
                $num = 0;
                while ($row3 = $result3->fetch_assoc()) {
                    $editer_id[$num] = $row3['id'];
                    $editer_register_year_time[$num] = $row3['register_year_time'];
                    $editer_register_month_time[$num] = $row3['register_month_time'];
                    $editer_register_day_time[$num] = $row3['register_day_time'];
                    $editer_id_sql = $editer_id[$num];

                    $query2 = "SELECT * FROM editer_prof WHERE user_id = '$editer_id_sql'" ;
                    $result2 = $mysqli->query($query2);

                    if (!$result2) {
                    print('クエリーが失敗しました。' . $mysqli->error);
                    $mysqli->close();
                    exit();
                    }
                    // ユーザー情報の取り出し
                    while ($row2 = $result2->fetch_assoc()) {
                        $editer_profile_id[$num] = $row2['id'];
                        $editer_username[$num] = $row2['username'];
                        $editer_unit_price[$num] = $row2['unit_price'];
                        $editer_unit_jpy_price[$num] = number_format($editer_unit_price[$num]);
                        $editer_image_name[$num] = $row2['image_name'];
                    }
                    
                        $query = "SELECT * FROM evaluation WHERE editer_id='$editer_id_sql'";
                        $result = $mysqli->query($query);

                        if (!$result) {
                        print('クエリーが失敗しました。' . $mysqli->error);
                        $mysqli->close();
                        exit();
                        }

                        $sum[$num] = 0;

                        // ユーザー情報の取り出し
                        while ($row = $result->fetch_assoc()) {
                            $sum_star[$num] += $row['star'];
                            $sum[$num]++;
                        }
                        if($sum_star[$num]!=NULL || $sum[$num] != NULL){
                          $average_star[$num] = $sum_star[$num] / $sum[$num];           
                          $average_star[$num] = round($average_star[$num], 1);
                        }
                        else{
                          $average_star[$num] = 0;
                        }        
                        

                        if($sum[$num] == 0){
                            $average_star[$num] = 0;
                        }
                        $num ++;
                      
                }
                for($num=0; $num<20; $num++){
                    if(!$editer_username[$num]){
                    }
                    else{
                    ?>
                    <?php
                    if($editer_register_year_time[$num]<=2021 && $editer_register_month_time[$num]<=5 && $editer_register_month_time[$num]<=31){
                      ?>
                      <div class="editer_list_detail triangle">
                      <?php
                    }
                    else{
                      ?>
                      <div class="editer_list_detail">
                      <?php
                    }
                    ?>
                        <div class="editer_list_detail_top">
                            <div class="editer_list_detail_top_left">
                                <img src="profile_img/editer/<?php echo $editer_image_name[$num]; ?>">
                            </div>
                            <div class="editer_list_detail_top_right">
                                <h3><?php echo $editer_username[$num] ;?></h3>
                                <p><span class="star5_rating" data-rate="<?php echo $average_star[$num]; ?>"></span></p></p> 
                            </div>
                        </div>
                        <div class="editer_list_detail_bottom">
                            <div class="editer_list_detail_bottom_left">
                                <p><span class="background_gray">実績</span><?php echo $sum[$num]; ?>件</p>
                                <p><span class="background_gray">単価</span>¥<?php echo $editer_unit_jpy_price[$num] ;?>-</p>
                            </div>
                            <div class="editer_list_detail_bottom_right">
                                <p><a href="profile.php?id=<?php echo $editer_id[$num]; ?>">→</a></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                }
                ?>
          </div>
          <?php include('footer.php'); ?>
</body>
</html>