<?php
ob_start();
session_start();
if(!isset($_SESSION['user']) != "") {
//   header("Location: index.php");
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
  $user_authority = $row['authority'];
}

$get_project_id = $_GET['project_id'];
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

if($user_authority == 0){
    if($project_client_id != $user_id){
        header("Location: home.php");
    }
}
else if($user_authority ==  1){
    if($project_editer_id != $user_id){
        header("Location: home.php");
    }
}

    $query3 = "SELECT * FROM client_prof WHERE user_id='$project_client_id'";
    $result3 = $mysqli->query($query3);
  
    if (!$result3) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
  
    // ユーザー情報の取り出し
    while ($row3 = $result3->fetch_assoc()) {
        $client_id = $row3['user_id'];
        $client_image_name = $row3['image_name'];
        $client_username = $row3['username'];
    }

    $query3 = "SELECT * FROM editer_prof WHERE user_id='$project_editer_id'";
    $result3 = $mysqli->query($query3);
  
    if (!$result3) {
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
  
    // ユーザー情報の取り出し
    while ($row3 = $result3->fetch_assoc()) {
        $editer_id = $row3['user_id'];
        $editer_image_name = $row3['image_name'];
        $editer_username = $row3['editer'];
    }  

    if(isset($_POST['project_condition_approval_client'])) {
        $project_id = $mysqli->real_escape_string($_POST['project_condition_approval_client']);

        echo $project_id;

        $query = "UPDATE order_receive SET done_flag = 1 WHERE id = $project_id ";
        
        $result = $mysqli->query($query);
        if(!$result){
            print('クエリーが失敗しました。' . $mysqli->error);
        }
        else{
            header("Location: chat.php?project_id=$project_id");
        }
    }
    if(isset($_POST['project_condition_approval_editer'])) {
        $project_id = $mysqli->real_escape_string($_POST['project_condition_approval_editer']);

        echo $project_id;

        $query = "UPDATE order_receive SET done_flag = 2 WHERE id = $project_id ";
        
        $result = $mysqli->query($query);
        if(!$result){
            print('クエリーが失敗しました。' . $mysqli->error);
        }
        else{
            header("Location: chat.php?project_id=$project_id");
        }
    }
        if(isset($_POST['pay'])) {
            $project_price = $mysqli->real_escape_string($_POST['pay']);
            $project_price_number = number_format($project_price);

            $query = "SELECT * FROM point WHERE client_id='$user_id'";
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

            if($user_point_sum < $project_price){
                ?>
                <script>
                var result = confirm('ポイント不足です。ポイントを購入しますか？');
 
                if(result == true) {
                <?php
                header("Location: chat.php?project_id=$project_id");
                ?>
                } else {
                <?php
                header("Location: point.php");
                ?>
                }
                </script>
                <?php
                exit;
            }

            $query = "UPDATE order_receive SET done_flag = 3 WHERE id = '$project_id' ";

            $result = $mysqli->query($query);
                if(!$result){
                    print('クエリーが失敗しました。' . $mysqli->error);
                }
            else{
                date_default_timezone_set('Asia/Tokyo');
                $date = date("Y/m/d");
                $time = date("H:i:s");
                $query = "INSERT INTO point(client_id,editer_id,project_id,point_num,insert_date,insert_time,add_or_delete) VALUES ('$user_id','$editer_id','$get_project_id','$project_price','$date','$time', 0)";

                $result = $mysqli->query($query);
                    if(!$result){
                        print('クエリーが失敗しました。' . $mysqli->error);
                    }
                else{
                    header("Location: chat.php?project_id=$project_id");
                }
            }
    
        }
    if(isset($_POST['project_delivery'])) {
        $project_id = $mysqli->real_escape_string($_POST['project_delivery']);

        echo $project_id;

        $query = "UPDATE order_receive SET done_flag = 4 WHERE id = $project_id ";
        
        $result = $mysqli->query($query);
        if(!$result){
            print('クエリーが失敗しました。' . $mysqli->error);
        }
        else{
            header("Location: chat.php?project_id=$project_id");
        }
    }

?>

<!DOCTYPE HTML>
<br lang="ja">

<head>
  <meta charset="UTF-8">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $project_project_name?> - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/chat.css">
</head>

<?php include('header.php'); ?>
<main>
<?php
if($project_done_flag == 0){
    $switch_progress0 = "isActive";
}
else if($project_done_flag == 1){
    $switch_progress1 = "isActive";
}
else if($project_done_flag == 2){
    $switch_progress2 = "isActive";
}
else if($project_done_flag == 3){
    $switch_progress3 = "isActive";
}
else if($project_done_flag == 4){
    $switch_progress4 = "isActive";
}
else if($project_done_flag == 5){
    $switch_progress5 = "isActive";
}
else if($project_done_flag == 6){
    $switch_progress6 = "isActive";
}
?>
<div id = "progress_bar">
<div class="Group">
   <div class="Group-Bar"></div>
   <div class="Group-Item <?php echo $switch_progress0; ?>">
     <div class="Group-Item-CircleOuter Circle Shapeborder <?php echo $switch_progress0; ?>">
       <div class="Group-Item-CircleInner Circle Shapeborder <?php echo $switch_progress0; ?>"></div>
     </div>
     <p class="Group-Item-Text">未承認</p>
   </div>
   <div class="Group-Item <?php echo $switch_progress1; ?>">
     <div class="Group-Item-CircleOuter Circle Shapeborder <?php echo $switch_progress1; ?>">
       <div class="Group-Item-CircleInner Circle Shapeborder <?php echo $switch_progress1; ?>"></div>
     </div>
     <p class="Group-Item-Text">クライアント承認</p>
   </div>
   <div class="Group-Item <?php echo $switch_progress2; ?>">
     <div class="Group-Item-CircleOuter Circle Shapeborder <?php echo $switch_progress2; ?>">
       <div class="Group-Item-CircleInner Circle Shapeborder <?php echo $switch_progress2; ?>"></div>
     </div>
     <p class="Group-Item-Text">編集者承認</p>
   </div>
   <div class="Group-Item <?php echo $switch_progress3; ?>">
      <div class="Group-Item-CircleOuter Circle Shapeborder <?php echo $switch_progress3; ?>">
        <div class="Group-Item-CircleInner Circle Shapeborder <?php echo $switch_progress3; ?>"></div>
      </div>
      <p class="Group-Item-Text">支払い完了</p>
   </div>
   <div class="Group-Item <?php echo $switch_progress4; ?>">
     <div class="Group-Item-CircleOuter Circle Shapeborder <?php echo $switch_progress4; ?>">
       <div class="Group-Item-CircleInner Circle Shapeborder <?php echo $switch_progress4; ?>"></div>
     </div>
     <p class="Group-Item-Text">納品</p>
   </div>
   <div class="Group-Item <?php echo $switch_progress5; ?>">
     <div class="Group-Item-CircleOuter Circle Shapeborder <?php echo $switch_progress5; ?>">
       <div class="Group-Item-CircleInner Circle Shapeborder <?php echo $switch_progress5; ?>"></div>
     </div>
     <p class="Group-Item-Text">評価</p>
   </div>
   <div class="Group-Item <?php echo $switch_progress6; ?>">
     <div class="Group-Item-CircleOuter Circle Shapeborder <?php echo $switch_progress6; ?>">
       <div class="Group-Item-CircleInner Circle Shapeborder <?php echo $switch_progress6; ?>"></div>
     </div>
     <p class="Group-Item-Text">完了</p>
   </div>
 </div>
 <!-- 「isActive」クラスを付与 -->
<?php
if($user_authority == 0 && $project_done_flag == 0){
    ?>
    <h1>条件を承認してください</h1>
    <?php
}
else if($user_authority == 0 && $project_done_flag == 1){
    ?>
    <h1>編集者承認待ち</h1>
    <?php
}
else if($user_authority == 0 && $project_done_flag == 2){
    ?>
    <h1>支払いを行なってください</h1>
    <?php
}
else if($user_authority == 0 && $project_done_flag == 3){
    ?>
    <h1>納品待ち</h1>
    <?php
}
else if($user_authority == 0 && $project_done_flag == 4){
    ?>
    <h1>評価を行なってください</h1>
    <?php
}
else if($user_authority == 0 && $project_done_flag == 5){
    ?>
    <h1>編集者評価待ち</h1>
    <?php
}
else if($user_authority == 0 && $project_done_flag == 6){
    ?>
    <h1>完了</h1>
    <?php
}

if($user_authority == 1 && $project_done_flag == 0){
    ?>
    <h1>クライアント承認待ち</h1>
    <?php
}
else if($user_authority == 1 && $project_done_flag == 1){
    ?>
    <h1>条件を承認してください</h1>
    <?php
}
else if($user_authority == 1 && $project_done_flag == 2){
    ?>
    <h1>クライアント支払い待ち</h1>
    <?php
}
else if($user_authority == 1 && $project_done_flag == 3){
    ?>
    <h1>仕事を開始してください</h1>
    <?php
}
else if($user_authority == 1 && $project_done_flag == 4){
    ?>
    <h1>クライアント評価待ち</h1>
    <?php
}
else if($user_authority == 1 && $project_done_flag == 5){
    ?>
    <h1>評価を行なってください</h1>
    <?php
}
else if($user_authority == 1 && $project_done_flag == 6){
    ?>
    <h1>完了</h1>
    <?php
}
?>
</div>

    <table>
        <tr>
            <th>プロジェクト名</th>
            <td><?php echo $project_project_name; ?></td>
        </tr>
        <tr>
            <th>状態</th>
            <?php
            if($project_done_flag == 0){
                ?>
                <td>未承認</td>
                <?php
            }
            else if($project_done_flag == 1){
                ?>
                <td>クライアント価格承認</td>
                <?php
            }
            else if($project_done_flag == 2){
                ?>
                <td>編集者承認</td>
                <?php
            }
            else if($project_done_flag == 3){
                ?>
                <td>支払い完了</td>
                <?php
            }
            else if($project_done_flag == 4){
                ?>
                <td>クライアント評価待ち</td>
                <?php
            }
            else if($project_done_flag == 5){
                ?>
                <td>編集者評価待ち</td>
                <?php
            }
            else if($project_done_flag == 6){
                ?>
                <td>完了</td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <th>単価</th>
            <td>¥<?php echo $project_price_jpy;?></td>
        </tr>
    </table>
    <?php
    if($user_authority == 1 && $project_done_flag == 0){
        ?>
        <form method="post" action=project_update.php>
            <button type="submit" id="btn" class="btn" name="project_id" value="<?php echo $get_project_id; ?>">変更</button>
        </form>
    <?php
    }

    if($user_authority == 0 && $project_done_flag == 0){
        ?>
        <form method="post">
            <button type="submit" id="btn" class="btn" name="project_condition_approval_client" value="<?php echo $get_project_id; ?>">条件承認</button>
        </form>
    <?php
    }
    if($user_authority == 1 && $project_done_flag == 1){
        ?>
        <form method="post">
            <button type="submit" id="btn" class="btn" name="project_condition_approval_editer" value="<?php echo $get_project_id; ?>">条件承認</button>
        </form>
    <?php
    }
    if($user_authority == 0 && $project_done_flag == 2){
        ?>

        <form method="post">
            <button onclick="return confirm('<?php echo $project_price_jpy;?>ptを消費します。よろしいですか？')"  type="submit" id="btn" class="btn" name="pay" value="<?php echo $project_price; ?>">仮払い</button>
        </form>
        <!-- <form action="pay/charge.php" method="POST">
        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="pk_test_51Id7tRLmKKxwC25IP4nyoDIOlH7GugnADG2vY0rbNqrcSXtMRHFm2IOl2fPT3MeAiCtJuzkrl80ujZQdC9aIP8a500PAthfNlq"
        data-amount=<?php echo $project_price ?>;
        data-name="この商品の料金は<?php echo $project_price ?>円です"
        data-description="マッチング料金"
        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
        data-locale="auto"
        data-allow-remember-me="false"
        data-label="支払い"
        data-currency="jpy">
        </script>
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
        <input type="hidden" name="amount" value="<?php echo $project_price; ?>">
    </form> -->
    <?php
    }
    if($user_authority == 1 && $project_done_flag == 3){
        ?>
        <form method="post">
            <button type="submit" id="btn" class="btn" name="project_delivery" value="<?php echo $get_project_id; ?>">納品</button>
        </form>
    <?php
    }
    if($user_authority == 0 && $project_done_flag == 4){
        ?>
        <form method="post" action="evaluation.php">
            <input type="hidden" name="project_id" value="<?php echo $get_project_id; ?>">
            <input type="hidden" name="direction" value="0">
            <button type="submit" id="btn" class="btn" name="project_delivery_verification" value="<?php echo $get_project_id; ?>">評価</button>
        </form>
    <?php
    }
    if($user_authority == 1 && $project_done_flag == 5){
        ?>
        <form method="post" action="evaluation.php">
            <input type="hidden" name="project_id" value="<?php echo $get_project_id; ?>">
            <input type="hidden" name="direction" value="1">
            <button type="submit" id="btn" class="btn" name="project_delivery_verification" value="<?php echo $get_project_id; ?>">評価</button>
        </form>
    <?php
    }
    ?>
    <div class="container chat">
    <?php
    $query = "SELECT * FROM chat WHERE chat_id='$project_id'";
    $result = $mysqli->query($query);

    if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
    exit();
    }

    // ユーザー情報の取り出し
    while ($row = $result->fetch_assoc()) {
        $chat_id = $row['id'];
        $chat_client_id = $row['client_id'];
        $chat_editer_id = $row['editer_id'];
        $chat_text = $row['text'];
        $chat_send_flag = $row['send_flag'];
        $chat_read_flag = $row['read_flag'];
        $chat_send_time = $row['send_time'];

        // クリエイターからクライアントのメッセージ flag=0
        if($chat_send_flag == 0 && $chat_client_id == $user_id){
            $query2 = "UPDATE chat SET read_flag = 1 WHERE chat_id=$project_id AND send_flag = 0 AND client_id = $user_id";
            $result2 = $mysqli->query($query2);
            if(!$result2){
                print('クエリーが失敗しました。' . $mysqli->error);
            }
        }
        // クライアントからクリエイターのメッセージ flag=1
        else if($chat_send_flag == 1 && $chat_editer_id == $user_id){
            $query2 = "UPDATE chat SET read_flag = 1 WHERE chat_id=$project_id AND send_flag = 1 AND editer_id = $user_id";
            $result2 = $mysqli->query($query2);
            if(!$result2){
                print('クエリーが失敗しました。' . $mysqli->error);
            }
        }
            
        if($user_authority == 0){
            if($chat_send_flag == 0){
                ?>
                <!-- 相手からのメッセージ -->
                <div class="kaiwa">
                    <figure class="kaiwa-img-left">
                        <a href="#"><img src="profile_img/editer/<?php echo $editer_image_name; ?>"></a>
                    </figure>
                    <div class="kaiwa-text-right">
                        <p class="kaiwa-text"><?php echo $chat_text; ?></p>
                    </div>
                    <div class="send_time">
                        <p class="send_time-text">
                            <?php echo $chat_send_time?>
                        </p>
                    </div>
                </div>
                <?php
            }
            else if($chat_send_flag == 1){
            ?>
                <!-- 自分からのメッセージ -->
                <div class="kaiwa">
                    <figure class="kaiwa-img-right">
                        <a href="#"><img src="profile_img/client/<?php echo $client_image_name; ?>"></a>
                    </figure>
                    <div class="kaiwa-text-left">
                        <p class="kaiwa-text"><?php echo $chat_text; ?></p>
                    </div>
                    <div class="already_read">
                        <p style="height: 4px;" class="already_read-text">
                            <?php echo $chat_send_time?>
                        </p>
                        <?php
                        if($chat_read_flag == 1){
                            ?>
                            <p class="already_read-text">
                                既読
                            </p>
                            <?php
                        }
                       ?>
                    </div>
                </div>
            <?php
            }
        }
        else if($user_authority == 1){
            if($chat_send_flag == 1){
                ?>
                <!-- 相手からのメッセージ -->
                <div class="kaiwa">
                    <figure class="kaiwa-img-left">
                    <a href="#"><img src="profile_img/client/<?php echo $client_image_name; ?>"></a>
                    </figure>
                    <div class="kaiwa-text-right">
                        <p class="kaiwa-text"><?php echo $chat_text; ?></p>
                    </div>
                    <div class="send_time">
                        <p class="send_time-text">
                            <?php echo $chat_send_time?>
                        </p>
                    </div>
                </div>
                <?php
            }
            else if($chat_send_flag == 0){
            ?>
                <!-- 自分からのメッセージ -->
                <div class="kaiwa">
                    <figure class="kaiwa-img-right">
                        <a href="#"><img src="profile_img/editer/<?php echo $editer_image_name; ?>"></a>
                    </figure>
                    <div class="kaiwa-text-left">
                        <p class="kaiwa-text"><?php echo $chat_text; ?></p>
                    </div>
                    <div class="already_read">
                        <p style="height: 4px;" class="already_read-text">
                            <?php echo $chat_send_time?>
                        </p>
                        <?php
                        if($chat_read_flag == 1){
                            ?>
                            <p class="already_read-text">
                                既読
                            </p>
                            <?php
                        }
                       ?>
                    </div>
                </div>
            <?php
            }
        }
    }
    ?>
</div>
<div id="post_btn_area" class="container">
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="send_id" value="<?php echo $user_id ;?>">
        <input type="hidden" name="client_id" value="<?php echo $client_id ;?>">
        <input type="hidden" name="editer_id" value="<?php echo $editer_id ;?>">
        <div class="form-group">
            <div id="text">
                <textarea class="form-control message_area" rows="10" name="message"></textarea>
            </div>
            <button type="submit" name="submit_message" value="submit_message"
                class="btn btn-primary btn-lg btn-block post_btn container" style="margin-top: 2%;">送信
            </button>
        </div>
    </form>
</div>
<form method="post">
    <button type="submit" id="btn" class="btn btn_red">更新する</button>
</form>

<script>
let btn = document.getElementById('btn');
btn.addEventListener('click', function(){
    window.setTimeout(function(){
        // alert('時間切れです');
        location.reload();
    }, 2000);
});
</script>
</main>

</body>
<?php include('footer.php'); ?>
</html>

<?php
if(isset($_POST['submit_message'])) {
    $client_id = $mysqli->real_escape_string($_POST['client_id']);
    $editer_id = $mysqli->real_escape_string($_POST['editer_id']);
    $text = $mysqli->real_escape_string($_POST['message']);
    $send_id = $mysqli->real_escape_string($_POST['send_id']);
    if($send_id == $client_id){
        $send_flag = 1;
    }
    else if($send_id == $editer_id){
        $send_flag = 0;
    }
    date_default_timezone_set('Asia/Tokyo');
    $send_time = date("H:i");

    $query = "INSERT INTO chat(client_id,editer_id,text,send_flag,read_flag,send_time,chat_id) VALUES('$client_id','$editer_id','$text','$send_flag',0,'$send_time','$get_project_id')";
          
    $result = $mysqli->query($query);
      if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
      }
      else{
        $query2 = "SELECT * FROM user WHERE id = '$editer_id'";
          
        $result2 = $mysqli->query($query2);
          if (!$result2) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          while ($row2 = $result2->fetch_assoc()) {
            $editer_email = $row2['email'];
          }

        $query3 = "SELECT * FROM user WHERE id = '$client_id'";
          
        $result3 = $mysqli->query($query3);
          if (!$result3) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          while ($row3 = $result3->fetch_assoc()) {
            $client_email = $row3['email'];
          }

          $query4 = "SELECT * FROM editer_prof WHERE user_id = '$editer_id'";
          
        $result4 = $mysqli->query($query4);
          if (!$result4) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          while ($row4 = $result4->fetch_assoc()) {
            $editer_username = $row4['username'];
          }

        $query5 = "SELECT * FROM client_prof WHERE user_id = '$client_id'";
          
        $result5 = $mysqli->query($query5);
          if (!$result5) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          while ($row5 = $result5->fetch_assoc()) {
            $client_username = $row5['username'];
          }

        if($send_flag == 0){
            // クライアント向けメール
            $host_email = "info@youcast.jp";
            $to = $client_email;

            mb_language("Japanese"); 
            mb_internal_encoding("UTF-8");
            $subject = "【YouCast】新着メッセージのお知らせ"; // 題名 
            $body .= "YouCastをご利用いただき、誠にありがとうございます。";
            $body .= "\n";
            $body .= "新着メッセージが届いています。";
            $body .= "\n\n";
            $body .= "___________________________\n";
            $body .= "メッセージ情報\n\n";
            $body .= "送信者氏名：";
            $body .= $editer_username;
            $body .= "\n";
            $body .= "内容：";
            $body .= $text;
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
        
            $header = "From: $host_email";
            
            mb_send_mail($to, $subject, $body, $header);
        }

        else if($send_flag == 1){
            // 編集者向けメール
            $host_email = "info@youcast.jp";
            $to = $editer_email;

            mb_language("Japanese"); 
            mb_internal_encoding("UTF-8");
            $subject = "【YouCast】新着メッセージのお知らせ"; // 題名 
            $body .= "YouCastをご利用いただき、誠にありがとうございます。";
            $body .= "\n";
            $body .= "新着メッセージが届いています。";
            $body .= "\n\n";
            $body .= "___________________________\n";
            $body .= "メッセージ情報\n\n";
            $body .= "送信者氏名：";
            $body .= $client_username;
            $body .= "\n";
            $body .= "内容：";
            $body .= $text;
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
        
            $header = "From: $host_email";
            
            mb_send_mail($to, $subject, $body, $header);
        }

        header("Location: chat.php?project_id=$get_project_id");
      }
}
?>