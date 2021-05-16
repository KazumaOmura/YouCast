<?php
ob_start();
session_start();
date_default_timezone_set('Asia/Tokyo');
$year_time = date("Y"); 
$month_time = date("m"); 
$day_time = date("d"); 
if(isset($_SESSION['user']) != "") {
  // ログイン済みの場合はリダイレクト
  header("Location: home.php");
}
// DBとの接続
include_once 'dbconnect.php';

if(isset($_POST['signup_client'])) {
  //$username = $mysqli->real_escape_string($_POST['username']);
  $email = $mysqli->real_escape_string($_POST['email']);
  $password = $mysqli->real_escape_string($_POST['password']);
  $password = password_hash($password, PASSWORD_DEFAULT);

  require "function/return_sql.php";
  $query3 = "SELECT * FROM user";
  $result3 = $mysqli->query($query3);

  if (!$result3) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
  $switch = 1;
  while ($row3 = $result3->fetch_assoc()) {
    $switch_email = $row3['email'];
    if($switch_email == $email){
      $switch = 0;
    }
  }

  $query4 = "SELECT * FROM contemporary_user";
  $result4 = $mysqli->query($query4);

  if (!$result4) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
  while ($row4 = $result4->fetch_assoc()) {
    $switch_email = $row4['email'];
    if($switch_email == $email){
      $switch = 0;
    }
  }
  if($switch == 0){
    ?>
      <div role="alert" id="alert">同じメールアドレスが存在します。</div>
    <?php
  }
  else{
    $today = date("H,i,s"); 
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';  
    // 変数の初期化
    $new_code = '';

    // 繰り返し処理でランダムに文字列を生成(8文字)
    for ($i = 0; $i < 6; $i++) {
      $auth_code .= $chars[mt_rand(0, 61)];
    }

    // 生成された文字列を出力
    $query = "INSERT INTO contemporary_user(email,password,auth_code,insert_date) VALUES('$email','$password','$auth_code','$today')";
          
    $result = $mysqli->query($query);
      if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
        ?>
        <div role="alert" id="alert">エラーが発生しました</div>
        <?php
      }
      else{
        $to_title = $_POST['title'];
        $to_name = $_POST['name'];
        $to_email = $_POST['email'];
        $to_contents = $_POST['contents'];

        $host_email = "info@youcast.jp";

        mb_language("Japanese"); 
        mb_internal_encoding("UTF-8");
        $subject = "【YouCast】仮登録認証コード発行のお知らせ"; // 題名 
        $subject .= $to_title;
        $body .= $to_contents;
        $body .= "YouCastに仮登録いただき誠にありがとうございます。";
        $body .= "\n";
        $body .= "ご本人様確認のため、下記URLから「10分以内」に発行された認証コードを入力してアカウントの本登録を完了させて下さい。\n";
        $body .= "https://youcast.jp/main_register.php";
        $body .= "\n";
        $body .= "___________________________\n";
        $body .= "認証コード：";
        $body .= $auth_code;
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
        $to = $to_email;
        $header = "From: $host_email";
        
        mb_send_mail($to, $subject, $body, $header);
        header("Location: main_register.php");
  }
}
}

if(isset($_POST['signup_editer'])) {
  //$username = $mysqli->real_escape_string($_POST['username']);
  $email = $mysqli->real_escape_string($_POST['email']);
  $invitation_code = $mysqli->real_escape_string($_POST['invitation_code']);
  $password = $mysqli->real_escape_string($_POST['password']);
  $password = password_hash($password, PASSWORD_DEFAULT);

  require "function/return_sql.php";
  $query3 = "SELECT * FROM user";
  $result3 = $mysqli->query($query3);

  if (!$result3) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
  $switch = 1;
  while ($row3 = $result3->fetch_assoc()) {
    $switch_email = $row3['email'];
    if($switch_email == $email){
      $switch = 0;
    }
  }
  if($switch == 0){
    ?>
      <div role="alert" id="alert">同じメールアドレスが存在します。</div>
    <?php
  }

  if($invitation_code){
    $alert = 0;
    // ユーザー情報の取り出し
    $queryy = "SELECT * FROM invitation_code WHERE used_flag=0";
    $resultt = $mysqli->query($queryy);
    if (!$resultt) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    while ($roww = $resultt->fetch_assoc()) {
      $already_invitation_code = $roww['code'];
      if($invitation_code == $already_invitation_code){
        $alert = 1;
        $query = "UPDATE invitation_code SET used_flag = 1 WHERE code = $invitation_code";
        $result = $mysqli->query($query);
        if(!$result){
          print('クエリーが失敗しました。' . $mysqli->error);  
        }
      }
    }
    if($alert == 1){

    $query = "INSERT INTO user(email,password,prof_done,authority,register_year_time,register_month_time,register_day_time) VALUES('$email','$password',0,1,'$year_time','$month_time','$day_time')";
          
    $result = $mysqli->query($query);
      if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
        ?>
        <div role="alert" id="alert">エラー１１</div>
        <?php
      }
      else{
        $query = "SELECT * FROM user WHERE email='$email'";
        $result = $mysqli->query($query);

      // パスワード(暗号化済み）とユーザーIDの取り出し
      while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
      }
      $_SESSION['user'] = $id;

      $query = "UPDATE invitation_code SET user_id = '$id' WHERE code = '$invitation_code'";

      $result = $mysqli->query($query);
        if (!$result) {
          print('クエリーが失敗しました。' . $mysqli->error);
          $mysqli->close();
          exit();
        }

      $query = "UPDATE user SET authority = 1 WHERE id='$id'";
      $result = $mysqli->query($query);
        if (!$result) {
          print('クエリーが失敗しました。' . $mysqli->error);
          $mysqli->close();
          exit();
        }
        
      header("Location: prof_insert.php"); //ログインphpに遷移
      }
    }
    else{
        ?>
        <div role="alert" id="alert">認証コードが異なります。</div>
        <?php
    }
  }
  else{
    $query = "INSERT INTO user(email,password,prof_done,authority,register_year_time,register_month_time,register_day_time) VALUES('$email','$password',0,0,'$year_time','$month_time','$day_time')";
          
    $result = $mysqli->query($query);
      if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
      }
      else{
        $query = "SELECT * FROM user WHERE email='$email'";
        $result = $mysqli->query($query);
        while ($row = $result->fetch_assoc()) {
          $id = $row['id'];
        }
        $_SESSION['user'] = $id;
        header("Location: prof_insert.php");//ログインphpに遷移
      }
  }
}
?>

<!DOCTYPE HTML>
<html lang="ja">
<meta charset="utf-8" />

<head>
    <title>新規会員登録</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">

    <script type="text/javascript" src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <main>
      <div id="login_form">
          <p class="login_index">新規会員登録</p>
            <div style="margin:10px 0; overflow:hidden;">
                <section class="typeA" style="float:left; width:50%;">
                    <input id="TAB-A01" type="radio" name="TAB-A" value="1" checked="checked">
                    <label style="text-align:right;" id="header_switch_para1" class="tabLabel switch_blue" for="TAB-A01" onclick="setRequired(false);">編集を依頼したい</label>
                </section>          
                <section class="typeA" style="float:left; width:50%;">   
                    <input id="TAB-A02" type="radio" name="TAB-A" value="2">
                    <label id="header_switch_para2" class="tabLabel" for="TAB-A02" onclick="setRequired(true);">編集を受けたい</label>
                </section>
            </div>
          <div id="content_div1" class="content">
            <form method="post">
            <p>メールアドレス</p>
            <input type="email" placeholder="メールアドレス" name="email" required />
            <p>パスワード</p>
            <input type="password" placeholder="パスワード" id="password" name="password" min="5" required />
            <input style="width:auto !important; margin-right:8px !" type="checkbox" id="show_pass">表示
            <button type="submit" name="signup_client" class="btn">登録</button>
            </form>
          </div>
          <div id="content_div2" class="none">
            <form method="post">
              <p>メールアドレス</p>
              <input type="email" placeholder="メールアドレス" name="email" required />
              <p>パスワード</p>
              <input type="password" placeholder="パスワード" id="password" name="password" min="5" required />
              <input style="width:auto !important; margin-right:8px !" type="checkbox" id="show_pass">表示
              <p>招待コード</p>
              <!-- <input type="text" placeholder="招待コード" id="invitation_code" name="invitation_code"  /> クライアント募集開始時にこちらを適用 -->
              <input type="text" placeholder="招待コード" id="invitation_code" name="invitation_code" required />    
            <button type="submit" name="signup_editer" class="btn">登録</button>
            </form>
          </div>
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

  <footer>
  </footer>
</body>


</html>