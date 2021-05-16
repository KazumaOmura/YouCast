<?php

ob_start();
session_start();
date_default_timezone_set('Asia/Tokyo');
if( isset($_SESSION['user']) != "") {
  // ログイン済みの場合はリダイレクト
  header("Location: home.php");
}
// DBとの接続
include_once 'dbconnect.php';

// ログインボタンがクリックされたときに下記を実行

  $get_email = $_POST['email'];
  $times = date("Y,m,d,H,i,s");
  
  if(!$get_email){
    header("Location:index.php");
  }

  require "function/return_sql.php";

  $query = select_sql_1("user", "email", $get_email);
  
  $result = $mysqli->query($query);
  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }

  // パスワード(暗号化済み）とユーザーIDの取り出し
  while ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $id = $row['id'];
  }

  if($get_email != $email){
      ?>
      <!-- <div role="alert" id="alert">メールアドレスとパスワードが一致しません。</div> -->
      <?php
      header("Location:resend_pass.php");
  }else{
        $query2 = select_sql_1("pass_reset_user", "email", $get_email);
        
        $result2 = $mysqli->query($query2);
        if (!$result2) {
          print('クエリーが失敗しました。' . $mysqli->error);
          $mysqli->close();
          exit();
        }

        // パスワード(暗号化済み）とユーザーIDの取り出し
        while ($row2 = $result2->fetch_assoc()) {
          $pass_reset_user_id = $row['id'];
        }
        if(!$pass_reset_user_id){
          
        }
        else{

      // 文字列の生成に使用する文字を変数へ代入(英数字)
      $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';

      // 変数の初期化
      $new_code = '';

      // 繰り返し処理でランダムに文字列を生成(8文字)
      for ($i = 0; $i < 6; $i++) {
    $new_code .= $chars[mt_rand(0, 61)];
      }

      $query ="INSERT INTO pass_reset_user(email,user_id,onetime_pass,time) VALUES ('$email','$id','$new_code','$times')";
      $result = $mysqli->query($query);
  if(!$result){
    print('クエリーが失敗しました。' . $mysqli->error);
  }else{
        $host_email = "info@youcast.jp";

        mb_language("Japanese"); 
        mb_internal_encoding("UTF-8");
        $subject = "【YouCast】パスワードリセット認証コード発行のお知らせ"; // 題名 
        $body .= "YouCastをご利用いただき、誠にありがとうございます。";
        $body .= "\n";
        $body .= "ご本人様確認のため、10分以内」に発行された認証コードを入力してパスワードの再設定を完了させて下さい。\n";
        // $body .= "https://youcast.jp/main_register.php";
        $body .= "\n";
        $body .= "___________________________\n";
        $body .= "認証コード：";
        $body .= $new_code;
        $body .= "\n";
        $body .= "___________________________\n";
        $body .= "\n";
        $body .= "-------------------------------------------------";
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
        $body .= "-------------------------------------------------";
        $to = $email;
        $header = "From: $host_email";
        
        mb_send_mail($to, $subject, $body, $header);
      }


  }
}
?>

<!DOCTYPE HTML>
<html lang="ja">
<meta charset="utf-8" />

  <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="js/main.js"></script>
    <title>ワンタイムパスワード認証</title>
  </head>

  <body>
    <?php include('header_index.php'); ?>

    <main>
      <div id="login_form">
        <form method="post" action="pass_reset.php">
          <p class="login_index">ワンタイムパスワード認証</p>
          <p style="color:#222;">指定のメールアドレスに記載されている認証コードを入力してパスワード再設定作業を行ってください。</p>
          <p style="color:red;">※認証コードは1回限り有効です。誤ったコードを入力した場合は最初からやり直してください。</p>
          <p>メールアドレス</p>
          <input type="text" name="email" placeholder="メールアドレス" required="" id="username" />
          <p>ワンタイムパスワード</p>
          <input type="password" name="password" placeholder="ワンタイムパスワード" required="" id="password" />
          <input type="checkbox" id="show_pass">表示
          <button name="login" type="submit" class="btn">認証</button>
        </form>
      </div>
    </main>
    <script type="text/javascript">
      const password = document.getElementById('password');
      const show_pass = document.getElementById('show_pass');

      show_pass.addEventListener('change',function(){
        if(show_pass.checked){
          password.setAttribute('type','text');
        }else{
          password.setAttribute('type','password');
        }

      },false);
    </script>

    <p class="register_a_tag">アカウントはありますか？ <a href="register.php">新規会員登録</a></p>

    <footer>
    </footer>

  </body>
</html>