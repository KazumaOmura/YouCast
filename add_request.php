<?php
ob_start();
session_start();
if(!isset($_SESSION['user']) != "") {
//   header("Location: index.php");
}
include_once 'dbconnect.php';
    $get_client_id = $mysqli->real_escape_string($_POST['post_client_id']);
    $get_editer_id = $mysqli->real_escape_string($_POST['post_editer_id']);
    $get_price = $mysqli->real_escape_string($_POST['post_price']);
    
    $query = "SELECT * FROM order_receive WHERE client_id = '$get_client_id' AND editer_id = '$get_editer_id'";
          
    $result = $mysqli->query($query);
    if(!$result){
      print('クエリーが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    
    while ($row = $result->fetch_assoc()) {
     $done_flag = $row['done_flag'];
     echo $done_flag;
     if($done_flag != 5){
      header("Location: profile.php?id=$get_editer_id");
      exit;
    }
  }

    $query = "INSERT INTO order_receive(client_id,editer_id,price,done_flag) VALUES('$get_client_id','$get_editer_id','$get_price',0)";
          
    $result = $mysqli->query($query);
      if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
      }

      else{
        $query2 = "SELECT * FROM user WHERE id = '$get_editer_id'";
          
        $result2 = $mysqli->query($query2);
          if (!$result2) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          while ($row2 = $result2->fetch_assoc()) {
            $editer_email = $row2['email'];
          }

        $query3 = "SELECT * FROM user WHERE id = '$get_client_id'";
          
        $result3 = $mysqli->query($query3);
          if (!$result3) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          while ($row3 = $result3->fetch_assoc()) {
            $client_email = $row3['email'];
          }

        $query4 = "SELECT * FROM editer_prof WHERE user_id = '$get_editer_id'";
          
        $result4 = $mysqli->query($query4);
          if (!$result4) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          while ($row4 = $result4->fetch_assoc()) {
            $editer_username = $row4['username'];
          }

        $query5 = "SELECT * FROM client_prof WHERE user_id = '$get_client_id'";
          
        $result5 = $mysqli->query($query5);
          if (!$result5) {
            print('クエリーが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          while ($row5 = $result5->fetch_assoc()) {
            $client_username = $row5['username'];
          }
          
        // クライアント向けメール
        $host_email = "info@youcast.jp";
        $to = $client_email;

        mb_language("Japanese"); 
        mb_internal_encoding("UTF-8");
        $subject = "【YouCast】案件発注のお知らせ"; // 題名 
        $body .= "YouCastをご利用いただき、誠にありがとうございます。";
        $body .= "\n";
        $body .= "発注が完了いたしましたのでお知らせいたします。\n";
        $body .= "下記リンクからメッセージを開始してください。\n";
        $body .= "https://youcast.jp/profile.php?id=";
        $body .= $get_editer_id;
        $body .= "\n\n";
        $body .= "___________________________\n";
        $body .= "発注情報\n\n";
        $body .= "発注先氏名：";
        $body .= $editer_username;
        $body .= "\n";
        $body .= "発注元氏名：";
        $body .= $client_username;
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

        // 編集者向けメール
        $host_email = "info@youcast.jp";
        $to = $editer_email;

        mb_language("Japanese"); 
        mb_internal_encoding("UTF-8");
        $subject = "【YouCast】案件受注のお知らせ"; // 題名 
        $body .= "YouCastをご利用いただき、誠にありがとうございます。";
        $body .= "\n";
        $body .= "クライアント様から案件の発注がありましたのでお知らせいたします。\n";
        $body .= "下記リンクからメッセージを開始してください。\n";
        $body .= "https://youcast.jp/profile.php?id=";
        $body .= $get_editer_id;
        $body .= "\n\n";
        $body .= "___________________________\n";
        $body .= "受注情報\n\n";
        $body .= "発注先氏名：";
        $body .= $editer_username;
        $body .= "\n";
        $body .= "発注元氏名：";
        $body .= $client_username;
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
        header("Location: profile.php?id=$get_editer_id");
      }
?>