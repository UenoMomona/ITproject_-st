<?php
$window_title = "a_new";
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

if(!empty($_GET['q_id']) && !empty($_GET['answer']) ){
  // confirm
  $q_id = $_GET['q_id'];
  $answer = $_GET['answer'];
  // 元の質問の詳細をとってくる
  $result = $detail->detail($q_id);
  include_once( 'template/header.php' );
  ?>
  <div class="wrapper">
    <h2>解答先の質問</h2>
    <div class="post">
    <ul>
    <div class="flex-detail">
      <li class="detail-title"><?php echo $result['title']; ?></li><br>
      <li class="detail-grade"><?php echo $functions->gradeJudge($result['grade']); ?><li>
    </div>
      <li class="detail-body"><?php echo $result['body']; ?></li>
    </ul>
  </div>
  </div>

  <div class="wrapper">
    <h2>解答の確認</h2>
    <div class="post">
      <ul>
        <h3>解答</h3>
        <li class="detail-body"><?php echo $answer; ?></li>
      </ul>
    </div>
        <form action="a_new.php" method="POST" class="hidden">
          <input type="hidden" name="answer" id="" value="<?php echo $answer; ?>">
          <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
          <input type="submit" class="button-right" value="投稿">
        </form>
  </div>
  <div class="wrapper-nocolor">
    <form action="a_new.php" method="POST" class="hidden">
      <input type="hidden" name="answer" id="" value="<?php echo $answer; ?>">
      <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
      <input type="hidden" name="back" id="" value="">
      <input type="submit" class="button" value="戻る">
    </form>
  </div>
  <?php
}elseif(!empty($_POST['answer']) && !empty($_POST['q_id']) && !isset($_POST['back'])){
  // complete
  $answer = $_POST['answer'];
  $q_id = $_POST['q_id'];

  $sql = 'INSERT INTO answers (user_id, question_id, answer) VALUES (:user_id, :question_id, :answer);';
  // バインドしたい値を配列にする
  $bind_params = array();
  $bind_params[':user_id'] = $user_id;
  $bind_params[':question_id'] = $q_id;
  $bind_params[':answer'] = $answer;
  // sqlとバインド変数を渡す
  $DB->setSQL($sql);
  $DB->setBind($bind_params);
  // SQLを実行する
  $DB->execute();
  include_once( 'template/header.php' );
  ?>
  <div class="wrapper">
    <p>質問の投稿が完了しました</p>
      <div class="flex">
        <a href="detail.php?q_id=<?php echo $q_id ?>" class="btn1">質問詳細へ戻る</a>
        <a href="index.php" class="btn1">トップへ</a>
        <a href="list.php?type=1" class="btn1">質問一覧へ</a>
    </div>
  </div>
  <?php
}elseif(isset($_GET['q_id']) || isset($_POST['q_id'])){
  if(isset($_POST['q_id'])){
    $q_id = $_POST['q_id'];
  }
  if(isset($_GET['q_id'])){
    $q_id = $_GET['q_id'];
  }

  // 元の質問の詳細をとってくる
  $result = $detail->detail($q_id);
  include_once( 'template/header.php' );
?>
<div class="wrapper">
  <h2>新規解答</h2>
  <h3>解答先の質問</h3>
  <div class="post">
    <ul>
    <div class="flex-detail">
      <li class="detail-title"><?php echo $result['title']; ?></li><br>
      <li class="detail-grade"><?php echo $functions->gradeJudge($result['grade']); ?><li>
    </div>
      <li class="detail-body"><?php echo $result['body']; ?></li>
    </ul>
  </div>
   <h3>解答</h3>
   <form action="a_new.php" method="get">
     <textarea name="answer" class="answer"  required placeholder="ここに解答を入力"><?php if(isset($_POST['answer'])){ echo $_POST['answer']; } ?></textarea>
     <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
     <input type="submit" class="button-right" value="確認">
   </form>
  </div>
  <div class="wrapper-nocolor">
    <a href="detail.php?q_id=<?php echo $q_id; ?>" class="btn">戻る</a>
  </div>
</body>
</html>
<?php
}else{
  $_SESSION['error'] = "どの質問に対して解答するか選んでください";
  header('Location: list.php?type=1');
}