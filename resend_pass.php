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

  if(isset($_POST['register'])) {
    header("Location: register.php");
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
    <title>ワンタイムパスワード発行</title>
  </head>

  <body>
    <?php include('header_index.php'); ?>

    <main>
      <div id="login_form">
        <form method="post" action="auth_onetime_pass.php">
          <p class="login_index">ワンタイムパスワード発行</p>
          <p>登録済みのメールアドレス</p>
          <input type="text" name="email" placeholder="メールアドレス" required="" id="username" />
          <button name="submit" type="submit" class="btn">送信</button>
        </form>
      </div>
    </main>

    <p class="register_a_tag">アカウントはありますか？ <a href="register.php">新規会員登録</a></p>

    <footer>
    </footer>

  </body>
</html>