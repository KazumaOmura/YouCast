<?php
ob_start();
session_start();
if(isset($_SESSION['user']) != "") {
  // ログイン済みの場合はリダイレクト
  header("Location: home.php");
}
// DBとの接続
include_once 'dbconnect.php';

if(isset($_POST['signup'])) {
    //$username = $mysqli->real_escape_string($_POST['username']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $password = password_hash($password, PASSWORD_DEFAULT);
  
    require "function/return_sql.php";
    $query3 = "SELECT * FROM user";
    $result3 = $mysqli->query($query3);
  
    if (!$result3) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    $switch = 1;
    while ($row3 = $result3->fetch_assoc()) {
      $switch_email = $row3['email'];
      if($switch_email == $email){
        $switch = 0;
      }
    }
  
    $query4 = "SELECT * FROM contemporary_user";
    $result4 = $mysqli->query($query4);
  
    if (!$result4) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    while ($row4 = $result4->fetch_assoc()) {
      $switch_email = $row4['email'];
      if($switch_email == $email){
        $switch = 0;
      }
    }
    if($switch == 0){
      ?>
        <div role="alert" id="alert">同じメールアドレスが存在します。</div>
      <?php
    }
    else{
      $today = date("H,i,s"); 
      $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';  
      // 変数の初期化
      $new_code = '';
  
      // 繰り返し処理でランダムに文字列を生成(8文字)
      for ($i = 0; $i < 6; $i++) {
        $auth_code .= $chars[mt_rand(0, 61)];
      }
  
      // 生成された文字列を出力
      $query = "INSERT INTO contemporary_user(email,password,auth_code,insert_date) VALUES('$email','$password','$auth_code','$today')";
            
      $result = $mysqli->query($query);
        if (!$result) {
          print('クエリーが失敗しました。' . $mysqli->error);
          $mysqli->close();
          exit();
          ?>
          <div role="alert" id="alert">エラーが発生しました</div>
          <?php
        }
        else{
          $to_title = $_POST['title'];
          $to_name = $_POST['name'];
          $to_email = $_POST['email'];
          $to_contents = $_POST['contents'];
  
          $host_email = "info@youcast.jp";
  
          mb_language("Japanese"); 
          mb_internal_encoding("UTF-8");
          $subject = "【YouCast】仮登録認証コード発行のお知らせ"; // 題名 
          $subject .= $to_title;
          $body .= $to_contents;
          $body .= "YouCastに仮登録いただき誠にありがとうございます。";
          $body .= "\n";
          $body .= "ご本人様確認のため、下記URLから「10分以内」に発行された認証コードを入力してアカウントの本登録を完了させて下さい。\n";
          $body .= "https://youcast.jp/main_register.php";
          $body .= "\n";
          $body .= "___________________________\n";
          $body .= "認証コード：";
          $body .= $auth_code;
          $body .= "\n";
          $body .= "___________________________\n";
          $body .= "\n";
          $body .= "-------------------------------------------------";
          $body .= "\n";
          $body .= "【YouCastからのお知らせ】";
          $body .= "\n";
          $body .= "このお知らせは、メールにも自動配信されます。";
          $body .= "\n";
          $body .= "送信専用アドレスから送信しているため、";
          $body .= "\n";
          $body .= "返信をしても回答できませんのでご注意ください。";
          $body .= "\n";
          $body .= "質問等がありましたら直接管理者へご連絡ください。";
          $body .= "\n";
          $body .= "-------------------------------------------------";
          $to = $to_email;
          $header = "From: $host_email";
          
          mb_send_mail($to, $subject, $body, $header);
          header("Location: main_register.php");
    }
  }
  }
?>


<!DOCTYPE HTML>
<html lang="ja">
<meta charset="utf-8" />

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/mypage.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="js/main.js"></script>

<title>優秀な動画編集者を探すならYouCast</title>
<meta name="description" content="手数料業界最安値！YouTuberと動画編集者のマッチングサービス『YouCast』がリリース！YouTubeで人気になりたい方向けに経験豊富な編集者を紹介するプラットフォーム！">

<style type="text/css">
.outline {
  color          : #ffffff;            /* 文字の色 */
  font-size      : 28px !important;               /* 文字のサイズ */
  letter-spacing : 2px;                /* 文字間 */
  text-shadow    : 
       1px  1px 0px #000000,
      -1px  1px 0px #000000,
       1px -1px 0px #000000,
      -1px -1px 0px #000000,
       1px  0px 0px #000000,
       0px  1px 0px #000000,
      -1px  0px 0px #000000,
       0px -1px 0px #000000;        /* 文字の影 */
}
</style>

</head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-157129050-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-157129050-2');
</script>


<body>
<section class="typeA">
	<input id="TAB-A01" type="radio" name="TAB-A" value="1" checked="checked">
	<label class="tabLabel" for="TAB-A01">編集を依頼したい</label>
    <input id="TAB-A02" type="radio" name="TAB-A" value="2">
	<label class="tabLabel" for="TAB-A02">編集を受けたい</label>
    </section>
    <?php include('header_index.php'); ?>
    

	<div id="content_div1" class="content">
        <!-- ここからクライアントエリア -->
        <div id="top">
            <div class="top_left">
                <h1>¥1,000から応募できる</h1>
                <p>YouTuberに特化した動画編集者を探せる</br>紹介サービス</p>
                <p style="font-weight:bold;">＼まずはEmailで無料登録／</p>

                <form method="post">
                    <input type="email" placeholder="メールアドレス" name="email" required />
                    <input type="password" placeholder="パスワード" name="password" min="5" required />
                    <button type="submit" name="signup" class="btn btn_red">無料で編集者を探す</button>
                </form>
            </div>
            <div class="top_right">
                <img class="top_img" src="image/top.png" alt="top画像">
            </div>
        </div>
        <div id="portfolio_list">
            <?php
            $query = "SELECT * FROM user WHERE authority = 1" ;
            $result = $mysqli->query($query);

            if (!$result) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
            }
            // ユーザー情報の取り出し
            $editer_num = 0;
            while ($row = $result->fetch_assoc()) {
                $editer_num ++;
            }
            $editer_num_number = number_format($editer_num);

            $query2 = "SELECT * FROM point" ;
            $result2 = $mysqli->query($query2);

            if (!$result2) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
            }
            // ユーザー情報の取り出し
            $point_sum = 0;
            while ($row2 = $result2->fetch_assoc()) {
                $point_sum += $row2['point_num'];
            }
            $point_sum_number = number_format($point_sum+86000);

            $query3 = "SELECT * FROM order_receive" ;
            $result3 = $mysqli->query($query3);

            if (!$result3) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
            }
            // ユーザー情報の取り出し
            $order_num = 0;
            while ($row3 = $result3->fetch_assoc()) {
                $order_num ++;
            }
            $order_num_number = number_format($order_num+10);
            ?>
                <div class="portfolio_detail1">
                    <div class="portfolio_detail_top">
                        <p>累計流通額</p>
                    </div>
                    <div class="portfolio_detail_bottom">
                        <h4><?php echo $point_sum_number; ?><span style="font-size:15px; color:#000000; font-weight:bold; padding-left:3px;">円</span></h4>
                    </div>
                </div>
                <div class="portfolio_detail2">
                    <div class="portfolio_detail_top">
                        <p>動画編集者数</p>
                    </div>
                    <div class="portfolio_detail_bottom">
                        <h4><?php echo $editer_num_number; ?><span style="font-size:15px; color:#000000; font-weight:bold; padding-left:3px;">人</span></h4>
                    </div>
                </div>
                <div class="portfolio_detail3">
                    <div class="portfolio_detail_top">
                        <p>案件数</p>
                    </div>
                    <div class="portfolio_detail_bottom">
                        <h4><?php echo $order_num_number; ?><span style="font-size:15px; color:#000000; font-weight:bold; padding-left:3px;">件</span></h4>
                    </div>
                </div>
            </div>
        <main style="margin-top:0 !important;">
            <div id="about">
                <h2 style="color:#1E82DB; margin-bottom:15px;">ABOUT</h2>
                <h1 style="margin-top:0;">YouCastとは</h1>
                <img src="image/samune.png">
                <div class="about_detail">
                    <div class="about_detail_top">
                        <p>point</p>
                        <h3 class="outline">01</h3>
                    </div>
                    <div class="about_detail_bottom">
                        <h3>現役YouTuberが編集を担当します</h3>
                        <p>"現役"×"編集スキル"を兼ね備えた選りすぐりの編集者があなたの動画を編集します。</p>
                    </div>
                </div>
                <div class="about_detail">
                    <div class="about_detail_top">
                        <p>point</p>
                        <h3 class="outline">02</h3>
                    </div>
                    <div class="about_detail_bottom">
                        <h3>豊富な実績を持つ編集者のみ承認</h3>
                        <p>サービス管理者が編集者を承認制で運営しているので、質を担保しています。</p>
                    </div>
                </div>
                <div class="about_detail">
                    <div class="about_detail_top">
                        <p>point</p>
                        <h3 class="outline">03</h3>
                    </div>
                    <div class="about_detail_bottom">
                        <h3>"お得"なポイント制を採用</h3>
                        <p>YouCast内のやりとりは全てポイント方式になっています。</p>
                    </div>
                </div>
            </div>
            <div id="editer_list">
                <h2 style="margin-bottom:15px;">New Editer</h2>
                <h1 style="margin-top:0;">新着編集者</h1>
                <?php
                    $query = "SELECT * FROM user WHERE authority = 1 ORDER BY id DESC";
                    $result = $mysqli->query($query);

                    if (!$result) {
                    print('クエリーが失敗しました。' . $mysqli->error);
                    $mysqli->close();
                    exit();
                    }
                    // ユーザー情報の取り出し
                    $num = 0;
                    $num2 = 0;
                    while ($row = $result->fetch_assoc()) {
                        $editer_id[$num] = $row['id'];
                        $editer_id_sql = $editer_id[$num];
                        $editer_register_year_time[$num] = $row['register_year_time'];
                        $editer_register_month_time[$num] = $row['register_month_time'];
                        $editer_register_day_time[$num] = $row['register_day_time'];
                        $query2 = "SELECT * FROM editer_prof WHERE user_id = '$editer_id_sql'" ;
                        $result2 = $mysqli->query($query2);

                        if (!$result2) {
                        print('クエリーが失敗しました。' . $mysqli->error);
                        $mysqli->close();
                        exit();
                        }
                        // ユーザー情報の取り出し
                        while ($row2 = $result2->fetch_assoc()) {
                            $editer_profile_id[$num] = $row2['id'];
                            $editer_username[$num] = $row2['username'];
                            $editer_unit_price[$num] = $row2['unit_price'];
                            $editer_unit_jpy_price[$num] = number_format($editer_unit_price[$num]);
                            $editer_image_name[$num] = $row2['image_name'];
                        }
                        $num ++;

                        $query3 = "SELECT * FROM evaluation WHERE editer_id='$editer_id_sql'";
                            $result3 = $mysqli->query($query3);

                            if (!$result3) {
                            print('クエリーが失敗しました。' . $mysqli->error);
                            $mysqli->close();
                            exit();
                            }
                            $sum[$num2] = 0;

                            // ユーザー情報の取り出し
                            while ($row3 = $result3->fetch_assoc()) {
                                $sum_star[$num2] += $row3['star'];
                                $sum[$num2]++;
                            }

                            
                            if($sum_star[$num2]!=NULL || $sum[$num2] != NULL){
                                $average_star[$num2] = $sum_star[$num2] / $sum[$num2];           
                                $average_star[$num2] = round($average_star[$num2], 1);
                            }
                            else{
                                $average_star[$num2] = 0;
                            } 

                            if($sum[$num2] == 0){
                                $average_star[$num2] = 0;

                            }
                            $num2 ++;
                    }
                    for($num=0; $num<12; $num++){
                        if(!$editer_username[$num]){

                        }
                        else{
                            if($editer_register_year_time[$num]<=2021 && $editer_register_month_time[$num]<=5 && $editer_register_month_time[$num]<=31){
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
                            <div class="editer_list_detail_top">
                                <div class="editer_list_detail_top_left">
                                    <img src="profile_img/editer/<?php echo $editer_image_name[$num]; ?>">
                                </div>
                                <div class="editer_list_detail_top_right">
                                    <h3><?php echo $editer_username[$num] ;?></h3>
                                    <p><span class="star5_rating" data-rate="<?php echo $average_star[$num]; ?>"></span></p></p> 
                                </div>
                            </div>
                            <div class="editer_list_detail_bottom">
                                <div class="editer_list_detail_bottom_left">
                                    <p><span class="background_gray">実績</span><?php echo $sum[$num]; ?>件</p>
                                    <p><span class="background_gray">単価</span>¥<?php echo $editer_unit_jpy_price[$num] ;?>-</p>
                                </div>
                                <div class="editer_list_detail_bottom_right">
                                    <p><a href="profile.php?id=<?php echo $editer_id[$num]; ?>">→</a></p>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                    }
                    ?>
            </div>
            <div id="reason">
                <h2 style="color:#1E82DB; margin-bottom:15px;">MERIT</h2>
                <h1 style="margin-top:0;">他にはない独自のメリット</h1>
                <div class="reason_detail">
                    <div class="reason_detail_top">
                        <img src="image/fee.png">
                    </div>
                    <div class="reason_detail_bottom">
                        <h2>招待制 & 承認制</h2>
                        <p>運営に承認された編集者しか居ない為、高品質な動画が納品されます。さらに、編集者の枠は限定されているので粗悪な編集者は居ません。</p>
                    </div>
                </div>
                <div class="reason_detail">
                    <div class="reason_detail_top">
                        <img src="image/editer.png">
                    </div>
                    <div class="reason_detail_bottom">
                        <h2>経験豊富な編集者</h2>
                        <p>経験を詰んだ編集者のみを採用しているため、未経験者は"ゼロ"です。信頼と品質に特化した編集者揃いなので心配は要りません。</p>
                    </div>
                </div>
                <div class="reason_detail">
                    <div class="reason_detail_top">
                        <img src="image/youtube_image.png">
                    </div>
                    <div class="reason_detail_bottom">
                        <h2>YouTuber専門</h2>
                        <p>100万人越えのYouTuberの編集を手掛ける編集者も居ます。現役で活躍する最前線の編集者が揃っています。</p>
                    </div>
                </div>
            </div>
        </main>
            <div id="flow">
                <h2 style="margin-bottom:15px;">FLOW</h2>
                <h1 style="margin-top:0;">ご利用の流れ</h1>
                <div class="flow_detail">
                    <div class="flow_detail_top">
                        <h3 class="flow_num">１</h3>
                        <img src="image/signup.png">
                        <h3>会員登録</h3>
                    </div>
                    <div class="flow_detail_bottom">
                        <p>サイト上で無料会員登録をしていただきます。</p>
                    </div>
                </div>
                <div class="flow_detail">
                    <div class="flow_detail_top">
                        <h3 class="flow_num">２</h3>
                        <img src="image/search.png">
                        <h3>編集者を探す</h3>
                    </div>
                    <div class="flow_detail_bottom">
                        <p>あなたの条件にあった編集者を検索します。</p>
                    </div>
                </div>
                <div class="flow_detail">
                    <div class="flow_detail_top">
                        <h3 class="flow_num">３</h3>
                        <img src="image/agreement.png">
                        <h3>契約</h3>
                    </div>
                    <div class="flow_detail_bottom">
                        <p>サイト内でメッセージを送り編集者と契約を結びます。</p>
                    </div>
                </div>
                <div class="flow_detail">
                    <div class="flow_detail_top">
                        <h3 class="flow_num">４</h3>
                        <img src="image/delivery.png">
                        <h3>納品</h3>
                    </div>
                    <div class="flow_detail_bottom">
                        <p>問題なくデータが納品されたら完了です。</p>
                    </div>
                </div>
            </div>
        <main>
            <div id="news">
                <h2 style="color:#1E82DB; margin-bottom:15px;">NEWS</h2>
                <h1 style="margin-top:0;">ニュース</h1>
                <hr>

                <?php
                // ユーザーIDからユーザー名を取り出す
                $query = "SELECT * FROM information ORDER BY id desc LIMIT 3";
                $result = $mysqli->query($query);

                if (!$result) {
                print('クエリーが失敗しました。追加位置' . $mysqli->error);
                $mysqli->close();
                exit();
                }

                // ユーザー情報の取り出し
                while($row = $result->fetch_assoc()){
                    $news_id = $row['id'];
                    $new_info_title = $row['title'];
                    $times = $row['times'];
                
                ?>
                <div class="news_detail">
                    <div class="news_detail_1st">
                        <p><?php echo $times?></p>
                    </div>
                    <div class="news_detail_2nd">
                        <p>アップデート</p>
                    </div>
                    <div class="news_detail_3rd">
                        <p><a href="news.php?news_id=<?php echo $news_id?>"><?php echo $new_info_title?></a></p>
                    </div>
                </div>
                <?php }?>

                <hr>
            </div>
            <!-- <div id="banner_list">
                <a href="#">
                    <div class="banner_list_detail">
                        <div class="banner_list_detail_left">
                            <h3>はじめての方へ</h3>
                            <p>ご登録から納品完了までの流れをご紹介</p>
                        </div>
                        <div class="banner_list_detail_right">
                            <img src="image/youcast_logo.png">
                        </div>
                    </div>
                </a>
                <a href="#">
                    <div class="banner_list_detail">
                        <div class="banner_list_detail_left">
                            <h3>動画編集者の方はこちら</h3>
                            <p>編集者として登録をお願いします</p>
                        </div>
                        <div class="banner_list_detail_right">
                            <img src="image/youcast_logo.png">
                        </div>
                    </div>
                </a>
                <a href="#">
                    <div class="banner_list_detail">
                        <div class="banner_list_detail_left">
                            <h3>完全招待制について</h3>
                            <p>動画編集者としての登録について</p>
                        </div>
                        <div class="banner_list_detail_right">
                            <img src="image/youcast_logo.png">
                        </div>
                    </div>
                </a>
            </div> -->
        </main>
        <!-- ここまでクライアントエリア -->
	</div>
	
	<div id="content_div2" class="none">
        <!-- ここから編集者エリア -->
        <main style="margin-top:0 !important;">
            <div id="about">
                <h1 style="margin-top:0; text-align:left;">YouCastが選ばれる理由</h1>
                <hr style="border-top: 3px solid #1E82DB !important; margin-top:-10px;">
                <div class="about_detail">
                    <div class="about_detail_top">
                        <p>point</p>
                        <h3 class="outline">01</h3>
                    </div>
                    <div class="about_detail_bottom">
                        <h3>手数料、業界最安値</h3>
                        <p>案件受注の際に発生する手数料は契約金額の"10%"のみ！受注の際は10%以上の手数料は発生しません。</p>
                    </div>
                </div>
                <div class="about_detail">
                    <div class="about_detail_top">
                        <p>point</p>
                        <h3 class="outline">02</h3>
                    </div>
                    <div class="about_detail_bottom">
                        <h3>1対1による高単価</h3>
                        <p>間に仲介会社を挟まず、直接クライアントと契約しているので、高単価な案件が多いです。</p>
                    </div>
                </div>
                <div class="about_detail">
                    <div class="about_detail_top">
                        <p>point</p>
                        <h3 class="outline">03</h3>
                    </div>
                    <div class="about_detail_bottom">
                        <h3>YouTuber専門</h3>
                        <p>クライアントは全員YouTuberなので、あなたのスキルを最大限に生かすことができます。</p>
                    </div>
                </div>
            </div>
            <div id="reason">
                <h2 style="color:#1E82DB; margin-bottom:15px;">REASON</h2>
                <h1 style="margin-top:0;">案件が受注できる理由</h1>
                <div class="reason_detail">
                    <div class="reason_detail_top">
                        <img src="image/fee.png">
                    </div>
                    <div class="reason_detail_bottom">
                        <p class="reason_small_index">\まずは簡単に3分で会員登録！/</p>
                        <h2>YouCastがクライアントをお探しします</h2>
                        <p>運営に承認された編集者しか居ない為、高品質な動画が納品されます。さらに、編集者の枠は限定されているので粗悪な編集者は居ません。</p>
                    </div>
                </div>
                <div class="reason_detail">
                    <div class="reason_detail_top">
                        <img src="image/editer.png">
                    </div>
                    <div class="reason_detail_bottom">
                        <p class="reason_small_index">\プロフィールを充実させよう！/</p>
                        <h2>経験豊富な編集者</h2>
                        <p>経験を詰んだ編集者のみを採用しているため、未経験者は"ゼロ"です。信頼と品質に特化した編集者揃いなので心配は要りません。</p>
                    </div>
                </div>
                <div class="reason_detail">
                    <div class="reason_detail_top">
                        <img src="image/youtube_image.png">
                    </div>
                    <div class="reason_detail_bottom">
                        <p class="reason_small_index">\まずは簡単に3分で会員登録！/</p>
                        <h2>YouTuber専門</h2>
                        <p>100万人越えのYouTuberの編集を手掛ける編集者も居ます。現役で活躍する最前線の編集者が揃っています。</p>
                    </div>
                </div>
            </div>
        </main>
            <div id="flow">
                <h2 style="margin-bottom:15px;">FLOW</h2>
                <h1 style="margin-top:0;">ご利用の流れ</h1>
                <div class="flow_detail">
                    <div class="flow_detail_top">
                        <h3 class="flow_num">１</h3>
                        <img src="image/signup.png">
                        <h3>会員登録</h3>
                    </div>
                    <div class="flow_detail_bottom">
                        <p>サイト上で無料会員登録をしていただきます。</br>※招待コードが必要です。</p>
                    </div>
                </div>
                <div class="flow_detail">
                    <div class="flow_detail_top">
                        <h3 class="flow_num">２</h3>
                        <img src="image/search.png">
                        <h3>マッチング</h3>
                    </div>
                    <div class="flow_detail_bottom">
                        <p>あなたの条件を希望するクライアントとマッチングします。</p>
                    </div>
                </div>
                <div class="flow_detail">
                    <div class="flow_detail_top">
                        <h3 class="flow_num">３</h3>
                        <img src="image/agreement.png">
                        <h3>契約</h3>
                    </div>
                    <div class="flow_detail_bottom">
                        <p>サイト内でメッセージを送りクライアントと契約を結びます。</p>
                    </div>
                </div>
                <div class="flow_detail">
                    <div class="flow_detail_top">
                        <h3 class="flow_num">４</h3>
                        <img src="image/delivery.png">
                        <h3>評価</h3>
                    </div>
                    <div class="flow_detail_bottom">
                        <p>納品後、評価をして完了です。</p>
                    </div>
                </div>
            </div>
        <main>
            <div id="news">
                <h2 style="color:#1E82DB; margin-bottom:15px;">NEWS</h2>
                <h1 style="margin-top:0;">ニュース</h1>
                <hr>

                <?php
                // ユーザーIDからユーザー名を取り出す
                $query = "SELECT * FROM information ORDER BY id desc LIMIT 3";
                $result = $mysqli->query($query);

                if (!$result) {
                print('クエリーが失敗しました。追加位置' . $mysqli->error);
                $mysqli->close();
                exit();
                }

                // ユーザー情報の取り出し
                while($row = $result->fetch_assoc()){
                    $news_id = $row['id'];
                    $new_info_title = $row['title'];
                    $times = $row['times'];
                
                ?>
                <div class="news_detail">
                    <div class="news_detail_1st">
                        <p><?php echo $times?></p>
                    </div>
                    <div class="news_detail_2nd">
                        <p>アップデート</p>
                    </div>
                    <div class="news_detail_3rd">
                        <p><a href="news.php?news_id=<?php echo $news_id?>"><?php echo $new_info_title?></a></p>
                    </div>
                </div>
                <?php }?>

                <hr>
            </div>
            <!-- <div id="banner_list">
                <a href="#">
                    <div class="banner_list_detail">
                        <div class="banner_list_detail_left">
                            <h3>はじめての方へ</h3>
                            <p>ご登録から納品完了までの流れをご紹介</p>
                        </div>
                        <div class="banner_list_detail_right">
                            <img src="image/youcast_logo.png">
                        </div>
                    </div>
                </a>
                <a href="#">
                    <div class="banner_list_detail">
                        <div class="banner_list_detail_left">
                            <h3>動画編集者の方はこちら</h3>
                            <p>編集者として登録をお願いします</p>
                        </div>
                        <div class="banner_list_detail_right">
                            <img src="image/youcast_logo.png">
                        </div>
                    </div>
                </a>
                <a href="#">
                    <div class="banner_list_detail">
                        <div class="banner_list_detail_left">
                            <h3>完全招待制について</h3>
                            <p>動画編集者としての登録について</p>
                        </div>
                        <div class="banner_list_detail_right">
                            <img src="image/youcast_logo.png">
                        </div>
                    </div>
                </a>
            </div> -->
        </main>
        <!-- ここまで編集者エリア -->
    </div>
    <?php include('footer.php'); ?>

</body>


</html>
