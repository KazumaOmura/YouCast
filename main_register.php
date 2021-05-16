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
if(isset($_POST['contemporary_signup'])) {

    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $auth_code= $mysqli->real_escape_string($_POST['auth_code']);
  
    require "function/return_sql.php";

    $query = select_sql_1("contemporary_user", "email", $email);
    
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
        $query2 = select_sql_1("contemporary_user", "email", $email);
    
        $result2 = $mysqli->query($query2);
        if (!$result2) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
        }
    
        // パスワード(暗号化済み）とユーザーIDの取り出し
        while ($row2 = $result2->fetch_assoc()) {
            $contemporary_user_id = $row2['id'];
            $user_auth_code = $row2['auth_code'];
        }
        if($user_auth_code == $auth_code){
            date_default_timezone_set('Asia/Tokyo');
            $year_time = date("Y"); 
            $month_time = date("m"); 
            $day_time = date("d"); 
            $query3 = "INSERT INTO user(email,password,prof_done,authority,register_year_time,register_month_time,register_day_time) VALUES('$email','$db_hashed_pwd',0,0,'$year_time','$month_time','$day_time')";
          
            $result3 = $mysqli->query($query3);
            if (!$result3) {
                print('クエリーが失敗しました。' . $mysqli->error);
                $mysqli->close();
                exit();
                ?>
                <div role="alert" id="alert">エラーが発生しました</div>
                <?php
            }
            else{
                $query4 = select_sql_1("user", "email", $email);
    
                $result4 = $mysqli->query($query4);
                if (!$result4) {
                print('クエリーが失敗しました。' . $mysqli->error);
                $mysqli->close();
                exit();
                }
            
                // パスワード(暗号化済み）とユーザーIDの取り出し
                while ($row4 = $result4->fetch_assoc()) {
                    $session_id = $row4['id'];
                }
                $query5 = "DELETE FROM contemporary_user WHERE email='$email'";
                $result5 = $mysqli->query($query5);
                if (!$result5) {
                    print('クエリーが失敗しました。' . $mysqli->error);
                    $mysqli->close();
                    exit();
                }

                $_SESSION['user'] = $session_id;
                header("Location: home.php");
                exit;
            }
        }
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
    <title>本人確認</title>
  </head>

  <body>
    <?php include('header_index.php'); ?>

    <main>
      <div id="login_form">
        <form method="post">
          <p class="login_index">本人確認</p>
          <p style="color:#222;">指定のメールアドレスに記載されている認証コードを入力して登録作業を行ってください。</p>
          <p>メールアドレス</p>
          <input type="text" name="email" placeholder="メールアドレス" required="" id="username" />
          <p>パスワード</p>
          <input type="password" name="password" placeholder="パスワード" required="" id="password" />
          <p>認証コード</p>
          <input type="password" name="auth_code" placeholder="認証コード" required="" id="password" />
          <input style="width:auto !important; margin-right:8px !" type="checkbox" id="show_pass">表示
          <button name="contemporary_signup" type="submit" class="btn">認証</button>
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