<?php
ob_start();
session_start();
if(!isset($_SESSION['user']) != "") {
//   header("Location: index.php");
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
  $user_authority = $row['authority'];
}

?>

<!DOCTYPE HTML>
<br lang="ja">

<head>
  <meta charset="UTF-8">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $editer_user_name?>のPROFILE</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/chat.css">
</head>

<?php include('header.php'); ?>
<main>
  <?php
  if($user_authority == 0){
  ?>
<table>
        <tr>
            <th>ポイント数</th>
            <th>日時</th>
        </tr>
        <?php
        $query = "SELECT * FROM point WHERE client_id='$user_id'";
        $result = $mysqli->query($query);

        if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
        }

        // ユーザー情報の取り出し
        while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $point_num = $row['point_num'];
        $add_or_delete = $row['add_or_delete'];
        $insert_date = $row['insert_date'];
        $insert_time = $row['insert_time'];
        $point_num_jpy = number_format($point_num);
        ?>
        <tr>
            <?php
            if($add_or_delete == 0){
                ?>
                <td>-<?php echo $point_num_jpy; ?>pt</td>
                <?php
            }
            else if($add_or_delete == 1){
                ?>
                <td>+<?php echo $point_num_jpy; ?>pt</td>
                <?php
            }
            ?>
            <td><?php echo $insert_date; ?> / <?php echo $insert_time; ?></td>
        </tr>
        <?php
        }
        ?>
    </table>
  <?php
  }
  else if($user_authority == 1){
    ?>
<table>
        <tr>
          <th>プロジェクト名</th>
          <th>ポイント数</th>
        </tr>
        <?php
        $query = "SELECT * FROM point WHERE editer_id='$user_id'";
        $result = $mysqli->query($query);

        if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
        }

        // ユーザー情報の取り出し
        while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $project_id = $row['project_id'];
        $point_num = $row['point_num'];
        $add_or_delete = $row['add_or_delete'];
        $insert_date = $row['insert_date'];
        $insert_time = $row['insert_time'];
        $point_num_jpy = number_format($point_num);
        ?>
        <tr>
          <?php
          $query2 = "SELECT * FROM order_receive WHERE id='$project_id'";
          $result2 = $mysqli->query($query2);
  
          if (!$result2) {
          print('クエリーが失敗しました。' . $mysqli->error);
          $mysqli->close();
          exit();
          }
  
          // ユーザー情報の取り出し
          while ($row2 = $result2->fetch_assoc()) {
            $project_name = $row2['project_name'];
            $done_flag = $row2['done_flag'];
          }
          if($done_flag == 6){
            ?>
            <td><a href="chat.php?project_id=<?php echo $project_id; ?>"><?php echo $project_name; ?></a></td>
            <?php
            if($add_or_delete == 0){
                ?>
                <td>+<?php echo $point_num_jpy; ?>pt</td>
                <?php
            }
            else if($add_or_delete == 1){
                ?>
                <td>-<?php echo $point_num_jpy; ?>pt</td>
                <?php
            }
            ?>
            <?php
          }
          ?>
        </tr>
        <?php
        }
        ?>
    </table>
    <?php
  }
  ?>

</main>

</body>
<footer>
</footer>
</html>