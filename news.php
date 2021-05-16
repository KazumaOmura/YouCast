<?php
ob_start();
session_start();
if(isset($_SESSION['user']) != "") {
  // ログイン済みの場合はリダイレクト
  header("Location: home.php");
}
// DBとの接続
include_once 'dbconnect.php';
if(isset($_POST['signup'])) {
    $email = $mysqli->real_escape_string($_POST['email']);

    $query = "INSERT INTO user(email,prof_done,authority) VALUES('$email',0,0)";
            
    $result = $mysqli->query($query);
    if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
    }
    else{
        $queryy = "SELECT * FROM user WHERE email='$email'";
        $resultt = $mysqli->query($queryy);
        while ($roww = $resultt->fetch_assoc()) {
          $id = $roww['id'];
        }
        $_SESSION['user'] = $id;
        header("Location: prof_insert.php");
    }
}


            $news_id = $_GET['news_id'];

            // ユーザーIDからユーザー名を取り出す
            $query = "SELECT * FROM information WHERE id = '$news_id'";
            $result = $mysqli->query($query);

            if (!$result) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
            }

            // ユーザー情報の取り出し
            while ($row = $result->fetch_assoc()) {
                $info_id = $row['id'];
                $new_info_title = $row['title'];
                $new_info = $row['information'];
                $times = $row['times'];
            }
            if(!$info_id){
                header("Location: index.php");
            }
            
        ?>




<!DOCTYPE HTML>
<html lang="ja">
<meta charset="utf-8" />

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<link rel="stylesheet" href="css/style.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="js/main.js"></script>

<title>優秀な動画編集者を探すならYouCast</title>

</head>

<body>
<!-- <section class="typeA">
	<input id="TAB-A01" type="radio" name="TAB-A" value="1" checked="checked">
	<label class="tabLabel" for="TAB-A01">編集を依頼したい</label>
    <input id="TAB-A02" type="radio" name="TAB-A" value="2">
	<label class="tabLabel" for="TAB-A02">編集を受けたい</label>
    </section> -->
    <?php include('header_index.php'); ?>

    <main style="margin-top:5px !important; min-height:500px;">
    
    <p><span style="margin-top:0; text-align:left; font-weight:bold;"><?php echo $new_info_title; ?></span> - <?php echo $times; ?></p>
          <hr style="border-top: 3px solid #1E82DB !important; margin-top:-10px;">
    <p><?php echo $new_info; ?></p>

    
    </main>
    <?php include('footer.php'); ?>
</body>


</html>
