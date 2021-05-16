<?php

ob_start();
session_start();
if( isset($_SESSION['user']) != "") {
  // ログイン済みの場合はリダイレクト
  header("Location: home.php");
}
// DBとの接続
include_once 'dbconnect.php';

      $_SESSION['user'] = 34;
      header("Location: home.php");
?>