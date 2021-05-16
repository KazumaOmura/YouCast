<?php
ob_start();
session_start();
$session_flag = 1;
if(!isset($_SESSION['user']) != "") {
  // header("Location: index.php");
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
if($user_authority != 0){
    header("Location: home.php");
}
?>

<!DOCTYPE HTML>
<br lang="ja">

<head>
  <meta charset="UTF-8">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>受注一覧 - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/mypage.css">
</head>

<?php include('header.php'); ?>
<main>
            <div style="margin-top:30px;" class="mypage_right_bottom">
            <p style="margin-top:0; text-align:left; font-weight:bold;">発注履歴</p>
            <hr style="border-top: 3px solid #1E82DB !important; margin-top:-10px;">
              <?php
               $query = "SELECT * FROM order_receive WHERE client_id = '$user_id'";
               $result = $mysqli->query($query);
             
               if(!$result){
                 print('クエリーが失敗しました。' . $mysqli->error);
                 $mysqli->close();
                 exit();
               }
               
               while ($row = $result->fetch_assoc()) {
                $project_id = $row['id'];
                $project_name = $row['project_name'];
                 $project_price = $row['price'];
                 $project_done_flag = $row['done_flag'];
                 $project_price_jpy = number_format($project_price);
                 ?>
                 <table style="margin-bottom:10px;">
                  <tr>
                    <?php
                    if(!$project_name){
                      ?>
                      <td><a href="chat.php?project_id=<?php echo $project_id; ?>">プロジェクト名未設定</a></td>
                      <?php
                      if($project_done_flag == 0){
                        ?>
                        <td class="done_flag" rowspan="2"><p>条件を承認してください</p></td>
                        <?php
                      }
                      else if($project_done_flag == 1){
                        ?>
                        <td class="done_flag" rowspan="2"><p>編集者承認待ち</p></td>
                        <?php
                      }
                      else if($project_done_flag == 2){
                        ?>
                        <td class="done_flag" rowspan="2"><p>支払いを行なってください</p></td>
                        <?php
                      }
                      else if($project_done_flag == 3){
                        ?>
                        <td class="done_flag" rowspan="2"><p>納品待ち</p></td>
                        <?php
                      }
                      else if($project_done_flag == 4){
                        ?>
                        <td class="done_flag" rowspan="2"><p>評価を行なってください</p></td>
                        <?php
                      }
                      else if($project_done_flag == 5){
                        ?>
                        <td class="done_flag" rowspan="2"><p>編集者評価待ち</p></td>
                        <?php
                      }
                      else if($project_done_flag == 6){
                        ?>
                        <td class="done_flag" rowspan="2"><p>完了</p></td>
                        <?php
                      }
                      ?>
                      <?php
                    }
                    else{
                      ?>
                      <td><a href="chat.php?project_id=<?php echo $project_id; ?>"><?php echo $project_name; ?></a></td>
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
            </div>
</main>

</body>
<footer>
</footer>
</html>