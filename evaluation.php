<?php
ob_start();
session_start();
if(!isset($_SESSION['user']) != "") {
  header("Location: index.php");
}
include_once 'dbconnect.php';

$get_project_id = $_POST['project_id'];
$get_direction = $_POST['direction'];

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

$query4 = "SELECT * FROM order_receive WHERE id='$get_project_id'";
$result4 = $mysqli->query($query4);

if (!$result4) {
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}

// ユーザー情報の取り出し
while ($row4 = $result4->fetch_assoc()) {
    $project_id = $row4['id'];
    $project_project_name = $row4['project_name'];
    $project_client_id = $row4['client_id'];
    $project_editer_id = $row4['editer_id'];
    $project_price = $row4['price'];
    $project_price_jpy = number_format($project_price);
    $project_done_flag = $row4['done_flag'];
}

if(isset($_POST['insert_evaluation'])){
    //エスケープして前処理
    $level = $mysqli->real_escape_string($_POST['evaluation_level']);
    $para = $mysqli->real_escape_string($_POST['evaluation_para']);
    $get_project_id = $mysqli->real_escape_string($_POST['get_project_id']);
    $get_direction = $mysqli->real_escape_string($_POST['get_direction']);
    $project_client_id = $mysqli->real_escape_string($_POST['client_id']);
    $project_editer_id = $mysqli->real_escape_string($_POST['editer_id']);

    if($get_direction == 0){
        $query = "INSERT INTO evaluation(star,para,project_id,client_id,editer_id,direction) VALUES('$level','$para','$get_project_id','$project_client_id','$project_editer_id',0)";
    }
    if($get_direction == 1){
        $query = "INSERT INTO evaluation(star,para,project_id,client_id,editer_id,direction) VALUES('$level','$para','$get_project_id','$project_client_id','$project_editer_id',1)";
    }
    $result = $mysqli->query($query);
    if(!$result){
        print('クエリーが失敗しました。' . $mysqli->error);
        exit;
    }
    else{
        if($get_direction == 0){
            $query = "UPDATE order_receive SET done_flag = 5 WHERE id = $get_project_id";
        }
        else if($get_direction == 1){
            $query = "UPDATE order_receive SET done_flag = 6 WHERE id = $get_project_id";
        }
        $result = $mysqli->query($query);
        if(!$result){
            print('クエリーが失敗しました。' . $mysqli->error);
        }
        else{
            header("Location: chat.php?project_id=$get_project_id");
        }
    }
}

?>

<!DOCTYPE HTML>
<html lang="ja">
<meta charset="utf-8" />

<head>
		<title>評価入力</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/login.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
		<main>
			<div id="login_form">
			<form method="post">
				<p class="login_index">評価入力</p>
                <p>評価</p>
				<select style="display:block" name = "evaluation_level" required>
				<option value="1" checked="checked">★
				<option value="2">★★
				<option value="3">★★★
				<option value="4">★★★★
				<option value="5">★★★★★
				</select>
				<p>評価のコメントを記入しましょう</p>
				<textarea placeholder="この度はありがとうございました。" name="evaluation_para" required></textarea>
                <input type = "hidden" name = "get_project_id" value = "<?php echo $get_project_id?>"> 
                <input type = "hidden" name = "get_direction" value = "<?php echo $get_direction?>"> 
                <input type = "hidden" name = "client_id" value = "<?php echo $project_client_id?>"> 
                <input type = "hidden" name = "editer_id" value = "<?php echo $project_editer_id?>"> 
				<button type="submit" name="insert_evaluation" class="btn">評価を投稿する</button>
				</form>
			</div>
		</main>
	<footer>
	</footer>
</body>


</html>