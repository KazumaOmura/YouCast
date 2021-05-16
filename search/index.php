<?php
ob_start();
session_start();
// if(isset($_SESSION['user']) != "") {
//   // ログイン済みの場合はリダイレクト
//   header("Location: home.php");
// }
// DBとの接続
include_once '../dbconnect.php';
if(isset($_POST['signup'])) {
    $email = $mysqli->real_escape_string($_POST['email']);

    $query = "INSERT INTO user(email,prof_done,authority) VALUES('$email',0,0)";
            
    $result = $mysqli->query($query);
    if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
    }
    else{
        $queryy = "SELECT * FROM user WHERE email='$email'";
        $resultt = $mysqli->query($queryy);
        while ($roww = $resultt->fetch_assoc()) {
          $id = $roww['id'];
        }
        $_SESSION['user'] = $id;
        header("Location: prof_insert.php");
    }
}
?>



<?php
if(isset($_POST['search'])){
	//エスケープして前処理
	$max_unit_price = $mysqli->real_escape_string($_POST['max_unit_price']);
	$min_unit_price = $mysqli->real_escape_string($_POST['min_unit_price']);
	$edit_software = $_POST['edit_software'];

	if($max_unit_price < $min_unit_price){?>
		<div role="alert" id="alert">最低価格が最高価格を上回っています</div>
		<?php
	}else{
		$array1 = implode(",",$edit_software);
		if(!$edit_software){
			header("Location: index.php?max=$max_unit_price&min=$min_unit_price");
		}else{
			header("Location: index.php?max=$max_unit_price&min=$min_unit_price&edit_soft=$array1");
		}

	}

}



?>

<!DOCTYPE HTML>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/mypage.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script type="text/javascript" src="../js/main.js"></script>

  <title>編集者検索 - YouCast</title>
</head>

<body>
<?php 
if(isset($_SESSION['user']) != "") {
	include('../header.php'); 
}
else{
	include('../header_index.php'); 
}?>
	<form method="post">
		<input type="number" name="max_unit_price" placeholder="最高価格" required/>
		<input type="number" name="min_unit_price" placeholder="最低価格" required/>
		<p>編集ソフト</p>
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
				<input style="width:auto !important; margin-right:8px !important;" type="checkbox" name="edit_software[]" value="<?php echo $software_id ; ?>"><?php echo $software_edit_software_name; ?>
			<?php
		  }
		?>
		<button type="submit" name="search" class="btn">検索</button>
	</form>
	<main style="overflow:hidden; margin-top:5px !important;">
		<?php
		$max = $_GET["max"];
		$min = $_GET["min"];
		$search_edit_software = $_GET["edit_soft"];
		if(!$search_edit_software){
			// echo "nasidesu";

		}
		else{
			$array_user_edit_software = preg_split("/,/",$search_edit_software);
			$count_array_user_edit_software = count($array_user_edit_software);
			//echo $count_array_user_edit_software;
		}
		$edit_software_list = " AND edit_software LIKE '%";
			for ($i = 0; $i < $count_array_user_edit_software; $i++){
				$edit_software_list .= "$array_user_edit_software[$i]";
				$edit_software_list .= "%";
			}
			$edit_software_list = substr($edit_software_list,0,-1);
			$edit_software_list .= "%'";

		if($max!=NULL){
			$query = "SELECT * FROM editer_prof WHERE unit_price <= $max AND unit_price >= $min";
			$query .= $edit_software_list;
			$result = $mysqli->query($query);
		
			if (!$result) {
				print('クエリーが失敗しました。search' . $mysqli->error);
				$mysqli->close();
				exit();
			}
			?>
			<div id="editer_list">
			<h2>SEARCH RESULT</h2>
			<h1>検索結果</h1>
			<?php
			while ($row = $result->fetch_assoc()) {
				$editer_id = $row['id'];
				$editer_user_id = $row['user_id'];
				$editer_username = $row['username'];
				$editer_image_name = $row['image_name'];
				$editer_unit_price = $row['unit_price'];
				$editer_unit_jpy_price = number_format($editer_unit_price);

				$query2 = "SELECT * FROM user WHERE id=$editer_user_id";
				$result2 = $mysqli->query($query2);
			
				if (!$result2) {
					print('クエリーが失敗しました。search' . $mysqli->error);
					$mysqli->close();
					exit();
				}
				while ($row2 = $result2->fetch_assoc()) {
					$editer_user_id = $row2['id'];
				}
				
				if($editer_id == NULL){
					echo "<p>いません</p>";
				}
				else{

					if($editer_register_year_time<=2021 && $editer_register_month_time<=5 && $editer_register_month_time<=31){
					?>
					<div class="editer_list_detail triangle">
					<?php
					}
					else{
					?>
					<div class="editer_list_detail">
					<?php
					}
					?>
					<?php
							$query2 = "SELECT * FROM evaluation WHERE editer_id='$editer_id'";
							$result2 = $mysqli->query($query2);

							if (!$result2) {
							print('クエリーが失敗しました。' . $mysqli->error);
							$mysqli->close();
							exit();
							}

							$sum = 0;

							// ユーザー情報の取り出し
							while ($row2 = $result2->fetch_assoc()) {
								$sum_star += $row2['star'];
								$sum++;
							}
							if($sum_star!=NULL || $sum != NULL){
							$average_star = $sum_star / $sum;           
							$average_star = round($average_star, 1);
							}
							else{
							$average_star = 0;
							}        


							if($sum == 0){
								$average_star = 0;
							}
						?>
						<div class="editer_list_detail_top">
							<div class="editer_list_detail_top_left">
								<img src="https://youcast.jp/profile_img/editer/<?php echo $editer_image_name; ?>">
							</div>
							<div class="editer_list_detail_top_right">
								<h3><?php echo $editer_username ;?></h3>
								<p><span class="star5_rating" data-rate="<?php echo $average_star; ?>"></span></p></p> 
							</div>
						</div>
						<div class="editer_list_detail_bottom">
							<div class="editer_list_detail_bottom_left">
								<p><span class="background_gray">実績</span><?php echo $sum; ?>件</p>
								<p><span class="background_gray">単価</span>¥<?php echo $editer_unit_jpy_price ;?>-</p>
							</div>
							<div class="editer_list_detail_bottom_right">
								<?php
								if(!$_SESSION['user']){
									?>
									<p><a href="https://youcast.jp/login.php">→</a></p>
									<?php
								}
								else{
									?>
									<p><a href="https://youcast.jp/profile.php?id=<?php echo $editer_user_id; ?>">→</a></p>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				<?php
				}

			 }
			 ?>
			 </div>
			 <?php
	
			}
			
		
		?>
		</main>
<?php include('../footer.php'); ?>
</body>
</html>