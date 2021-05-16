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
  <title>ポイント一覧 - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<?php include('header.php'); ?>

<form method="post">
		<input type = "number" name = "point" placeholder="ポイント数" required>
		<input type = "number" name = "price" placeholder="価格" required>
    <button type="submit" name="add_point_info" class="btn">ポイント追加</button>
</form>

<?php
	$query = "SELECT * FROM basic_point";
	$result = $mysqli->query($query);
	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
?>
<table>
	<tr>
		<th>id</th>
		<th>ポイント数</th>
		<th>価格</th>
		<th>button</th>
	</tr>

	
<?php
	while($row = $result->fetch_assoc()){
		$get_point_id = $row['id'];
		$get_point = $row['point'];
		$get_price = $row['price'];
		?>
		<tr>
			<td><?php echo $get_point_id?></td>
			<td><?php echo $get_point?></td>
			<td><?php echo $get_price?></td>
			<td>
				<form method ="post">
				<button type="submit" name="delete_point_info" class="btn" value = "<?php echo $get_point_id?>">削除</button>

				
				</form>
			</td>
		</tr>
<?php	}

	

?>

</body>
<footer>
</footer>
</html>