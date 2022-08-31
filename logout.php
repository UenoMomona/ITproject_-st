<?php
$window_title = "logout";
require_once( 'template/logincheck.php' );
require_once( 'class/db.php' );
$DB = new db();

$sql = "DELETE FROM login_checks WHERE verify_key = :now_key;";
// バインドしたい値を配列にする
$bind_params = array();
$bind_params[':now_key'] = $_SESSION['now_key'];
// sqlとバインド変数を渡す
$DB = new db();
$DB->setSQL($sql);
$DB->setBind($bind_params);
// SQLを実行する
$DB->execute();
$_SESSION = array();
session_destroy();
$user_id = "";
include_once( 'template/header.php' );
?>
<div class="wrapper">
  <p>ログアウトが完了しました</p>
</div>
<div class="wrapper-nocolor">
  <a href="login.php" class="btn">ログイン</a>
</div>