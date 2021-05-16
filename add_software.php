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
if(isset($_POST['delete_software'])) {

	$ds = $mysqli->real_escape_string($_POST['delete_software']);

	// クエリの実行
	$query = "DELETE FROM edit_software WHERE id='$ds'";
	$result = $mysqli->query($query);
	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
	header("Location: add_software.php");
}

// 削除ボタンがクリックされたときに下記を実行
if(isset($_POST['add_software'])) {

	$as = $mysqli->real_escape_string($_POST['add_soft_name']);

	// クエリの実行
	$query = "INSERT INTO edit_software(edit_software_name) VALUE ('$as')";
	$result = $mysqli->query($query);
	if (!$result) {
		print('クエリーが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}
	header("Location: add_software.php");
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
  <title>ソフトウェア一覧 - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<?php include('header.php'); ?>

<form method="post">
		<input type = "text" name = "add_soft_name" placeholder="ここに入力" required>
    <button type="submit" name="add_software" class="btn">編集ソフト追加</button>
</form>

<?php
	$query = "SELECT * FROM edit_software";
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
		<th>ソフト名</th>
		<th>button</th>
	</tr>

	
<?php
	while($row = $result->fetch_assoc()){
		$software_id = $row['id'];
		$software_name = $row['edit_software_name'];
		?>
		<tr>
			<td><?php echo $software_id?></td>
			<td><?php echo $software_name?></td>
			<td>
				<form method ="post">
				<button type="submit" name="delete_software" class="btn" value = "<?php echo $software_id?>">削除</button>

				
				</form>
			</td>
		</tr>
<?php	}

	

?>

</body>
<footer>
</footer>
</html>