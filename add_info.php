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

// 削除ボタンがクリックされたときに下記を実行
if(isset($_POST['delete_info'])) {

	$ds = $mysqli->real_escape_string($_POST['delete_info']);

	// クエリの実行
	$query = "DELETE FROM information WHERE id='$ds'";
	$result = $mysqli->query($query);
	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
	header("Location: add_info.php");
}

// 編集ボタンがクリックされたときに下記を実行
if(isset($_POST['edit_info'])) {

	$ds = $mysqli->real_escape_string($_POST['edit_info']);

	header("Location: edit_info.php?id=$ds");
}

//ここから
if(isset($_POST['add_info'])){
    //エスケープして前処理
    $new_info_title = $mysqli->real_escape_string($_POST['new_info_title']);
    $new_info = $mysqli->real_escape_string($_POST['new_info']);
    $news_type = $mysqli->real_escape_string($_POST['news_type']);
    $new_mail_switch = $mysqli->real_escape_string($_POST['mail_switch']);
    $times = date("Y/m/d"); 
    
    $query = "INSERT INTO information(news_type,title,information,times) VALUES ('$news_type','$new_info_title','$new_info','$times')";
    $result = $mysqli->query($query);
		if(!$result){
			print('クエリーが失敗しました。' . $mysqli->error);
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
          $subject .= $new_info_title; // 題名 
          $body = "YouCastをご利用いただき、誠にありがとうございます。";
          $body .= "\n\n";
          $body .= $new_info;
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
  <title>お知らせ一覧 - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<?php include('header.php'); ?>
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
						?>
						<option value=<?php echo $news_type?>><?php echo $news_type ;?></option>
						<?php
					}
				?>
				</select>
    <input type="text" placeholder="題名" name="new_info_title" value="<?php $new_info?>" required /> 
    <textarea placeholder="お知らせを追加" name="new_info" required></textarea> 
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
    <button type="submit" name="add_info" class="btn">お知らせ追加</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>お知らせの種類</th>
        <th>お知らせタイトル</th>
        <th>お知らせ内容</th>
        <th>時間</th>
        <th>編集</th>
        <th>削除</th>
    </tr>
    <tr>
        <?php
            // ユーザーIDからユーザー名を取り出す
            $query = "SELECT * FROM information";
            $result = $mysqli->query($query);

            if (!$result) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
            }

            // ユーザー情報の取り出し
            while ($row = $result->fetch_assoc()) {
                $info_id = $row['id'];
                $news_type = $row['news_type'];
                $new_info_title = $row['title'];
                $new_info = $row['information'];
                $times = $row['times'];
            
        ?>
        <td><?php echo $info_id; ?></td>
        <td><?php echo $news_type; ?></td>
        <td><?php echo $new_info_title; ?></td>
        <td><?php echo $new_info; ?></td>
        <td><?php echo $times; ?></td>
        <td>
				<form method ="post">
				<button type="submit" name="edit_info" class="btn" value = "<?php echo $info_id?>">編集</button>				
				</form>
			</td>
        <td>
				<form method ="post">
				<button type="submit" name="delete_info" class="btn" value = "<?php echo $info_id?>">削除</button>				
				</form>
			</td>
    </tr>
    <?php
    }
    ?>
</table>
</body>
</html>