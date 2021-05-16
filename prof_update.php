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

	//クライアント(パスワード未設定済みの場合)
	if(isset($_POST['update_client'])){
		//エスケープして前処理
		$username = $mysqli->real_escape_string($_POST['username']);
  		$gender = $mysqli->real_escape_string($_POST['gender']);
		$birth_year = $mysqli->real_escape_string($_POST['birth_year']);
  		$birth_month = $mysqli->real_escape_string($_POST['birth_month']);
        $birth_day = $mysqli->real_escape_string($_POST['birth_day']);
        $prof_para = $mysqli->real_escape_string($_POST['prof_para']);
        $youtube_url = $mysqli->real_escape_string($_POST['youtube_url']);

		//ファイルが送信されていない場合はエラー処理
		// if(!isset($_FILES['image'])){
		// 	echo 'ファイルが送信されていません。';
		// 	exit;
		// }
		
		$images_name = $_FILES['image']['name'];
		
		//ファイル名を使用して保存先ディレクトリを指定 basename()でファイルシステムトラバーサル攻撃を防ぐ
		$save = 'profile_img/client/' . basename($_FILES['image']['name']);
		
		//move_uploaded_fileで、一時ファイルを保存先ディレクトリに移動させる
        move_uploaded_file($_FILES['image']['tmp_name'], $save);
        
        if(!$images_name){
            $query = "UPDATE client_prof SET username='$username',gender='$gender',birth_year='$birth_year',birth_month='$birth_month',birth_day='$birth_day',youtube_url='$youtube_url',prof_para='$prof_para' WHERE user_id = $user_id ";
        }
        else{
            $query = "UPDATE client_prof SET username='$username',gender='$gender',birth_year='$birth_year',birth_month='$birth_month',birth_day='$birth_day',youtube_url='$youtube_url',prof_para='$prof_para',image_name='$images_name' WHERE user_id = $user_id ";
        }
		$result = $mysqli->query($query);
			if(!$result){
				print('クエリーが失敗しました。' . $mysqli->error);
			}
		else{
			header("Location: mypage.php");
		}
	}

	//動画編集者
	if(isset($_POST['update_editer'])){
		//エスケープして前処理
		$username = $mysqli->real_escape_string($_POST['username']);
		$gender = $mysqli->real_escape_string($_POST['gender']);
		$birth_year = $mysqli->real_escape_string($_POST['birth_year']);
		$birth_month = $mysqli->real_escape_string($_POST['birth_month']);
		$birth_day = $mysqli->real_escape_string($_POST['birth_day']);
		$edit_career = $mysqli->real_escape_string($_POST['edit_career']);
		$unit_price = $mysqli->real_escape_string($_POST['unit_price']);
		$prof_para = $mysqli->real_escape_string($_POST['prof_para']);
		$portfolio_title = $mysqli->real_escape_string($_POST['portfolio_title']);
		$portfolio_url = $mysqli->real_escape_string($_POST['portfolio_url']);
		$edit_software = ($_POST['edit_software']);
		
		$images_name = $_FILES['image']['name'];
		
		//ファイル名を使用して保存先ディレクトリを指定 basename()でファイルシステムトラバーサル攻撃を防ぐ
		$save = 'profile_img/editer/' . basename($_FILES['image']['name']);
		
		//move_uploaded_fileで、一時ファイルを保存先ディレクトリに移動させる
		move_uploaded_file($_FILES['image']['tmp_name'], $save);

		for ($i = 0; $i < count($edit_software); $i++){

			// 変数代入 //
			$insert_edit_software .= $edit_software[$i];
			$insert_edit_software .= ",";
		
		}
		$insert_edit_software = substr($insert_edit_software, 0, -1); //最後の区切り文字を削除

		if(!$images_name){
            $query = "UPDATE editer_prof SET username='$username',gender='$gender',birth_year='$birth_year',birth_month='$birth_month',birth_day='$birth_day',edit_career='$edit_career',edit_software='$insert_edit_software',unit_price='$unit_price',prof_para='$prof_para' ,portfolio_title='$portfolio_title',portfolio_url='$portfolio_url' WHERE user_id = $user_id ";
        }
        else{
            $query = "UPDATE editer_prof SET username='$username',gender='$gender',birth_year='$birth_year',birth_month='$birth_month',birth_day='$birth_day',edit_career='$edit_career',edit_software='$insert_edit_software',unit_price='$unit_price',prof_para='$prof_para' ,image_name='$images_name',portfolio_title='$portfolio_title',portfolio_url='$portfolio_url' WHERE user_id = $user_id ";
        }
		$result = $mysqli->query($query);
			if(!$result){
				print('クエリーが失敗しました。' . $mysqli->error);
			}
		else{
			header("Location: mypage.php");
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
    if($authority == 0){
        $query = "SELECT * FROM client_prof WHERE user_id='$user_id'";
        $result = $mysqli->query($query);

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
        $user_youtube_url = $row['youtube_url'];
        $user_prof_para = $row['prof_para'];
        $user_image_name = $row['image_name'];
        }
    ?>
		<main>
			<div id="login_form">
			<form method="post" enctype="multipart/form-data">
				<p class="login_index">プロフィール更新</p>
                <p>プロフィール画像</p>
                <img style="width:20%;" src="profile_img/client/<?php echo $user_image_name ;?>">
        		<input type="file" name="image" accept=".png, .jpg, .jpeg" />
				<p>氏名</p>
				<input type="text" placeholder="氏名" name="username" value="<?php echo $user_username; ?>" required /> 
                <p>性別</p>
                <?php
                if($user_gender == "men"){
                    $gender_switch1 = "checked";
                }
                else if($user_gender == "women"){
                    $gender_switch2 = "checked";
                }
                else{
                    $gender_switch3 = "checked";
                }
                ?>
				<div style="width:33%; float:left;">
					男性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="men" <?php echo $gender_switch1; ?>/>
				</div>
				<div style="width:33%; float:left;">
					女性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="women" <?php echo $gender_switch2; ?>/>
				</div>
				<div style="width:33%; float:left;">
					その他<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="other" <?php echo $gender_switch3; ?>/>
				</div>
				<p>生年月日</p>
				<select style="display:block; width:33%; float:left;" name = "birth_year" required>
				<option value = "">年</option>
				<?php 
					for($i = 1950 ;$i < $year_time ;$i++){
                        if($user_birth_year == $i){
                            ?>
                            <option value = "<?php echo $i ;?>" selected><?php echo $i ;?></option>
                            <?php
                        }
                        else{
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
                        <?php
                        }
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_month" required>
				<option value = "">月</option>
				<?php 
					for($i = 1 ;$i <= 12 ;$i++){
						if($user_birth_month == $i){
                            ?>
                            <option value = "<?php echo $i ;?>" selected><?php echo $i ;?></option>
                            <?php
                        }
                        else{
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
                        <?php
                        }
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_day" required>
				<option value = "">日</option>
				<?php 
					for($i = 1 ;$i <= 31 ;$i++){
						if($user_birth_day == $i){
                            ?>
                            <option value = "<?php echo $i ;?>" selected><?php echo $i ;?></option>
                            <?php
                        }
                        else{
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
                        <?php
                        }
					}
				?>
				</select>
				<p>YouTubeチャンネル</p>
                <input type="text" placeholder="YouTubeチャンネルURL" name="youtube_url" value="<?php echo $user_youtube_url; ?>" required /> 
                <p>自己紹介</p>
				<textarea placeholder="映像制作をしております「○○○○」と申します。" name="prof_para" required><?php echo $user_prof_para; ?></textarea> 
				<button type="submit" name="update_client" class="btn">更新</button>
				</form>
			</div>
		</main>
	<?php
    }

	else{
        $query = "SELECT * FROM editer_prof WHERE user_id='$user_id'";
        $result = $mysqli->query($query);

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
		$user_image_name = $row['image_name'];
		$user_edit_career = $row['edit_career'];
		$user_edit_software = $row['edit_software'];
		$user_unit_price = $row['unit_price'];
		$user_prof_para = $row['prof_para'];
		$user_portfolio_title = $row['portfolio_title'];
		$user_portfolio_url = $row['portfolio_url'];
        }
		?>
		<main>
			<div id="login_form">
				<form method="post" enctype="multipart/form-data">
				<p class="login_index">プロフィール更新</p>
                <p>プロフィール画像</p>
                <img style="width:20%;" src="profile_img/editer/<?php echo $user_image_name ;?>">
        		<input type="file" name="image" accept=".png, .jpg, .jpeg" />
				<p>氏名</p>
				<input type="text" placeholder="氏名" name="username" value="<?php echo $user_username; ?>" required /> 
				<p>性別</p>
				<?php
                if($user_gender == "men"){
                    $gender_switch1 = "checked";
                }
                else if($user_gender == "women"){
                    $gender_switch2 = "checked";
                }
                else{
                    $gender_switch3 = "checked";
                }
                ?>
				<div style="width:33%; float:left;">
					男性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="men" <?php echo $gender_switch1; ?>/>
				</div>
				<div style="width:33%; float:left;">
					女性<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="women" <?php echo $gender_switch2; ?>/>
				</div>
				<div style="width:33%; float:left;">
					その他<input style="width:auto !important; margin-left:8px !important;" type="radio" name="gender" value="other" <?php echo $gender_switch3; ?>/>
				</div>
				<p>生年月日</p>
				<select style="display:block; width:33%; float:left;" name = "birth_year" required>
				<option value = "">年</option>
				<?php 
					for($i = 1950 ;$i < $year_time ;$i++){
						if($user_birth_year == $i){
                            ?>
                            <option value = "<?php echo $i ;?>" selected><?php echo $i ;?></option>
                            <?php
                        }
                        else{
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
                        <?php
                        }
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_month" required>
				<option value = "">月</option>
				<?php 
					for($i = 1 ;$i <= 12 ;$i++){
						if($user_birth_month == $i){
                            ?>
                            <option value = "<?php echo $i ;?>" selected><?php echo $i ;?></option>
                            <?php
                        }
                        else{
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
                        <?php
                        }
					}
				?>
				</select>
				<select style="display:block; width:33%; float:left;" name = "birth_day" required>
				<option value = "">日</option>
				<?php 
					for($i = 1 ;$i <= 31 ;$i++){
						if($user_birth_day == $i){
                            ?>
                            <option value = "<?php echo $i ;?>" selected><?php echo $i ;?></option>
                            <?php
                        }
                        else{
						?>
						<option value = "<?php echo $i ;?>"><?php echo $i ;?></option>
                        <?php
                        }
					}
				?>
				</select>
				<p>動画1本あたりの単価</p>
				<input type="number" placeholder="動画1本あたりの単価" name="unit_price" value="<?php echo $user_unit_price; ?>" min="500" max="1000000" required /> 
				<p>編集歴</p>
				<?php
				if($user_edit_career == 1){
					$edit_career_switch1 = "selected";
				}
				else if($user_edit_career == 2){
					$edit_career_switch2 = "selected";
				}
				else if($user_edit_career == 3){
					$edit_career_switch3 = "selected";
				}
				else if($user_edit_career == 4){
					$edit_career_switch4 = "selected";
				}
				else if($user_edit_career == 5){
					$edit_career_switch5 = "selected";
				}
				?>
				<select style="display:block" name = "edit_career" required>
				<option value="1" <?php echo $edit_career_switch1; ?>>未経験
				<option value="2" <?php echo $edit_career_switch2; ?>>1年未満
				<option value="3" <?php echo $edit_career_switch3; ?>>1~2年
				<option value="4" <?php echo $edit_career_switch4; ?>>2~3年
				<option value="5" <?php echo $edit_career_switch5; ?>>3年以上
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
					$i = 0;
					while ($row = $result->fetch_assoc()) {
						$software_id = $row['id'];
						$software_edit_software_name = $row['edit_software_name'];
						?>
						<div style="width:100%;">
						<?php
						$array_user_edit_software = preg_split("/,/",$user_edit_software);
						$count_array_user_edit_software = count($array_user_edit_software);
						$softwere_switch = 0;
							for($i=0;$i<=$count_array_user_edit_software;$i++){
								if($software_id  == $array_user_edit_software[$i]){
									$softwere_switch = 1;
								}
							}
							if($softwere_switch == 1){
								?>
								<input style="width:auto !important; margin-right:8px !important;" type="checkbox" name="edit_software[]" value="<?php echo $software_id ; ?>" checked><?php echo $software_edit_software_name; ?>
								<?php
							}
							else if($softwere_switch == 0){
								?>
								<input style="width:auto !important; margin-right:8px !important;" type="checkbox" name="edit_software[]" value="<?php echo $software_id ; ?>"><?php echo $software_edit_software_name; ?>
								<?php
							}
						?>
						</div>
						<?php
					  }
				?>
				<p>自己紹介</p>
				<textarea placeholder="映像制作をしております「○○○○」と申します。" name="prof_para" required><?php echo $user_prof_para; ?></textarea> 
				<p>ポートフォリオ</p>
				<input type="text" placeholder="表示名" name="portfolio_title" value="<?php echo $user_portfolio_title; ?>" /> 
				<input type="text" placeholder="https://youtube.com" name="portfolio_url" value="<?php echo $user_portfolio_url; ?>"> 
				<button type="submit" name="update_editer" class="btn">更新</button>
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