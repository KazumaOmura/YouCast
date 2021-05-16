<?php
ob_start();
session_start();
$session_flag = 1;
if(!isset($_SESSION['user']) != "") {
  // header("Location: index.php");
  $session_flag = 0;
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
  $user_authority = $row['authority'];
}

?>

<!DOCTYPE HTML>
<br lang="ja">

<head>
  <meta charset="UTF-8">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>お知らせ一覧</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/mypage.css">
</head>

<?php include('header.php'); ?>
<main>
            <div style="margin-top:30px;" class="mypage_right_bottom">
            <p style="margin-top:0; text-align:left; font-weight:bold;">未読メッセージ一覧</p>
            <hr style="border-top: 3px solid #1E82DB !important; margin-top:-10px;">
              <?php
              if($user_authority == 0){
                $query = "SELECT * FROM chat WHERE client_id = '$user_id' AND send_flag=0 AND read_flag=0";
              }
              else if($user_authority == 1){
               $query = "SELECT * FROM chat WHERE editer_id = '$user_id' AND send_flag=1 AND read_flag=0";
              }
              $result = $mysqli->query($query);
               if(!$result){
                 print('クエリーが失敗しました。' . $mysqli->error);
                 $mysqli->close();
                 exit();
               }
               ?>
               <table style="margin-bottom:10px;">
                 <tr>
                    <th>日時</th>
                    <th></th>
                    <th>お知らせ</th>
                 </tr>
                 <?php
               while ($row = $result->fetch_assoc()) {
                $chat_id = $row['id'];
                $chat_text = $row['text'];
                $chat_client_id = $row['client_id'];
                $chat_editer_id = $row['editer_id'];
                $chat_send_time = $row['send_time'];
                $chat_chat_id = $row['chat_id'];
                 ?>
                 <tr>
                    <td><?php echo $chat_send_time; ?></td>
                    <?php
                    if($user_authority == 0){
                      $query2 = "SELECT * FROM editer_prof WHERE user_id = '$chat_editer_id'";
                      $result2 = $mysqli->query($query2);
                      if(!$result2){
                        print('クエリーが失敗しました。' . $mysqli->error);
                        $mysqli->close();
                        exit();
                      }
                      while ($row2 = $result2->fetch_assoc()) {
                        $chat_editer_image_name = $row2['image_name'];
                      }
                    }
                    else if($user_authority == 1){
                      $query2 = "SELECT * FROM client_prof WHERE user_id = '$chat_client_id'";
                      $result2 = $mysqli->query($query2);
                      if(!$result2){
                        print('クエリーが失敗しました。' . $mysqli->error);
                        $mysqli->close();
                        exit();
                      }
                      while ($row2 = $result2->fetch_assoc()) {
                        $chat_client_image_name = $row2['image_name'];
                      }
                    }
                    ?>
                    <?php
                    if($user_authority == 0){
                      ?>
                      <td><img style="width:30px; height:30px; object-fit:cover;" src="profile_img/editer/<?php echo $chat_editer_image_name; ?>"></td>
                      <?php
                    }
                    else if($user_authority == 1){
                      ?>
                      <td><img style="width:30px; height:30px; object-fit:cover;" src="profile_img/client/<?php echo $chat_client_image_name; ?>"></td>
                      <?php
                    }
                    ?>
                    <td><a href="https://youcast.jp/chat.php?project_id=<?php echo $chat_chat_id; ?>"><?php echo $chat_text; ?></a></td>
                 </tr>
                <?php
               }
              ?>
              </table>
            </div>
</main>

</body>
<footer>
</footer>
</html>