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



?>

<!DOCTYPE HTML>
<br lang="ja">

<head>
  <meta charset="UTF-8">
  <!--Import materialize.css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ポイント購入 - YouCast</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/chat.css">
  <style>
    table{
      /* border: 4px solid #1E82DB;
      border-radius: 25px; */
      border-collapse:collapse
    }
    table td{
      background: #fff;  
    }
      table tr{
        /* border:none; */
        border:2px solid #dedede;
        border-left:none;
        border-right:none;
      }
      table tr:first-child {
          border-top:none;
      }
      table td{
        width:auto !important;
      }
      .difference{
        background: #d85f5f;
        color:#fff;
        padding:5px;
        border-radius: 25px;
      }
  </style>
</head>

<?php include('header.php'); ?>
<main>
    <p style="margin-top:0; text-align:left; font-weight:bold;">ポイント購入</p>
          <hr style="border-top: 3px solid #1E82DB !important; margin-top:-10px;">
    <table>
        <?php
        // ユーザーIDからユーザー名を取り出す
        $query = "SELECT * FROM basic_point";
        $result = $mysqli->query($query);

        if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
        }

        // ユーザー情報の取り出し
        while ($row = $result->fetch_assoc()) {
        $point = $row['point'];
        $price = $row['price'];
        ?>
        <tr>
            <?php 
            $project_price = $price; 
            $project_price_number = number_format($project_price);
            $point_num = $point;
            $point_num_number = number_format($point_num);
            $difference = $point_num - $project_price;
            ?>
            <td><img style="width:30%; margin:0 35%;" src="image/point.png"></td>
            <td><p class="point_num"><?php echo $point_num_number; ?>pt</p></td>
            <?php
            if($difference != 0){
              ?>
              <td><p class="difference"><?php echo $difference; ?>ptお得</p></td>
              <?php
            }
            else{
              ?>
              <td></td>
              <?php
            }
            ?>
            <td><?php echo $project_price_number; ?>円</td>
            <td>
                <form action="pay/point_charge.php" method="POST">
                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="pk_live_51Id7tRLmKKxwC25ITYZ2dIimXYMCnMxEAlILBmh9tJ7rFt2SBQ0nnvrKL249GfKJ7AxFtfgrFWJKyqno8DBs0GH700sNXLwWQO"
                    data-amount=<?php echo $project_price ?>;
                    data-name="この商品の料金は<?php echo $project_price ?>円です"
                    data-description="マッチング料金"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                    data-locale="auto"
                    data-allow-remember-me="false"
                    data-label="支払い"
                    data-currency="jpy">
                    </script>
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="point_num" value="<?php echo $point_num; ?>">
                    <input type="hidden" name="amount" value="<?php echo $project_price; ?>">
                </form>
            </td>
        </tr>

        <?php
        }
        ?>
    </table>
    
</main>

</body>
<footer>
</footer>
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
    $send_time = date("H:i");

    $query = "INSERT INTO chat(client_id,editer_id,text,send_flag,read_flag,send_time,chat_id) VALUES('$client_id','$editer_id','$text','$send_flag',0,'$send_time','$get_project_id')";
          
    $result = $mysqli->query($query);
      if (!$result) {
        print('クエリーが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
      }
      else{
        header("Location: chat.php?project_id=$get_project_id");
      }
}
?>