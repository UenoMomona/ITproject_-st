<?php
$window_title = "q_delete";
require_once( 'template/logincheck.php' );
require_once( 'class/function.php' );
$functions = new functions();
require_once( 'class/db.php' );
$DB = new db();
require_once( 'class/detail.php' );
$detail = new detail();


// 削除しようとしている質問の質問者と今ログインしているユーザーが同じかを確かめてからこの画面を表示する
if(isset($_POST['q_id']) && !isset($_POST['form'])){
  $q_id = $_POST['q_id'];
  // 質問を削除する
  $result = $detail->detail($q_id);
  $result_user_id = $result['user_id'];
  $solve_check = $result['solve_check'];

  if( strcmp($result_user_id, $user_id) == 0){
    try{
      $DB->beginTransaction();
      $sql = "DELETE FROM questions WHERE id = :q_id;";
      // バインドしたい値を配列にする
      $bind_params = array();
      $bind_params[':q_id'] = $q_id;
      // sqlとバインド変数を渡す
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();
      $sql = "DELETE FROM answers WHERE question_id = :q_id;";
      // バインドしたい値を配列にする
      $bind_params = array();
      $bind_params[':q_id'] = $q_id;
      // sqlとバインド変数を渡す
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();
      $DB->commit();
      include_once( 'template/header.php' );
      echo "<div class='wrapper'><p>削除が完了しました</p>";
    }catch(Exception $e){
      $DB->rollback();
      $_SESSION['error'] = "質問の削除ができませんでした。" . $e->getMessage();
      header('Location: index.php');
      exit;
    }
  }else{
    $_SESSION['error'] = "質問を削除できませんでした";
    exit;
  }
}elseif(isset($_POST['q_id']) && isset($_POST['form'])){
  $q_id = $_POST['q_id'];
  // 元の質問の詳細をとってくる
  $result = $detail->detail($q_id);
  if (strcmp($result['user_id'], $user_id) == 1){
    $_SESSION['error'] = "エラーが発生しました";
    header('Location: index.php');
    exit;
  }
  if(isset($result)){
    include_once( 'template/header.php' );
    ?>
    <div class="wrapper">
      <p>本当にこの質問を削除してもいいですか？</p>
      <div class="post">
        <ul>
          <div class="flex-detail">
            <li class="detail-title"><?php echo $result['title'] ?></li>
            <li class="detail-grade"><?php echo $functions->gradeJudge($result['grade']) ?></li>
            <li class="detail-solve"><?php echo $functions->solveCheck($result['solve_check']) ?></li>
          </div>
          <li class="detail-body"><?php echo $result['body'] ?></li>
        </ul>
      </div>
        <form action="" method="post">
          <input type="hidden" name="q_id" value="<?php echo $q_id; ?>">
          <input type="submit" class="button" value="はい">
          <a href="detail.php?q_id=<?php echo $q_id; ?>" class="btn1">キャンセル</a>
        </form>
      </div>
    </div>
      
<?php
  }
}else{
  // q_idが送られてきていない場合はどの詳細を表示すべきかわからないので一覧に戻す
  // 本当はさっきいたところに戻りたいけどどこから来たのかわからないから無理かな
  $_SESSION['error'] = "削除する質問を指定してください";
  header('Location: list.php?type=1');
}

?>
<div class="wrapper-nocolor">
  <div class="flex-none">
    <a href="list.php?type=1" class="btn1">質問一覧へ戻る</a>
    <a href="index.php" class="btn1">トップへ</a>
  </div>
</div>
