<?php
$window_title = "signup";
session_start();
require_once( 'class/db.php' );
$DB = new db();

// ログインの情報が入っていたらログイン処理を行う
if(!empty($_POST['name']) && !empty($_POST['mail']) && !empty($_POST['password'])){
  $name = $_POST['name'];
  $mail = $_POST['mail'];
  $password = $_POST['password'];
  // passwordをハッシュ化する
  $password_hash = password_hash( $password, PASSWORD_DEFAULT);
  // 同じメールアドレスで登録している人がいないかを確認する
  $sql = 'SELECT id FROM users WHERE mail = :mail;';
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
  // 同じmailの人が見つからなかったらユーザーの情報を登録する
  if(empty($result)){
    try{
      $DB->beginTransaction();
      $sql = 'INSERT INTO users ( name, mail, password ) VALUES ( :name, :mail, :password );';
      // バインドしたい値を配列にする
      $bind_params = array();
      $bind_params[':name'] = $name;
      $bind_params[':mail'] = $mail;
      $bind_params[':password'] = $password_hash;
      // sqlとバインド変数を渡す
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();
      $user_id = $DB->getID();  
      // 新しい認証キー
      $new_key = uniqid(bin2hex(random_bytes(1)));
      // 有効期限を決める
      $expire_period = 30;
  
      $sql = "INSERT INTO login_checks ( user_id, verify_key, end_time ) VALUE ( :user_id, :new_key, CURRENT_TIMESTAMP + INTERVAL :expire_period MINUTE );";
      // バインドしたい値を配列にする
      $bind_params = array();
      $bind_params[':user_id'] = $user_id;
      $bind_params[':new_key'] = $new_key;
      $bind_params[':expire_period'] = $expire_period;
      // sqlとバインド変数を渡す
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();
      $DB->commit();
      $_SESSION['now_key'] = $new_key;
      // ログインが終わったらトップページにリダイレクトする
      // header( 'Location: index.php');
    }catch(Exception $e){
      $DB->rollback();
      $_SESSION['error'] = "ユーザー情報の登録できませんでした。" . $e->getMessage();
      header('Location: signup.php');
      exit;
    }
  }else{
    // 同じメールアドレスで登録している人がすでに存在する
    $_SESSION['error'] = "同一のメールアドレスがすでに登録されています";
  }
}
include_once( 'template/header.php' );
?>
<div class="wrapper">
  <div class="form_wrap">
    <h2 class="title">新規登録</h2>
    <hr>
    <form action="" method="post">
      <ul>
        <li>
          <label>名前</label>
          <input type="text" name="name" required>
        </li>
        <li>
          <label>メールアドレス</label>
          <input type="mail" name="mail" required>
        </li>
        <li>
          <label>パスワード</label>
          <input type="password" name="password" required>
        </li>
        <li>
          <input type="submit" value="新規登録" class="button">
        </li>
      </ul>
    </form>
  </div>
</div>

    <!-- ログインフォームに飛べるようにしておく -->
    <div class="wrapper-nocolor">
      <a href="login.php" class="btn">すでに登録済みの方はこちら</a>
    </div>
</body>
</html>
