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

if($authority == 0){
    header("Location: home.php");
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
  <title>招待コード一覧 - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<?php include('header.php'); ?>
<main style="margin-top:5px !important">
<form method="post" action = "add_invitation.php">
    <input type="hidden" name="switch" value=1>
    <button type="submit" name="add_invitation" class="btn">招待コード追加</button>
</form>

<table>
    <tr>
        <!-- <th>ID</th> -->
        <th>招待コード</th>
        <th>発行者氏名</th>
        <th>被招待者氏名</th>
        <th>状態</th>
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
                $invitation_generate_user_id = $row['generate_user_id'];
                $invitation_used_flag = $row['used_flag'];

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

                if($invitation_generate_user_id != NULL){
                    if($invitation_generate_user_id == "33"){
                        $invitation_generate_username = "管理者";
                    }
                    else{
                        // ユーザーIDからユーザー名を取り出す
                        $queryy = "SELECT * FROM editer_prof WHERE user_id='$invitation_generate_user_id";
                        $resultt = $mysqli->query($queryy);

                        if (!$resultt) {
                        print('クエリーが失敗しました。' . $mysqli->error);
                        $mysqli->close();
                        exit();
                        }

                        // ユーザー情報の取り出し
                        while ($roww = $resultt->fetch_assoc()) {
                            $invitation_generate_username = $roww['username'];
                        }
                    }
                }
                else{
                    $invitation_generate_username = NULL;
                }
        ?>
        <td><?php echo $invitation_code; ?></td>
        <?php
        if($invitation_generate_user_id == "33"){
            ?>
                <td><?php echo $invitation_generate_username; ?></td>
            <?php
        }
        else{
            ?>
                <td><a href="profile.php?id=<?php echo $invitation_generate_user_id ?>"><?php echo $invitation_generate_username; ?></a></td>
            <?php
        }
        ?>
        <td><a href="profile.php?id=<?php echo $invitation_user_id ?>"><?php echo $invitation_username; ?></a></td>
        <?php
        if($invitation_used_flag == 0){
            ?>
            <td>未使用のコードです</td>
            <?php
        }else{
            ?>
            <td>使用済みのコードです</td>
            <?php
        }
        ?>
    </tr>
    <?php
    }
    ?>
</table>
</main>

</body>
<footer>
</footer>
</html>