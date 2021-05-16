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
  $user_register_year = $row['register_year_time'];
  $user_register_month = $row['register_month_time'];
  $user_register_day = $row['register_day_time'];
}

if($user_prof_done == 0){
  header("Location: prof_insert.php");
}

if($user_authority == 0){
  $query = "SELECT * FROM client_prof WHERE user_id='$user_id'";
  $result = $mysqli->query($query);
}
else{
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
  $user_prof_para = $row['prof_para'];
  $user_unit_price = $row['unit_price'];
  $user_edit_software = $row['edit_software'];
  $portfolio_title = $row['portfolio_title'];
	$portfolio_url = $row['portfolio_url'];
  $user_unit_price_jpy = number_format($user_unit_price);
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

  <title>マイページ - YouCast</title>
  <link rel="stylesheet" href="css/mypage.css">
</head>
<body>
<?php include('header.php'); ?>
<main>
    <div id="mypage">
        <div class="mypage_left">
            <div class="mypage_left_top">
            <?php
                if($user_authority == 0){
                ?>
                <img src="profile_img/client/<?php echo $user_image_name; ?>">
                <?php
                }
                else{
                ?>
                <img src="profile_img/editer/<?php echo $user_image_name; ?>">
                <?php
                }
            ?>
            </div>
            <?php
            if($user_authority == 0){
              ?>
              <div class="mypage_left_bottom">
                  <p style="font-weight:bold;"><?php echo $user_username; ?></p>
                  <!-- <table>
                      <tr>
                          <th>発注実績</th>
                          <th>評価</th>
                      </tr>
                      <tr>
                          <td>3件</td>
                          <td>4.8</td>
                      </tr>
                  </table> -->
                  <table>
                      <tr>
                          <th>YouTubeチャンネル</th>
                          <td><a href="https://Google.com">こちら</a></td>
                      </tr>
                      <tr>
                          <th>登録日</th>
                          <td><?php echo  $user_register_year; ?> / <?php echo  $user_register_month; ?> / <?php echo  $user_register_day; ?></td>
                      </tr>
                  </table>
                  <!-- <table>
                      <tr>
                          <th>対応可能編集ソフト</th>
                      </tr>
                      <tr>
                          <td>FCPX</td>
                      </tr>
                      <tr>
                          <td>AdobePremiumPro</td>
                      </tr>
                  </table> -->
              </div>
              <?php
            }
            else{
              ?>
              <div class="mypage_left_bottom">
                  <p style="font-weight:bold;"><?php echo $user_username; ?></p>

                  <?php
                        $query = "SELECT * FROM evaluation WHERE editer_id=".$_SESSION['user']." AND direction=0";
                        $result = $mysqli->query($query);

                        if (!$result) {
                        print('クエリーが失敗しました。' . $mysqli->error);
                        $mysqli->close();
                        exit();
                        }

                        $sum = 0;

                        // ユーザー情報の取り出し
                        while ($row = $result->fetch_assoc()) {
                            $sum_star += $row['star'];
                            $sum++;
                        }

                        if($sum_star!=NULL || $sum != NULL){
                            $average_star = $sum_star / $sum;           
                            $average_star = round($average_star, 1);
                          }
                          else{
                            $average_star = 0;
                          } 

                        if($sum == 0){
                            $average_star = 0;
                        }
                      ?>

                  <div class="performance_evaluation">
                      <div class="performance">
                          <div class="top">
                              <p style="font-weight:bold; color:#fff;">受注実績</p>
                          </div>
                          <div class="bottom performance_bottom">
                                <p style="margin:0;"><?php echo $sum; ?>件</p>
                          </div>
                      </div>
                      <div class="evaluation">
                      <div class="top">
                              <p style="font-weight:bold; color:#fff;">評価</p>
                          </div>
                          <div class="bottom evaluation_bottom">
                          <p><span class="star5_rating" data-rate="<?php echo $average_star; ?>"></span></br><?php echo $average_star; ?></p></p> 
                          </div>
                      </div>
                  </div>
                  <table>
                      <tr>
                          <th>1本あたりの受注価格</th>
                          <td>¥<?php  echo $user_unit_price_jpy; ?></td>
                      </tr>
                      <tr>
                          <th>登録日</th>
                          <td><?php echo  $user_register_year; ?> / <?php echo  $user_register_month; ?> / <?php echo  $user_register_day; ?></td>
                      </tr>
                  </table>
                  <table>
                      <tr>
                          <th>対応可能編集ソフト</th>
                      </tr>
                      <?php
                      if(!$user_edit_software){
                    }
                    else{
                      $array_user_edit_software = preg_split("/,/",$user_edit_software);
                      $count_array_user_edit_software = count($array_user_edit_software);
                      for ($i = 0; $i < $count_array_user_edit_software; $i++){
                        $query = "SELECT * FROM edit_software WHERE id = $array_user_edit_software[$i]";
                        $result = $mysqli->query($query);

                        if (!$result) {
                        print('クエリーが失敗しました。' . $mysqli->error);
                        $mysqli->close();
                        exit();
                        }

                        // ユーザー情報の取り出し
                        while ($row = $result->fetch_assoc()) {
                            $edit_software_name = $row['edit_software_name'];
                            ?>
                            <tr>
                                <td><?php echo $edit_software_name; ?></td>
                            </tr>
                            <?php
                        }
                      }
                    }
                      ?>
                  </table>
                  <?php
                  if(!$portfolio_title){

                  }
                  else{
                    ?>
                    <table>
                      <tr>
                          <th colspan="2">ポートフォリオ</th>
                      </tr>
                      <tr>
                          <th><?php echo $portfolio_title; ?></th>
                          <td><a href="<?php echo $portfolio_url; ?>"><?php echo $portfolio_url; ?></a></td>
                      </tr>
                  </table>
                    <?php
                  }
                  ?>
              </div>
              <?php
            }
            ?>
        </div>
        <div class="mypage_right">
        <p style="margin-top:0; text-align:left; font-weight:bold;">プロフィール</p>
          <hr style="border-top: 3px solid #1E82DB !important; margin-top:-10px;">
            <p><?php echo $user_prof_para; ?></p>
            <a href="prof_update.php" class="btn">プロフィールを変更する</a>
        </div>
    </div>
</main>
</body>
</html>