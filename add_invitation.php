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
  $authority = $row['authority'];
}

$switch = $_POST['switch'];
if(!$switch){
  header("Location: invitation.php");
  exit;
}

            $query2 = "SELECT * FROM invitation_code WHERE generate_user_id = $user_id";
            $result2 = $mysqli->query($query2);

            if (!$result2) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
            }

            $invitation_num = 0;
            // ユーザー情報の取り出し
            while ($row2 = $result2->fetch_assoc()) {
                $invitation_id = $row2['id'];
                $invitation_num++;
            }

if($authority == 0){
    header("Location: home.php");
    exit;
}
if($invitation_num == 2 && $authority == 1){
  header("Location: invitation.php"); //adminに遷移
  exit;
}

// 文字列の生成に使用する文字を変数へ代入(英数字)
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
$today = date("Y-m-d H:i:s");   
// 変数の初期化
$new_code = '';

// 繰り返し処理でランダムに文字列を生成(8文字)
for ($i = 0; $i < 10; $i++) {
	$new_code .= $chars[mt_rand(0, 61)];
}

// 生成された文字列を出力
echo $new_code;

$query = "INSERT INTO invitation_code (code,generate_user_id) VALUES('$new_code','$user_id')";
        
  $result = $mysqli->query($query);
    if (!$result) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    else{
    header("Location: invitation.php"); //adminに遷移
    }

// データベースの切断
$result->close();
?>