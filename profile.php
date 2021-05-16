<?php
ob_start();
session_start();
$session_flag = 1;
if(!isset($_SESSION['user']) != "") {
  header("Location: login.php");
  $session_flag = 0;
}
include_once 'dbconnect.php';

if($session_flag == 1){
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
    $user_authority = $row['authority'];
  }
}

$get_id = $_GET['id'];

$query = "SELECT * FROM user WHERE id='$get_id'";
$result = $mysqli->query($query);

if (!$result) {
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}

// ユーザー情報の取り出し
while ($row = $result->fetch_assoc()) {
  $get_authority = $row['authority'];
  $get_register_year_time = $row['register_year_time'];
  $get_register_month_time = $row['register_month_time'];
  $get_register_day_time = $row['register_day_time'];
}

if($get_authority == 0){
  $query = "SELECT * FROM client_prof WHERE user_id = '$get_id'";
  $result = $mysqli->query($query);

  if(!$result){
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
  
  while ($row = $result->fetch_assoc()) {
    $client_id = $row['id'];
    $client_user_name = $row['username'];
    $client_gender = $row['gender'];
    $client_youtube_url = $row['youtube_url'];
    $client_image_name = $row['image_name'];
  }
}
if($get_authority == 0){
  $query = "SELECT * FROM client_prof WHERE user_id = '$get_id'";
  $result = $mysqli->query($query);

  if(!$result){
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
  
  while ($row = $result->fetch_assoc()) {
    $client_username = $row['username'];
  }
}
else if($get_authority == 1){
  $query = "SELECT * FROM editer_prof WHERE user_id = '$get_id'";
  $result = $mysqli->query($query);

  if(!$result){
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
  
  while ($row = $result->fetch_assoc()) {
    $editer_username = $row['username'];
    $editer_gender = $row['gender'];
    $editer_birth_year = $row['birth_year'];
    $editer_birth_month = $row['birth_month'];
    $editer_birth_day = $row['birth_day'];
    $editer_image_name = $row['image_name'];
    $editer_prof_para = $row['prof_para'];
    $user_edit_software = $row['edit_software'];
    $portfolio_title = $row['portfolio_title'];
		$portfolio_url = $row['portfolio_url'];
    $editer_unit_price = $row['unit_price'];
    $editer_unit_price_jpy = number_format($editer_unit_price);
  }
}
?>

<!DOCTYPE HTML>
<br lang="ja">

<head>
  <meta charset="UTF-8">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
  if($get_authority == 0){
    ?>
    <title><?php echo $client_username?>のPROFILE</title>
    <?php
  }
  else if($get_authority == 1){
    ?>
    <title><?php echo $editer_username?>のPROFILE</title>
    <?php
  }
  ?>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/mypage.css">
</head>

<?php include('header.php'); ?>
<main>
    <div id="mypage">
        <div class="mypage_left">
            <div class="mypage_left_top">
            <?php
                if($get_authority == 0){
                ?>
                <img src="profile_img/client/<?php echo $client_image_name; ?>">
                <?php
                }
                if($get_authority == 1){
                ?>
                <img src="profile_img/editer/<?php echo $editer_image_name; ?>">
                <?php
                }
            ?>
            </div>
            <?php
            if($get_authority == 0){
              ?>
              <div class="mypage_left_bottom">
                  <p style="font-weight:bold;"><?php echo $client_username; ?></p>
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
                          <td><?php echo  $get_register_year_time; ?> / <?php echo  $get_register_month_time; ?> / <?php echo  $get_register_day_time; ?></td>
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
            else if($get_authority == 1){
              ?>
              <div class="mypage_left_bottom">
                  <p style="font-weight:bold;"><?php echo $editer_username; ?></p>
                  <?php
                        $query = "SELECT * FROM evaluation WHERE editer_id='$get_id' AND direction=0";
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

                        if($sum_star[$num]!=NULL || $sum[$num] != NULL){
                          $average_star[$num] = $sum_star[$num] / $sum[$num];           
                          $average_star[$num] = round($average_star[$num], 1);
                        }
                        else{
                          $average_star[$num] = 0;
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
                          <td>¥<?php  echo $editer_unit_price_jpy; ?></td>
                      </tr>
                      <tr>
                          <th>登録日</th>
                          <td><?php echo  $get_register_year_time; ?> / <?php echo  $get_register_month_time; ?> / <?php echo  $get_register_day_time; ?></td>
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
            <p style="white-space:pre-wrap;"><?php echo $editer_prof_para; ?></p>
            <div style="margin-top:30px;" class="mypage_right_bottom">
              <p style="margin-top:0; text-align:left; font-weight:bold;">発注履歴</p>
            <hr style="border-top: 3px solid #1E82DB !important; margin-top:-10px;">
              <?php
               $query = "SELECT * FROM order_receive WHERE client_id = '$user_id' AND editer_id = '$get_id'";
               $result = $mysqli->query($query);
             
               if(!$result){
                 print('クエリーが失敗しました。' . $mysqli->error);
                 $mysqli->close();
                 exit();
               }
               
               while ($row = $result->fetch_assoc()) {
                $project_id = $row['id'];
                $project_project_name = $row['project_name'];
                 $project_price = $row['price'];
                 $project_done_flag = $row['done_flag'];
                 $project_price_jpy = number_format($project_price);
                 ?>
                 <table style="margin-bottom:10px;">
                  <tr>
                    <?php
                    if(!$project_project_name){
                      ?>
                      <td><a href="chat.php?project_id=<?php echo $project_id; ?>">名称未設定</a></td>
                      <?php
                      if($project_done_flag == 0){
                        ?>
                        <td class="done_flag" rowspan="2"><p>未承認</p></td>
                        <?php
                      }
                      else if($project_done_flag == 1){
                        ?>
                        <td class="done_flag" rowspan="2"><p>クライアント価格承認</p></td>
                        <?php
                      }
                      else if($project_done_flag == 2){
                        ?>
                        <td class="done_flag" rowspan="2"><p>編集者価格承認</p></td>
                        <?php
                      }
                      else if($project_done_flag == 3){
                        ?>
                        <td class="done_flag" rowspan="2"><p>仮払い完了</p></td>
                        <?php
                      }
                      else if($project_done_flag == 4){
                        ?>
                        <td class="done_flag" rowspan="2"><p>納品確認待ち</p></td>
                        <?php
                      }
                      else if($project_done_flag == 5){
                        ?>
                        <td class="done_flag" rowspan="2"><p>完了</p></td>
                        <?php
                      }
                      ?>
                      <?php
                    }
                    else{
                      ?>
                      <td><a href="chat.php?project_id=<?php echo $project_id; ?>"><?php echo $project_project_name; ?></a></td>
                      <?php
                      if($project_done_flag == 0){
                        ?>
                        <td class="done_flag" rowspan="2"><p>未承認</p></td>
                        <?php
                      }
                      else if($project_done_flag == 1){
                        ?>
                        <td class="done_flag" rowspan="2"><p>クライアント価格承認</p></td>
                        <?php
                      }
                      else if($project_done_flag == 2){
                        ?>
                        <td class="done_flag" rowspan="2"><p>編集者価格承認</p></td>
                        <?php
                      }
                      else if($project_done_flag == 3){
                        ?>
                        <td class="done_flag" rowspan="2"><p>仮払い完了</p></td>
                        <?php
                      }
                      else if($project_done_flag == 4){
                        ?>
                        <td class="done_flag" rowspan="2"><p>納品確認待ち</p></td>
                        <?php
                      }
                      else if($project_done_flag == 5){
                        ?>
                        <td class="done_flag" rowspan="2"><p>完了</p></td>
                        <?php
                      }
                      ?>
                      <?php
                    }
                    ?>
                  </tr>
                  <tr>
                    <td>報酬額 ¥<?php echo $project_price_jpy; ?></td>
                  </tr>
                </table>
                <?php
               }
              ?>
              <?php
            if($user_authority == 0){
            ?>
            <form method="post" action="add_request.php">
                <input type="hidden" name="post_price" value="<?php echo $editer_unit_price;?>">
                <input type="hidden" name="post_client_id" value="<?php echo $user_id;?>">
                <input type="hidden" name="post_editer_id" value="<?php echo $get_id;?>">
                <button type="submit" name="post_editer_id" class="btn" value="<?php echo $get_id; ?>">新しく依頼する</button>
            </form>
            <?php
            }
            ?>
            </div>
        </div>
    </div>
</main>
<?php
?>

</body>
<footer>
</footer>
</html>