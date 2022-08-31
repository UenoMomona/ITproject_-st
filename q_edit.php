<?php
$window_title = "q_edit";
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
if(isset($_GET['q_id'])){
  $q_id = $_GET['q_id'];
}
if(isset($_POST['q_id'])){
  $q_id = $_POST['q_id'];
}
// 元の質問の詳細をとってくる
if(!empty($q_id)){
  $result = $detail->detail($q_id);
}else{
  $_SESSION['error'] = "編集する質問を選択してください";
  header('Location: list.php?type=1');
}
// 編集しようとしている人ととってきたデータのユーザーが同じかを確認する
if (strcmp($result['user_id'], $user_id) == 1){
  $_SESSION['error'] = "エラーが発生しました";
  header('Location: index.php');
}

if(!empty($_GET['q_id']) && !empty($_GET['title']) && !empty($_GET['grade']) && !empty($_GET['body']) ){
  // confirm
  $title = $_GET['title'];
  $grade = $_GET['grade'];
  $body = $_GET['body'];
  $grade_name = $functions->gradeJudge($_GET['grade']);
  include_once( 'template/header.php' );
  ?>
  <div class="wrapper">
    <h2>編集前の質問</h2><hr>
    <div class="post">
      <ul>
        <div class="flex-detail">
          <li class="detail-title"><?php echo $result['title']; ?></li>
          <li class="detail-grade"><?php echo $functions->gradeJudge($result['grade']); ?></li>
        </div>
        <li class="detail-body"><?php echo $result['body']; ?></li>
      </ul>
    </div>
    <h2>編集後</h2><hr>
      <div class="post">
        <ul>
          <div class="flex-detail">
            <li class="detail-title"><?php echo $title; ?></li>
            <li class="detail-grade"><?php echo $grade_name; ?></li>
          </div>
          <li class="detail-body"><?php echo $body; ?></li>
        </ul>
      </div>
      <div class="flex-none">
        <form action="q_edit.php" method="POST" class="hidden">
          <input type="hidden" name="title" id="" value="<?php echo $title; ?>">
          <input type="hidden" name="grade" id="" value="<?php echo $grade; ?>">
          <input type="hidden" name="body" id="" value="<?php echo $body; ?>">
          <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
          <input type="submit" class="button" value="完了">
        </form>
        <form action="q_edit.php" method="POST" class="hidden">
          <input type="hidden" name="title" id="" value="<?php echo $title; ?>">
          <input type="hidden" name="grade" id="" value="<?php echo $grade; ?>">
          <input type="hidden" name="body" id="" value="<?php echo $body; ?>">
          <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
          <input type="hidden" name="back" id="" value="">
          <input type="submit" class="button" value="戻る">
        </form>
      </div>
    </div>
    
  </div>
  <?php
}elseif(!empty($_POST['q_id']) && !empty($_POST['title']) && !empty($_POST['grade']) && !empty($_POST['body']) && (!isset($_POST['back']) && !isset($_POST['form']) )){
  // complete
  $title = $_POST['title'];
  $grade = $_POST['grade'];
  $body = $_POST['body'];
  $q_id = $_POST['q_id'];

  $sql = "UPDATE questions SET title = :title, grade = :grade, body = :body WHERE id = :q_id;";
  // バインドしたい値を配列にする
  $bind_params = array();
  $bind_params[':title'] = $title;
  $bind_params[':grade'] = $grade;
  $bind_params[':body'] = $body;
  $bind_params[':q_id'] = $q_id;
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
}elseif( isset($_POST['q_id']) && (isset($_POST['back']) || isset($_POST['form'])) ){
  // form
  if(isset($_POST['back'])){
    // confirmから戻ってきた
    $title = $_POST['title'];
    $grade = $_POST['grade'];
    $body = $_POST['body'];
  }else{
    $title = $result['title'];
    $grade = $result['grade'];
    $body = $result['body'];
  }
  include_once( 'template/header.php' );
?>
 <div class="wrapper">
    <div class="form_wrap">
      <h2 class="title">質問編集</h2>
      <form action="" method="get" class="new_form">
        <ul>
          <li>
            <label for="title">質問タイトル</label>
            <input class="q_form" type="text" name="title" value="<?php if(isset($title)){ echo $title; }?>" required>
          </li>
          <li>
            <label for="grade">学年</label>
            <select name="grade" id="">
              <option value="<?php 
              if(isset($grade)){
                echo $grade;
                $grade_name = $functions->gradeJudge($grade);
              }else{
                echo 0;
              }
              ?>">
              <?php
              if(isset($grade_name)){
                echo $grade_name; 
              }else{
                echo "選択してください";
              }
              ?>
              </option>
              <option value="1">小学一年生</option>
              <option value="2">小学二年生</option>
              <option value="3">小学三年生</option>
              <option value="4">小学四年生</option>
              <option value="5">小学五年生</option>
              <option value="6">小学六年生</option>
              <option value="7">中学一年生</option>
              <option value="8">中学二年生</option>
              <option value="9">中学三年生</option>
              <option value="10">高校一年生</option>
              <option value="11">高校二年生</option>
              <option value="12">高校三年生</option>
            </select>
          </li>
          <li>
            <label for="body">質問内容</label>
            <textarea name="body" class="q_form" id="textarea"><?php
              if(isset($body)){
                echo $body;
              }
              ?></textarea>
          </li>
          <li>
            <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
            <input type="submit" class="button-right" value="確認">
          </li>
        </ul>
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
  $_SESSION['error'] = "どの質問を編集するか選んでください";
  header('Location: list.php?type=1');
}