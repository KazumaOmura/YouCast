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

// 削除ボタンがクリックされたときに下記を実行
if(isset($_POST['delete_point_info'])) {

	$ds = $mysqli->real_escape_string($_POST['delete_point_info']);

	// クエリの実行
	$query = "DELETE FROM basic_point WHERE id='$ds'";
	$result = $mysqli->query($query);
	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
	header("Location: point_basic.php");
}

// 削除ボタンがクリックされたときに下記を実行
if(isset($_POST['add_point_info'])) {

	$point = $mysqli->real_escape_string($_POST['point']);
	$price = $mysqli->real_escape_string($_POST['price']);

	// クエリの実行
	$query = "INSERT INTO basic_point(point,price) VALUE ('$point','$price')";
	$result = $mysqli->query($query);
	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
	header("Location: point_basic.php");
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
  <title>ユーザ一覧 - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<?php include('header.php'); ?>

<?php
	$query = "SELECT * FROM user WHERE NOT authority=2";
	$result = $mysqli->query($query);
	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
?>
<table>
	<tr>
		<th>ID</th>
		<th>ユーザ名</th>
		<th>プロフィール状態</th>
		<th>種類</th>
        <th>登録日</th>
	</tr>

	
<?php
	while($row = $result->fetch_assoc()){
        $user_id = $row['id'];
        $user_email = $row['email'];
        $user_prof_done = $row['prof_done'];
        $user_authority = $row['authority'];
        $user_register_year_time = $row['register_year_time'];
        $user_register_month_time = $row['register_month_time'];
        $user_register_day_time = $row['register_day_time'];

        if($user_authority == 0){
            $query2 = "SELECT * FROM client_prof WHERE user_id = $user_id";
        }
        else if($user_authority == 1){
            $query2 = "SELECT * FROM editer_prof WHERE user_id = $user_id";
        }
        $result2 = $mysqli->query($query2);
        if (!$result2) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
        }
        while($row2 = $result2->fetch_assoc()){
            $user_username = $row2['username'];
        }
		?>
		<tr>
			<td><?php echo $user_id?></td>
			<td><a href="https://youcast.jp/profile.php?id=<?php echo $user_id?>"><?php echo $user_username?></a></td>
            <?php
            if($user_prof_done == 0){
                ?>
                <td>未記入</td>
                <?php
            }
            else{
                ?>
                <td>記入済</td>
                <?php
            }
            if($user_authority == 0){
                ?>
                <td>クライアント</td>
                <?php
            }
            else{
                ?>
                <td>編集者</td>
                <?php
            }
            ?>
			<td><?php echo $user_register_year_time?> / <?php echo $user_register_month_time?> / <?php echo $user_register_day_time?></td>
		</tr>
<?php	}

	

?>

</body>
<footer>
</footer>
</html>