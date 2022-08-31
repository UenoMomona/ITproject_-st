<?php
$window_title = "login";
session_start();

require_once( 'class/db.php' );
$DB = new db();

// ログインの情報が入っていたらログイン処理を行う
if(!empty($_POST['mail']) && !empty($_POST['password'])){
  $mail = $_POST['mail'];
  $password = $_POST['password'];
  // こんなユーザーがいるかを確かめる
  $sql = 'SELECT * FROM users WHERE mail = :mail;';
  // バインドしたい値を配列にする
  $bind_params = array();
  $bind_params[':mail'] = $mail;
  // sqlとバインド変数を渡す
  $DB->setSQL($sql);
  $DB->setBind($bind_params);
  // SQLを実行する
  $DB->execute();
  // 結果を返す
  $result = $DB->fetch();
  // $resultに情報が登録されていたらそのpasswordと入力されたpasswordを比べてみる
  if(empty($result)){
    // ログイン情報が見つからなかった（入力されたmailが登録されていなかった場合）
    $_SESSION['error'] = "メールアドレスかパスワードが間違っています";
    header( 'Location: login.php' );
    exit;
  }else{
    // ユーザー情報がとってこれた場合
    if( !password_verify( $password, $result['password'])){
      // ここに入ってきた=パスワードが間違っている
      $_SESSION['error'] = "メールアドレスかパスワードが間違っています";
      header( 'Location: login.php' );
      exit;
    }
  }
  // ここまで来れている＝ちゃんとユーザー情報が合っていた
  // 新しい認証キー
  $new_key = uniqid(bin2hex(random_bytes(1)));
  // 有効期限を決める
  $expire_period = 30;
  $sql = "INSERT INTO login_checks ( user_id, verify_key, end_time ) VALUE ( :user_id, :new_key, CURRENT_TIMESTAMP + INTERVAL :expire_period MINUTE );";
  // バインドしたい値を配列にする
  $bind_params = array();
  $bind_params[':user_id'] = $result['id'];
  $bind_params[':new_key'] = $new_key;
  $bind_params[':expire_period'] = $expire_period;
  // sqlとバインド変数を渡す
  $DB->setSQL($sql);
  $DB->setBind($bind_params);
  // SQLを実行する
  $DB->execute();
  // 新しい認証キーをセッションに登録しておく
  $_SESSION['now_key'] = $new_key;
  header( 'Location: index.php');// ログインが終わったらトップページにリダイレクトする
  exit;
}
include_once( 'template/header.php' );
?>
<!-- ログイン情報が入っていなかったら入力画面を表示する  -->

<div class="wrapper">
  <div class="form_wrap">
    <h2 class="title">ログイン</h2>
    <hr>
      <form action="login.php" method="post">
        <ul>
          <li>
            <label>メールアドレス</label>
            <input class="login" type="mail" name="mail" required>
          </li>
          <li>
            <label>パスワード</label>
            <input class="login" type="password" name="password" minlength="6" required>
          </li>
          <li>
            <input type="submit" value="ログイン" class="button">
          </li>
        </ul>
      </form>
  </div>
</div>
<!-- サインアップしたことのなかった人向けに -->
<div class="wrapper-nocolor">
  <a href="signup.php" class="btn">新規登録はこちら</a>
</div>
</body>
</html>