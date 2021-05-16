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
  <title>TOP</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<form method="post" action = "add_invitation.php">
    <button type="submit" name="add_invitation" class="btn">招待コード追加</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>招待コード</th>
        <th>氏名</th>
    </tr>
    <tr>
        <?php
            // ユーザーIDからユーザー名を取り出す
            $query = "SELECT * FROM invitation_code";
            $result = $mysqli->query($query);

            if (!$result) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
            }

            // ユーザー情報の取り出し
            while ($row = $result->fetch_assoc()) {
                $invitation_id = $row['id'];
                $invitation_code = $row['code'];
                $invitation_user_id = $row['user_id'];

                if($invitation_user_id != NULL){
                    // ユーザーIDからユーザー名を取り出す
                    $queryy = "SELECT * FROM editer_prof WHERE user_id='$invitation_user_id'";
                    $resultt = $mysqli->query($queryy);

                    if (!$resultt) {
                    print('クエリーが失敗しました。' . $mysqli->error);
                    $mysqli->close();
                    exit();
                    }

                    // ユーザー情報の取り出し
                    while ($roww = $resultt->fetch_assoc()) {
                        $invitation_username = $roww['username'];
                    }
                }
                else{
                    $invitation_username = NULL;
                }
        ?>
        <td><?php echo $invitation_id; ?></td>
        <td><?php echo $invitation_code; ?></td>
        <td><a href="profile.php?id=<?php echo $invitation_user_id ?>"><?php echo $invitation_username; ?></a></td>
    </tr>
    <?php
    }
    ?>
</table>