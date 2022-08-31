<?php
$window_title = "user";
require_once( 'template/logincheck.php' );
include_once( 'template/header.php' );
require_once( 'class/db.php' );
$DB = new db();

// ユーザー情報をとってくる
$sql = "SELECT * FROM users WHERE id = :user_id;";
// バインドしたい値を配列にする
$bind_params = array();
$bind_params[':user_id'] = $user_id;
// sqlとバインド変数を渡す
$DB->setSQL($sql);
$DB->setBind($bind_params);
// SQLを実行する
$DB->execute();
// 結果を取得
$user_info = $DB->fetch();
?>
<div class="wrapper">
  <h2>登録情報</h2>
  <ul>
    <li>ユーザー名:<?php echo $user_info['name']; ?></li>
    <li>メールアドレス:<?php echo $user_info['mail']; ?></li>
  </ul>
</div>

<?php
$list_name = $user_info['name']. "さんの質問";

// $_GET['type'] = 4;
require_once( 'list.php' );