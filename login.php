<?php

ob_start();
session_start();
if( isset($_SESSION['user']) != "") {
  // ログイン済みの場合はリダイレクト
  header("Location: home.php");
}
// DBとの接続
include_once 'dbconnect.php';

// ログインボタンがクリックされたときに下記を実行
if(isset($_POST['login'])) {

    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $mysqli->real_escape_string($_POST['password']);
  
    require "function/return_sql.php";

    $query = select_sql_1("user", "email", $email);
    
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
  
    // パスワード(暗号化済み）とユーザーIDの取り出し
    while ($row = $result->fetch_assoc()) {
      $db_hashed_pwd = $row['password'];
      $id = $row['id'];
    }
  
    // ハッシュ化されたパスワードがマッチするかどうかを確認
    if (password_verify($password, $db_hashed_pwd)) {
      $_SESSION['user'] = $id;
      header("Location: home.php");
      exit;
    }else { 
      ?>
      <div role="alert" id="alert">メールアドレスとパスワードが一致しません。</div>
      <?php
    }
  }



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
    <title>ログイン</title>
  </head>

  <body>
    <?php include('header_index.php'); ?>

    <main>
      <div id="login_form">
        <form method="post">
          <p class="login_index">ログイン</p>
          <p>メールアドレス</p>
          <input type="text" name="email" placeholder="メールアドレス" required="" id="username" />
          <p>パスワード</p>
          <input type="password" name="password" placeholder="パスワード" required="" id="password" />
          <input style="width:auto !important; margin-right:8px !" type="checkbox" id="show_pass">表示
          <button name="login" type="submit" class="btn">ログイン</button>
        </form>
        <p>パスワード忘れた方は<a href="resend_pass.php">こちら</a></p>
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