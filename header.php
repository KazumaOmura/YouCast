<head>  
    <link rel="stylesheet" href="https://youcast.jp/css/badge.css">
    <script type="text/javascript" src="js/main.js"></script>
</head>
<?php
ob_start();
session_start();
include_once 'dbconnect.php';
$query = "SELECT * FROM user WHERE id=".$_SESSION['user']."";
$result = $mysqli->query($query);

if (!$result) {
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}

// ユーザー情報の取り出し
while ($row = $result->fetch_assoc()) {
  $user_authority = $row['authority'];
}

// チャットの未読
if($user_authority == 0){
    $query = "SELECT * FROM chat WHERE client_id=".$_SESSION['user']." AND read_flag = 0 AND send_flag = 0";
}
else if($user_authority == 1){
    $query = "SELECT * FROM chat WHERE editer_id=".$_SESSION['user']." AND read_flag = 0 AND send_flag = 1";
}
$result = $mysqli->query($query);

if (!$result) {
  print('クエリーが失敗しました。' . $mysqli->error);
  $mysqli->close();
  exit();
}
$num = 0;
// ユーザー情報の取り出し
while ($row = $result->fetch_assoc()) {
  $num ++;
}
if($user_authority == 0){
    $query = "SELECT * FROM point WHERE client_id=".$_SESSION['user']."";
    $result = $mysqli->query($query);

    if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
    }

    // ユーザー情報の取り出し
    $user_point_sum = 0;
    while ($row = $result->fetch_assoc()) {
        $user_point = $row['point_num'];
        $user_add_or_delete = $row['add_or_delete'];
            if($user_add_or_delete == 1){
                $user_point_sum += $user_point;
            }
            else if($user_add_or_delete == 0){
                $user_point_sum -= $user_point;
            }
        }
        $user_point_sum_number = number_format($user_point_sum);
}
if($user_authority == 0){
    ?>
    <header id="header">
    <nav id="pc" class="pc-nav">
        <div class="header_left">
            <a href="https://youcast.jp/index.php"><img id="logo_img" src="https://youcast.jp/image/youcast_logo_transe.png" alt="logo"></a>
        </div>
        <div class="header_right">
            <ul class="header_ul">
                <li><a class="normal" href="https://youcast.jp/mypoint.php"><?php echo $user_point_sum_number; ?>pt</a></li>
                <li><a class="normal" href="https://youcast.jp/home.php">ホーム</a></li>
                <li><a class="normal" href="https://youcast.jp/mypage.php">マイページ</a></li>
                <li><a class="normal" href="https://youcast.jp/point.php">ポイント購入</a></li>
                <li><a class="normal" href="https://youcast.jp/search/index.php">編集者を探す</a></li>
                <li><a class="normal" href="https://youcast.jp/offer_matter.php">発注リスト</a></li>
                <li><a id="icon" data-num="<?php echo $num;?>" class="normal" href="https://youcast.jp/notification_list.php"><img style="width:20px;" src="https://youcast.jp/image/bell.png"></a></li>
                <li><a href="https://youcast.jp/logout.php?logout" class="btn btn_blue">ログアウト</a></li>
            </ul>
        </div>
    </nav>
    <nav id="mobile" class="mobile-nav">
        <div class="header_left">
            <a href="https://youcast.jp/index.php"><img id="logo_img" src="https://youcast.jp/image/youcast_logo_transe.png" alt="logo"></a>
        </div>
        <div class="header_right">
        <div class="header-logo-menu">
            <div id="nav-drawer">
                <input id="nav-input" type="checkbox" class="nav-unshown">
                <label id="nav-open" for="nav-input"><span></span></label>
                <label class="nav-unshown" id="nav-close" for="nav-input"></label>
                <div id="nav-content">
                    <ul class="header_ul">
                        <li><a class="normal" href="https://youcast.jp/mypoint.php"><?php echo $user_point_sum_number; ?>pt</a></li>
                        <li><a class="normal" href="https://youcast.jp/home.php">ホーム</a></li>
                        <li><a class="normal" href="https://youcast.jp/mypage.php">マイページ</a></li>
                        <li><a class="normal" href="https://youcast.jp/point.php">ポイント購入</a></li>
                        <li><a class="normal" href="https://youcast.jp/search/index.php">編集者を探す</a></li>
                        <li><a class="normal" href="https://youcast.jp/offer_matter.php">発注リスト</a></li>
                        <li><a id="icon" data-num="<?php echo $num;?>" class="normal" href="https://youcast.jp/notification_list.php"><img style="width:20px;" src="https://youcast.jp/image/bell.png"></a></li>
                        <li><a href="https://youcast.jp/logout.php?logout" class="btn btn_blue">ログアウト</a></li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
        
    </nav>
</header>
<div id="header_bottom_div"></div>
    <?php
}
else if($user_authority == 1){
    ?>
    <header id="header">
    <nav id="pc" class="pc-nav">
        <div class="header_left">
            <a href="https://youcast.jp/index.php"><img id="logo_img" src="https://youcast.jp/image/youcast_logo_transe.png" alt="logo"></a>
        </div>
        <div class="header_right">
            <ul class="header_ul">
                <li><a class="normal" href="https://youcast.jp/home.php">ホーム</a></li>
                <li><a class="normal" href="https://youcast.jp/mypage.php">マイページ</a></li>
                <li><a class="normal" href="https://youcast.jp/invitation.php">招待</a></li>
                <li><a class="normal" href="https://youcast.jp/received_matter.php">受注リスト</a></li>
                <li><a class="normal" href="https://youcast.jp/mypoint.php">ポイント</a></li>
                <li><a id="icon" data-num="<?php echo $num;?>" class="normal" href="https://youcast.jp/#"><img style="width:20px;" src="https://youcast.jp/image/bell.png"></a></li>
                <li><a href="https://youcast.jp/logout.php?logout" class="btn btn_blue">ログアウト</a></li>
            </ul>
        </div>
    </nav>
    <nav id="mobile" class="mobile-nav">
        <div class="header_left">
            <a href="https://youcast.jp/index.php"><img id="logo_img" src="https://youcast.jp/image/youcast_logo_transe.png" alt="logo"></a>
        </div>
        <div class="header_right">
        <div class="header-logo-menu">
            <div id="nav-drawer">
                <input id="nav-input" type="checkbox" class="nav-unshown">
                <label id="nav-open" for="nav-input"><span></span></label>
                <label class="nav-unshown" id="nav-close" for="nav-input"></label>
                <div id="nav-content">
                    <ul class="header_ul">
                        <li><a class="normal" href="https://youcast.jp/home.php">ホーム</a></li>
                        <li><a class="normal" href="https://youcast.jp/mypage.php">マイページ</a></li>
                        <li><a class="normal" href="https://youcast.jp/invitation.php">招待</a></li>
                        <li><a class="normal" href="https://youcast.jp/received_matter.php">受注リスト</a></li>
                        <li><a class="normal" href="https://youcast.jp/mypoint.php">ポイント</a></li>
                        <li><a id="icon" data-num="<?php echo $num;?>" class="normal" href="https://youcast.jp/#"><img style="width:20px;" src="https://youcast.jp/image/bell.png"></a></li>
                        <li><a href="https://youcast.jp/logout.php?logout" class="btn btn_blue">ログアウト</a></li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
        
    </nav>
</header>
<div id="header_bottom_div"></div>
    <?php
}
else if($user_authority == 2){
    ?>
    <header id="header">
    <nav id="pc" class="pc-nav">
        <div class="header_left">
            <a href="https://youcast.jp/index.php"><img id="logo_img" src="https://youcast.jp/image/youcast_logo_transe.png" alt="logo"></a>
        </div>
        <div class="header_right">
            <ul class="header_ul">
                <li><a class="normal" href="https://youcast.jp/home.php">ホーム</a></li>
                <li><a class="normal" href="https://youcast.jp/mypage.php">マイページ</a></li>
                <li><a class="normal" href="https://youcast.jp/admin.php">管理ページ</a></li>
                <li><a id="icon" data-num="<?php echo $num;?>" class="normal" href="https://youcast.jp/#"><img style="width:20px;" src="https://youcast.jp/image/bell.png"></a></li>
                <li><a href="https://youcast.jp/logout.php?logout" class="btn btn_blue">ログアウト</a></li>
            </ul>
        </div>
    </nav>
    <nav id="mobile" class="mobile-nav">
        <div class="header_left">
            <a href="https://youcast.jp/index.php"><img id="logo_img" src="https://youcast.jp/image/youcast_logo_transe.png" alt="logo"></a>
        </div>
        <div class="header_right">
        <div class="header-logo-menu">
            <div id="nav-drawer">
                <input id="nav-input" type="checkbox" class="nav-unshown">
                <label id="nav-open" for="nav-input"><span></span></label>
                <label class="nav-unshown" id="nav-close" for="nav-input"></label>
                <div id="nav-content">
                    <ul class="header_ul">
                        <li><a class="normal" href="https://youcast.jp/home.php">ホーム</a></li>
                        <li><a class="normal" href="https://youcast.jp/mypage.php">マイページ</a></li>
                        <li><a class="normal" href="https://youcast.jp/admin.php">管理ページ</a></li>
                        <li><a id="icon" data-num="<?php echo $num;?>" class="normal" href="https://youcast.jp/#"><img style="width:20px;" src="https://youcast.jp/image/bell.png"></a></li>
                        <li><a href="https://youcast.jp/logout.php?logout" class="btn btn_blue">ログアウト</a></li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
        
    </nav>
</header>
<div id="header_bottom_div"></div>
    <?php
}
?>