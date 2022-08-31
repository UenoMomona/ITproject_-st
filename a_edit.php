<?php
$window_title = "a_edit";
require_once( 'template/logincheck.php' );
require_once( 'class/function.php' );
$functions = new functions();
require_once( 'class/db.php' );
$DB = new db();
require_once( 'class/detail.php' );
$detail = new detail();

// getで送られてきたらconfirm
// postで送られてきていたらcomplete
// なにも送られてきていなかったらform　を表示する

// 編集しようとしている質問の質問者と今ログインしているユーザーが同じかを確かめてからこの画面を表示する
if(isset($_GET['q_id']) && isset($_GET['a_id'])){
  $q_id = $_GET['q_id'];
  $a_id = $_GET['a_id'];
}
if(isset($_POST['q_id']) && isset($_POST['a_id'])){
  $q_id = $_POST['q_id'];
  $a_id = $_POST['a_id'];
}
// 元の解答と解答先の質問をとってくる
if(!empty($q_id) && !empty($a_id)){
  $result = $detail->detail($q_id);
  $a_result = $detail->a_detail($a_id);
}else{
  $_SESSION['error'] = "編集する解答を選択してください";
  header('Location: list.php?type=1');
}
if (strcmp($a_result['user_id'], $user_id) == 1){
  $_SESSION['error'] = "エラーが発生しました";
  header('Location: index.php');
}

if(!empty($_GET['q_id']) && !empty($_GET['a_id']) && !empty($_GET['answer']) ){
  // confirm
  $answer = $_GET['answer'];
  include_once( 'template/header.php' );
  ?>
  <div class="wrapper">
    <h2>編集する解答の元の質問</h2><hr>
    <div class="post">
      <ul>
        <div class="flex-detail">
          <li class="detail-title"><?php echo $result['title']; ?></li>
          <li class="detail-grade"><?php echo $functions->gradeJudge($result['grade']); ?></li>
        </div>
        <li class="detail-body"><?php echo $result['body']; ?></li>
      </ul>
    </div>
  
    <h2>編集前の解答</h2><hr>
    <div class="post">
      <ul>
        <li class="detail-body"><?php echo $a_result['answer']; ?></li>
      </ul>
    </div>
    <h2>編集後の解答</h2><hr>
    <div class="post">
      <ul>
        <li class="detail-body"><?php echo $answer; ?></li>
      </ul>
    </div>
    <div class="flex">
      <form action="a_edit.php" method="POST" class="hidden">
        <input type="hidden" name="answer" id="" value="<?php echo $answer; ?>">
        <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
        <input type="hidden" name="a_id" id="" value="<?php echo $a_id; ?>">
        <input type="submit" class="button" value="投稿">
      </form>
      <form action="a_edit.php" method="POST" class="hidden">
        <input type="hidden" name="answer" id="" value="<?php echo $answer; ?>">
        <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
        <input type="hidden" name="a_id" id="" value="<?php echo $a_id; ?>">
        <input type="hidden" name="back" id="" value="1">
        <input type="submit" class="button" value="戻る">
      </form>
    </div>

  </div>
  </div>
  <?php
}elseif(!empty($_POST['a_id']) && !empty($_POST['answer']) && (!isset($_POST['back']) && !isset($_POST['form']) ) ){
  // complete
  $answer = $_POST['answer'];

  $sql = "UPDATE answers SET answer = :answer WHERE id = :a_id;";
  // バインドしたい値を配列にする
  $bind_params = array();
  $bind_params[':answer'] = $answer;
  $bind_params[':a_id'] = $a_id;
  // sqlとバインド変数を渡す
  $DB->setSQL($sql);
  $DB->setBind($bind_params);
  // SQLを実行する
  $DB->execute();
  include_once( 'template/header.php' );
  ?>
  <div class="wrapper">
    <p>質問の編集が完了しました</p>
    <div class="flex">
      <a href="index.php" class="btn1">トップへ</a>
      <a href="list.php?type=1" class="btn1">質問一覧へ</a>
    </div>
  </div>
  <?php
}elseif( ( !empty($_POST['q_id']) && !empty($_POST['a_id']) ) && ( isset($_POST['back']) || isset($_POST['form']) ) ){
  // form
  if(isset($_POST['back'])){
    $answer = $_POST['answer'];
  }else{
    $answer = $a_result['answer'];
  }
  include_once( 'template/header.php' );
?>
  <div class="wrapper">
    <h2>編集する解答の元の質問</h2><hr>
    <div class="post">
      <ul>
        <div class="flex-detail">
          <li class="detail-title"><?php echo $result['title']; ?></li>
          <li class="detail-grade"><?php echo $functions->gradeJudge($result['grade']); ?></li>
        </div>
        <li class="detail-body"><?php echo $result['body']; ?></li>
      </ul>
    </div>
    <h2>編集する解答</h2>
    <form action="a_edit.php" method="GET" class="new_form">
      <textarea name="answer" class="answer"><?php
        if(isset($answer)){
          echo $answer;
        }
        ?></textarea>
      <input type="hidden" name="a_id" value="<?php echo $a_id; ?>">
      <input type="hidden" name="q_id" value="<?php echo $q_id; ?>">
      <input type="submit" class="button" value="確認">
      </form>
    </div>
  </div>
    <div class="wrapper-nocolor">
      <div class="flex">
        <a href="index.php" class="btn1">トップへ戻る</a>
        <a href="detail.php?q_id=<?php echo $q_id; ?>" class="btn1">戻る</a>
      </div>
    </div>
  </body>
</html>
<?php
}else{
  $_SESSION['error'] = "どの解答を編集するか選んでください";
  header('Location: list.php?type=1');
}