<?php
	ob_start();
	session_start();
	date_default_timezone_set('Asia/Tokyo');
	$year_time = date("Y"); 
	//if( isset($_SESSION['user']) != "") {
		// ログイン済みの場合はリダイレクト
		//header("Location: home.php");
	//}
	// DBとの接続
	if(!isset($_SESSION['user']) != "") {
		header("Location: index.php");
	}
	include_once 'dbconnect.php';

	//セッションの代入
    $user_id = $_SESSION['user'];
    $get_project_id = $_POST['project_id'];

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
  $password = $row['password'];
  $prof_done = $row['prof_done'];
  $authority = $row['authority'];
}

	//動画編集者
	if(isset($_POST['update_project'])){
		//エスケープして前処理
		$project_name = $mysqli->real_escape_string($_POST['name']);
        $project_price = $mysqli->real_escape_string($_POST['price']);
        $project_id = $mysqli->real_escape_string($_POST['id']);
		
        $query2 = "UPDATE order_receive SET project_name='$project_name',price='$project_price' WHERE id = $project_id ";

		$result2 = $mysqli->query($query2);
			if(!$result2){
				print('クエリーが失敗しました。' . $mysqli->error);
			}
		else{
			header("Location: chat.php?project_id=$project_id");
		}

	}

?>

<!DOCTYPE HTML>
<html lang="ja">
<meta charset="utf-8" />

<head>
		<title>プロフィール更新</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/login.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php include('header.php'); ?>
    <?php
        $query = "SELECT * FROM order_receive WHERE id='$get_project_id'";
        $result = $mysqli->query($query);

        if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
        }

        // ユーザー情報の取り出し
        while ($row = $result->fetch_assoc()) {
            $project_id = $row['id'];
            $project_name = $row['project_name'];
            $project_editer_id = $row['editer_id'];
            $project_price = $row['price'];
            $project_done_flag = $row['done_flag']; 
        }

        if($user_id != $project_editer_id){
            header("Location: chat.php?project_id=$project_id");
        }
        else if($get_project_id == NULL){
            header("Location: home.php");
        }
    ?>
		<main>
			<div id="login_form">
			<form method="post">
				<p class="login_index">プロジェクト内容更新</p>
				<p>プロジェクト名</p>
				<input type="text" placeholder="プロジェクト名" name="name" value="<?php echo $project_name; ?>" required /> 
                <p>単価</p>
                <input type="number" placeholder="単価" name="price" value="<?php echo $project_price; ?>" required /> 
                <input type="hidden" name="id" value="<?php echo $project_id; ?>"> 
				<button type="submit" name="update_project" class="btn">更新</button>
				</form>
            </div>
            <?php
?>
		</main>

	<footer>
	</footer>
</body>


</html>