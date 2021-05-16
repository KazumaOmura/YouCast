<?php
ob_start();
session_start();
date_default_timezone_set('Asia/Tokyo');
$year_time = date("Y"); 
$month_time = date("m"); 
$day_time = date("d"); 
if(isset($_SESSION['user']) != "") {
  // ログイン済みの場合はリダイレクト
  header("Location: home.php");
}
// DBとの接続
include_once '../dbconnect.php';
?>
<!DOCTYPE HTML>
<html lang="ja">
<meta charset="utf-8" />

<head>
<meta charset="utf-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/price.css">
<link rel="stylesheet" href="../css/chat.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>優秀な動画編集者を探すならYouCast</title>

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





<body>
    <?php include('../header_index.php'); ?>
    <div class="Heading">
            <h2>YOU cast の料金</h2>
            <p>YouCast内のやりとりは全てポイント方式になっています。</p>
            <p>YouTubeクリエイターが購入したポイントで編集者にポイントの支払いをします</p>
    </div>
    <main>
        <div class="point_description">
        <p>YouCastのポイント価格について</p>

        </div>
        
<!-- ポイント出力 -->
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
            <td><img style="width:30%; margin:0 35%;" src="../image/point.png"></td>
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
        </tr>

        <?php
        }
        ?>
    </table>

        <div class="point_margin">
            <p>※依頼した金額の10%の手数料がかかります</p>
        </div>





    </main>
    <?php include('../footer.php'); ?>
</body>


</html>
