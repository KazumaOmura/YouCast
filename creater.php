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
  $user_prof_done = $row['prof_done'];
  $user_authority = $row['authority'];
}

if($user_prof_done == 0){
  header("Location: prof_insert.php");
}


if($user_authority == 0){
  $query = "SELECT * FROM client_prof WHERE user_id='$user_id'";
  $result = $mysqli->query($query);
}
else if($user_authority == 1) {
  $query = "SELECT * FROM editer_prof WHERE user_id='$user_id'";
  $result = $mysqli->query($query);
}
// ユーザーIDからユーザー名を取り出す

if (!$result) {
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}

// ユーザー情報の取り出し
while ($row = $result->fetch_assoc()) {
  $user_username = $row['username'];
  $user_gender = $row['gender'];
  $user_birth_year = $row['birth_year'];
  $user_birth_month = $row['birth_month'];
  $user_birth_day = $row['birth_day'];
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
  <title>TOP</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<?php
  // ユーザーIDからユーザー名を取り出す
  $query = "SELECT * FROM user WHERE authority = 0" ;
  $result = $mysqli->query($query);

  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
  }
?>
  <h1>編集者リスト</h1>
  <?php
  // ユーザー情報の取り出し
  while ($row = $result->fetch_assoc()) {
    $editer_id = $row['id'];
    $editer_authority = $row['authority'];

    // ユーザーIDからユーザー名を取り出す
    $queryy = "SELECT * FROM editer_prof WHERE user_id = '$editer_id'";
    $resultt = $mysqli->query($queryy);

    if (!$resultt) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    while ($roww = $resultt->fetch_assoc()) {
      $editer_prof_id = $roww['id'];
      $editer_username = $roww['username'];
    }
    ?>
  <p><a href = "profile.php?id=<?php echo $editer_id?>"><?php echo $editer_username?></a></p>
  <?php
  }
  ?>
</body>
<footer>
</footer>
</html>