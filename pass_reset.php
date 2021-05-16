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
  $get_onetime_pass = $_POST['password'];

//   if(!$get_email){
//     header("Location:index.php");
//   }

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

  $query2 = select_sql_1("pass_reset_user", "email", $get_email);
  
  $result2 = $mysqli->query($query2);
  if (!$result2) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }

  // パスワード(暗号化済み）とユーザーIDの取り出し
  while ($row2 = $result2->fetch_assoc()) {
    $user_id = $row2['user_id'];
    $onetime_pass = $row2['onetime_pass'];
  }

  if($id != $user_id){
    header("Location:login.php");
  }
  if($get_onetime_pass != $onetime_pass){
    $query4 = "DELETE FROM pass_reset_user WHERE email = '$get_email' ";
    $result4 = $mysqli->query($query4);

    if (!$result4) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
    }
    header("Location:login.php");
  }
  

  if(isset($_POST['reset_pass'])) {
    $user_id = $mysqli->real_escape_string($_POST['id']);
    $new_password = $mysqli->real_escape_string($_POST['new_password']);
    if(!$user_id){
        header("Location:login.php");
    }
    $new_password = password_hash($new_password, PASSWORD_DEFAULT);
  
    $query3 = "UPDATE user SET password='$new_password' WHERE id = $user_id ";
    $result3 = $mysqli->query($query3);
  
    if (!$result3) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    else{
        $query4 = "DELETE FROM pass_reset_user WHERE user_id = '$user_id' ";
        $result4 = $mysqli->query($query4);
    
        if (!$result4) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
        }
        else{
            header("Location:login.php");  
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
    <title>パスワードリセット</title>
  </head>

  <body>
    <?php include('header_index.php'); ?>

    <main>
      <div id="login_form">
        <form method="post">
          <p class="login_index">パスワードリセット</p>
          <p>新規パスワード</p>
          <input type="hidden" name="id" value="<?php echo $id; ?>" />
          <input type="password" name="new_password" placeholder="新規パスワード" required="" id="password" />
          <input type="checkbox" id="show_pass">表示
          <button name="reset_pass" type="submit" class="btn">再設定</button>
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