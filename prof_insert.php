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

if($prof_done != 0){
	header("Location: home.php");

}

	//クライアント(パスワード未設定の場合)
	if(isset($_POST['signup_password_null'])){
		//エスケープして前処理
		$new_password = $mysqli->real_escape_string($_POST['password']);
  		$new_password = password_hash($new_password, PASSWORD_DEFAULT);
		$username = $mysqli->real_escape_string($_POST['username']);
		$gender = $mysqli->real_escape_string($_POST['gender']);
		$birth_year = $mysqli->real_escape_string($_POST['birth_year']);
		$birth_month = $mysqli->real_escape_string($_POST['birth_month']);
		$birth_day = $mysqli->real_escape_string($_POST['birth_day']);
		$youtube_url = $mysqli->real_escape_string($_POST['youtube_url']);
		$prof_para = $mysqli->real_escape_string($_POST['prof_para']);

		//ファイルが送信されていない場合はエラー処理
		if(!isset($_FILES['image'])){
			echo 'ファイルが送信されていません。';
			exit;
		}
		
		$images_name = $_FILES['image']['name'];
		
		//ファイル名を使用して保存先ディレクトリを指定 basename()でファイルシステムトラバーサル攻撃を防ぐ
		$save = 'profile_img/client/' . basename($_FILES['image']['name']);
		
		//move_uploaded_fileで、一時ファイルを保存先ディレクトリに移動させる
		move_uploaded_file($_FILES['image']['tmp_name'], $save);

		$queryy = "UPDATE user SET password = '$new_password' WHERE id='$user_id'";
      	$resultt = $mysqli->query($queryy);
			if(!$resultt){
				print('クエリーが失敗しました。' . $mysqli->error);
			}
		$query = "INSERT INTO client_prof(username,gender,birth_year,birth_month,birth_day,youtube_url,user_id,image_name,prof_para) VALUES('$username','$gender','$birth_year','$birth_month','$birth_day','$youtube_url','$user_id','$images_name','$prof_para')";
		$result = $mysqli->query($query);
			if(!$result){
				print('クエリーが失敗しました。' . $mysqli->error);
			}
		else{
			$query = "UPDATE user SET prof_done = 1 WHERE id = $user_id ";
			if(!$mysqli->query($query)) {
				print('クエリーが失敗しました。' . $mysqli->error);
			}
			else{
				header("Location: home.php");
			}
		}
	}


	//クライアント(パスワード未設定済みの場合)
	if(isset($_POST['signup'])){
		//エスケープして前処理
		$username = $mysqli->real_escape_string($_POST['username']);
  		$gender = $mysqli->real_escape_string($_POST['gender']);
		$birth_year = $mysqli->real_escape_string($_POST['birth_year']);
  		$birth_month = $mysqli->real_escape_string($_POST['birth_month']);
		$birth_day = $mysqli->real_escape_string($_POST['birth_day']);
		$youtube_url = $mysqli->real_escape_string($_POST['youtube_url']);
		$prof_para = $mysqli->real_escape_string($_POST['prof_para']);

		//ファイルが送信されていない場合はエラー処理
		if(!isset($_FILES['image'])){
			echo 'ファイルが送信されていません。';
			exit;
		}
		
		$images_name = $_FILES['image']['name'];
		
		//ファイル名を使用して保存先ディレクトリを指定 basename()でファイルシステムトラバーサル攻撃を防ぐ
		$save = 'profile_img/client/' . basename($_FILES['image']['name']);
		
		//move_uploaded_fileで、一時ファイルを保存先ディレクトリに移動させる
		move_uploaded_file($_FILES['image']['tmp_name'], $save);
		
		$query = "INSERT INTO client_prof(username,gender,birth_year,birth_month,birth_day,youtube_url,user_id,image_name,prof_para) VALUES('$username','$gender','$birth_year','$birth_month','$birth_day','$youtube_url','$user_id','$images_name','$prof_para')";
		$result = $mysqli->query($query);
			if(!$result){
				print('クエリーが失敗しました。' . $mysqli->error);
			}
		else{
			$query = "UPDATE user SET prof_done = 1 WHERE id = $user_id ";
			if(!$mysqli->query($query)) {
				print('クエリーが失敗しました。' . $mysqli->error);
			}
			else{
				header("Location: home.php");
			}
		}
	}

	//動画編集者
	if(isset($_POST['signup_editer'])){
		//エスケープして前処理
		$username = $mysqli->real_escape_string($_POST['username']);
		$gender = $mysqli->real_escape_string($_POST['gender']);
		$birth_year = $mysqli->real_escape_string($_POST['birth_year']);
		$birth_month = $mysqli->real_escape_string($_POST['birth_month']);
		$birth_day = $mysqli->real_escape_string($_POST['birth_day']);
		$edit_career = $mysqli->real_escape_string($_POST['edit_career']);
		$edit_software = $mysqli->real_escape_string($_POST['edit_software']);
		$unit_price = $mysqli->real_escape_string($_POST['unit_price']);
		$portfolio_title = $mysqli->real_escape_string($_POST['portfolio_title']);
		$portfolio_url = $mysqli->real_escape_string($_POST['portfolio_url']);
		$prof_para = $mysqli->real_escape_string($_POST['prof_para']);

		//ファイルが送信されていない場合はエラー処理
		if(!isset($_FILES['image'])){
			echo 'ファイルが送信されていません。';
			exit;
		}
		
		$images_name = $_FILES['image']['name'];
		
		//ファイル名を使用して保存先ディレクトリを指定 basename()でファイルシステムトラバーサル攻撃を防ぐ
		$save = 'profile_img/editer/' . basename($_FILES['image']['name']);
		
		//move_uploaded_fileで、一時ファイルを保存先ディレクトリに移動させる
		move_uploaded_file($_FILES['image']['tmp_name'], $save);

		$query = "INSERT INTO editer_prof(username,gender,birth_year,birth_month,birth_day,edit_career,edit_software,unit_price,user_id,image_name,prof_para,portfolio_title,portfolio_url) VALUES('$username','$gender','$birth_year','$birth_month','$birth_day','$edit_career','$edit_software','$unit_price','$user_id','$images_name','$prof_para','$portfolio_title','$portfolio_url')";
		$result = $mysqli->query($query);
			if(!$result){
				print('クエリーが失敗しました。' . $mysqli->error);
			}
		else{
			$query = "UPDATE user SET prof_done = 1 WHERE id = $user_id ";
			if(!$mysqli->query($query)) {
				print('クエリーが失敗しました。' . $mysqli->error);
			}
			else{
				header("Location: home.php");
			}
		}

	}

?>

<!DOCTYPE HTML>
<html lang="ja">
<meta charset="utf-8" />

<head>
		<title>新規登録</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/login.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php include('header.php'); ?>
<?php
if($authority == 0){
?>
<?php
	if(!$password){
		?>
		<main>
			<div id="login_form">
			<form method="post" enctype="multipart/form-data">
				<p class="login_index">プロフィール入力</p>
				<p>新規パスワード</p>
				<input type="password" placeholder="新規パスワード" name="password" required />
				<p>アップロード画像</p>
        		<input type="file" name="image" accept=".png, .jpg, .jpeg" required />
				<p>氏名</p>
				<input type="text" placeholder="氏名" name="username" required /> 
				<p>性別</p>
				<div style="width:33%; float:left;">
					男性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="men" checked/>
				</div>
				<div style="width:33%; float:left;">
					女性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="women" checked/>
				</div>
				<div style="width:33%; float:left;">
					その他<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="other" checked/>
				</div>
				<p>生年月日</p>
				<select style="display:block; width:33%; float:left;" name = "birth_year" required>
				<option value = "">年</option>
				<?php 
					for($i = $year_time ;$i > 1950 ;$i--){
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
						<?php
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_month" required>
				<option value = "">月</option>
				<?php 
					for($i = 1 ;$i <= 12 ;$i++){
						echo "<option value = $i>$i</option>";
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_day" required>
				<option value = "">日</option>
				<?php 
					for($i = 1 ;$i <= 31 ;$i++){
						echo "<option value = $i>$i</option>";
					}
				?>
				</select>
				<p>YouTubeチャンネル</p>
				<input type="text" placeholder="YouTubeチャンネルURL" name="youtube_url" required /> 
				<p>自己紹介</p>
				<textarea placeholder="YouTuberをしております「○○○○」と申します。" name="prof_para" required><?php echo $user_prof_para; ?></textarea> 
				<button type="submit" name="signup_password_null" class="btn">登録</button>
				</form>
			</div>
		</main>
		<?php
	}
	else{
		?>
		<main>
			<div id="login_form">
				<form method="post" enctype="multipart/form-data">
				<p class="login_index">プロフィール入力</p>
				<p>アップロード画像</p>
        		<input type="file" name="image" accept=".png, .jpg, .jpeg" required />
				<p>氏名</p>
				<input type="text" placeholder="氏名" name="username" required /> 
				<p>性別</p>
				<div style="width:33%; float:left;">
					男性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="men" checked/>
				</div>
				<div style="width:33%; float:left;">
					女性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="women" checked/>
				</div>
				<div style="width:33%; float:left;">
					その他<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="other" checked/>
				</div>
				<p>生年月日</p>
				<select style="display:block; width:33%; float:left;" name = "birth_year" required>
				<option value = "">年</option>
				<?php 
					for($i = $year_time ;$i > 1950 ;$i--){
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
						<?php
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_month" required>
				<option value = "">月</option>
				<?php 
					for($i = 1 ;$i <= 12 ;$i++){
						echo "<option value = $i>$i</option>";
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_day" required>
				<option value = "">日</option>
				<?php 
					for($i = 1 ;$i <= 31 ;$i++){
						echo "<option value = $i>$i</option>";
					}
				?>
				</select>
				<p>YouTubeチャンネル</p>
				<input type="text" placeholder="YouTubeチャンネルURL" name="youtube_url" required /> 
				<p>自己紹介</p>
				<textarea placeholder="YouTuberをしております「○○○○」と申します。" name="prof_para" required><?php echo $user_prof_para; ?></textarea> 
				<button type="submit" name="signup" class="btn">登録</button>
				</form>
			</div>
		</main>
	<?php
	}
	?>
<?php
}
?>
	<?php
	if($authority == 1 || $authority == 2){
		?>
		<main>
			<div id="login_form">
				<form method="post" enctype="multipart/form-data">
				<p class="login_index">プロフィール入力</p>
				<p>アップロード画像</p>
        		<input type="file" name="image" accept=".png, .jpg, .jpeg" required />
				<p>氏名</p>
				<input type="text" placeholder="氏名" name="username" required /> 
				<p>性別</p>
				<div style="width:33%; float:left;">
					男性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="men" checked/>
				</div>
				<div style="width:33%; float:left;">
					女性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="women" checked/>
				</div>
				<div style="width:33%; float:left;">
					その他<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="other" checked/>
				</div>
				<p>生年月日</p>
				<select style="display:block; width:33%; float:left;" name = "birth_year" required>
				<option value = "">年</option>
				<?php 
					for($i = $year_time ;$i > 1950 ;$i--){
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
						<?php
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_month" required>
				<option value = "">月</option>
				<?php 
					for($i = 1 ;$i <= 12 ;$i++){
						echo "<option value = $i>$i</option>";
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_day" required>
				<option value = "">日</option>
				<?php 
					for($i = 1 ;$i <= 31 ;$i++){
						echo "<option value = $i>$i</option>";
					}
				?>
				</select>
				<p>動画1本あたりの単価</p>
				<input type="number" placeholder="動画1本あたりの単価" name="unit_price" min="500" max="1000000" required /> 
				<p>編集歴</p>
				<select style="display:block" name = "edit_career" required>
				<option value="1" checked="checked">未経験
				<option value="2">1年未満
				<option value="3">1~2年
				<option value="4">2~3年
				<option value="5">3年以上
				</select>
				<p>対応できる編集ソフト</p>
				<?php
					$query = "SELECT * FROM edit_software";
					$result = $mysqli->query($query);

					if (!$result) {
					print('クエリーが失敗しました。' . $mysqli->error);
					$mysqli->close();
					exit();
					}

					while ($row = $result->fetch_assoc()) {
						$software_id = $row['id'];
						$software_edit_software_name = $row['edit_software_name'];
						?>
						<div style="width:100%;">
						<?php
						$array_user_edit_software = preg_split("/,/",$user_edit_software);
						$count_array_user_edit_software = count($array_user_edit_software);
						for ($i = 0; $i < $count_array_user_edit_software; $i++){
							if($software_id  == $array_user_edit_software[$i]){
								?>
								<input style="width:auto !important; margin-right:8px !important;" type="checkbox" name="edit_software[]" value="<?php echo $software_id ; ?>" checked><?php echo $software_edit_software_name; ?>
								<?php
							}
							else{
								?>
								<input style="width:auto !important; margin-right:8px !important;" type="checkbox" name="edit_software[]" value="<?php echo $software_id ; ?>"><?php echo $software_edit_software_name; ?>
								<?php
							}
						}
						?>
						</div>
						<?php
					  }
				?>
				<p>ポートフォリオ</p>
				<input type="text" placeholder="表示名" name="portfolio_title" value="<?php echo $user_portfolio_title; ?>" /> 
				<input type="text" placeholder="https://youtube.com" name="portfolio_url" value="<?php echo $user_portfolio_url; ?>"> 
				<p>自己紹介</p>
				<textarea placeholder="映像制作をしております「○○○○」と申します。" name="prof_para" required><?php echo $user_prof_para; ?></textarea> 
				<button type="submit" name="signup_editer" class="btn">登録</button>
				</form>
			</div>
		</main>
	<?php
	}	
?>

	<footer>
	</footer>
</body>


</html>