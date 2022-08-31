<?php
$window_title = "a_delete";
require_once( 'template/logincheck.php' );
require_once( 'class/function.php' );
$functions = new functions();
require_once( 'class/db.php' );
$DB = new db();
require_once( 'class/detail.php' );
$detail = new detail();


// 削除しようとしている質問の質問者と今ログインしているユーザーが同じかを確かめてからこの画面を表示する
if(isset($_POST['a_id']) && isset($_POST['form'])){
  $q_id = $_POST['q_id'];
  $a_id = $_POST['a_id'];
  // 元の質問の詳細をとってくる
  $result = $detail->detail($q_id);
  $a_result = $detail->a_detail($a_id);
  if (strcmp($a_result['user_id'], $user_id) == 1){
    $_SESSION['error'] = "エラーが発生しました";
    header('Location: index.php');
    exit;
  }
  if(isset($result) && isset($a_result)){
    include_once( 'template/header.php' );
    ?>
    <div class="wrapper">
      <p>本当にこの解答を削除してもいいですか？</p>
      <div class="post">
        <ul>
          <li class="detail-username"><?php echo $result['username']; ?></li>
          <div class="flex-detail">
            <li class="detail-title"><?php echo $result['title'] ?></li>
            <li class="detail-grade"><?php echo $functions->gradeJudge($result['grade']) ?></li>
            <li class="detail-solve"><?php echo $functions->solveCheck($result['solve_check']) ?></li>
          </div>
          <li class="detail-body"><?php echo $result['body'] ?></li>
        </ul>
      </div>
      <p>解答</p>
      <div class="post">
        <ul>
          <li class="detail-body"><?php echo $a_result['answer'] ?></li>
        </ul>
      </div>
      <div class="flex">
        <form action="" method="post" class="hidden-form">
          <input type="hidden" name="a_id" value="<?php echo $a_id; ?>">
          <input type="hidden" name="q_id" value="<?php echo $q_id; ?>">
          <input type="submit" class="button" value="はい">
        </form>
          <a href="detail.php?q_id=<?php echo $q_id; ?>" class="mini-btn">キャンセル</a>
      </div>

    </div>
<?php
  }
}elseif(isset($_POST['a_id'])){
  include_once( 'template/header.php' );
  $a_id = $_POST['a_id'];
  $q_id = $_POST['q_id'];
  // 質問を削除する
    $sql = "DELETE FROM answers WHERE id = :a_id;";
    // バインドしたい値を配列にする
    $bind_params = array();
    $bind_params[':a_id'] = $a_id;
    // sqlとバインド変数を渡す
    $DB->setSQL($sql);
    $DB->setBind($bind_params);
    // SQLを実行する
    $DB->execute();
    echo "<div class='wrapper'><p>削除が完了しました</p>";
    echo "<a href='detail.php?q_id=" . $q_id ."' class='btn'>質問詳細へ戻る</a></div>";
}else{
  // q_idが送られてきていない場合はどの詳細を表示すべきかわからないので一覧に戻す
  // 本当はさっきいたところに戻りたいけどどこから来たのかわからないから無理かな
  $_SESSION['error'] = "削除する解答を指定してください";
  header('Location: list.php?type=1');
}

?>
<div class="wrapper-nocolor">
  <div class="flex">
    <a href="list.php?type=1" class="btn1">質問一覧へ戻る</a>
    <a href="index.php" class="btn1">トップへ</a>
  </div>
</div>
