<?php
ob_start();
session_start();
date_default_timezone_set('Asia/Tokyo');
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
  $authority = $row['authority'];
}

if($authority != 2){
    header("Location: index.php");
}

$edit_id = $_GET['id'];

//ここから
if(isset($_POST['edit_info'])){
    //エスケープして前処理
    $edit_id2 = $mysqli->real_escape_string($_POST['edit_info_title']);
    $edit_info_title = $mysqli->real_escape_string($_POST['edit_info_title']);
    $edit_info = $mysqli->real_escape_string($_POST['edit_info_body']);
    $news_type = $mysqli->real_escape_string($_POST['news_type']);
    $new_mail_switch = $mysqli->real_escape_string($_POST['mail_switch']);
    $times = date("Y/m/d"); 
    
    $query = "UPDATE information SET news_type = '$news_type', title='$edit_info_title',information='$edit_info' WHERE id = $edit_id";
    $result = $mysqli->query($query);
		if(!$result){
			print('クエリーが失敗しました。aaa' . $mysqli->error);
		}else{
      if($new_mail_switch == 1){
        $query = "SELECT * FROM user";
        $result = $mysqli->query($query);
        if (!$result) {
          print('クエリーが失敗しました。' . $mysqli->error);
          $mysqli->close();
          exit();
        }
        while($row = $result->fetch_assoc()){ 
          $toemail_adress = $row['email'];

          // メール関数
          $host_email = "info@youcast.jp";
          $to = $toemail_adress;
          mb_language("Japanese"); 
          mb_internal_encoding("UTF-8");
          $subject = "【YouCast】"; // 題名 
          $subject .= $edit_info_title; // 題名 
          $body = "YouCastをご利用いただき、誠にありがとうございます。";
          $body .= "\n\n";
          $body .= "お知らせが変更されました。以下をご確認ください。";
          $body .= "\n\n";
          $body .= $edit_info;
          $body .= "\n\n";
          $body .= "-------------------------------------------";
          $body .= "\n";
          $body .= "【YouCastからのお知らせ】";
          $body .= "\n";
          $body .= "このお知らせは、メールにも自動配信されます。";
          $body .= "\n";
          $body .= "送信専用アドレスから送信しているため、";
          $body .= "\n";
          $body .= "返信をしても回答できませんのでご注意ください。";
          $body .= "\n";
          $body .= "質問等がありましたら直接管理者へご連絡ください。";
          $body .= "\n";
          $body .= "-------------------------------------------";
        
          $header = "From: $host_email";
          
          mb_send_mail($to, $subject, $body, $header);
        }
      }
      else{

      }
      header("Location: add_info.php");
    }
}

//ここまで

if($authority != 2){
    header("Location: home.php");
}

// ユーザーIDからユーザー名を取り出す
$queryy = "SELECT * FROM client_prof WHERE user_id = '$user_id'";
$resultt = $mysqli->query($queryy);

if (!$resultt) {
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}
while ($roww = $resultt->fetch_assoc()) {
  $user_username = $roww['username'];
}

// データベースの切断
$result->close();
?>

<!DOCTYPE HTML>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>お知らせ修正 - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<?php 
    include('header.php'); 
    $query = "SELECT * FROM information WHERE id = '$edit_id'";
    $result = $mysqli->query($query);

    if (!$result) {
        print('クエリーが失敗しました。news_type' . $mysqli->error);
        $mysqli->close();
        exit();
    }

    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $get_news_type = $row['news_type'];
        $title = $row['title'];
        $information = $row['information'];
    }
?>

<form method="post">
        <p>お知らせの種類</p>
				<select style="display:block; width:33%; float:left;" name = "news_type" required>
        <option value = "aaa">お知らせを選択</option>
				<?php 
          // ユーザーIDからユーザー名を取り出す
          $query = "SELECT news_type FROM news_type";
          $result = $mysqli->query($query);

          if (!$result) {
          print('クエリーが失敗しました。news_type' . $mysqli->error);
          $mysqli->close();
          exit();
          }

          // ユーザー情報の取り出し
          while ($row = $result->fetch_assoc()) {
              $id = $row['id'];
              $news_type = $row['news_type'];
              if($get_news_type == $news_type){
                ?>
						<option value="<?php echo $news_type?>" selected><?php echo $news_type ;?></option>
						<?php
              }
              else{?>
                <option value="<?php echo $news_type?>"><?php echo $news_type ;?></option>
              <?php
                }		
				}
				?>
				</select>
    <input type="text" placeholder="題名" name="edit_info_title" value="<?php echo $title?>" required /> 
    <textarea placeholder="お知らせを追加" name="edit_info_body" required><?php echo $information?></textarea>
    <input type="hidden" name="news_id_hidden" value="<?php echo $id?>"> 
    <p>メールでも一斉送信</p>
        <div class="switch">
            <label>
            Off
            <input type="checkbox" name="mail_switch" value=1>
            <span class="lever"></span>
            On
            </label>
        </div>
        </br>
    <button type="submit" name="edit_info" class="btn">お知らせ変更</button>
</form>
</body>
</html>