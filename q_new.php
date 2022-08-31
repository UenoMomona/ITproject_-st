<?php
$window_title = "q_new";
require_once( 'template/logincheck.php' );
require_once( 'class/function.php' );
$functions = new functions();
require_once( 'class/db.php' );
$DB = new db();
include_once( 'template/header.php' );
// getで送られてきたらconfirm
// postで送られてきていたらcomplete
// なにも送られてきていなかったらform　を表示する

if(!empty($_GET['title']) && !empty($_GET['grade']) && !empty($_GET['body'])){
  // confirm
  $title = $_GET['title'];
  $grade = $_GET['grade'];
  $body = $_GET['body'];
  $grade_name = $functions->gradeJudge($_GET['grade']);
  ?>
  <div class="wrapper">
    <div class="post">
      <ul>
        <div class="flex-detail">
          <li class="detail-title"><?php echo $title; ?></li>
          <li class="detail-grade"><?php echo $grade_name; ?></li>
        </div>
        <li class="detail-body"><?php echo $body; ?></li>
      </ul>
    </div>
      <form action="q_new.php" method="POST" class="hidden">
        <input type="hidden" name="title" id="" value="<?php echo $title; ?>">
        <input type="hidden" name="grade" id="" value="<?php echo $grade; ?>">
        <input type="hidden" name="body" id="" value="<?php echo $body; ?>">
        <input type="submit" class="button-right" value="投稿">
      </form>
  </div>
  <div class="wrapper-nocolor">
    <form action="q_new.php" method="POST" class="hidden">
      <input type="hidden" name="title" id="" value="<?php echo $title; ?>">
      <input type="hidden" name="grade" id="" value="<?php echo $grade; ?>">
      <input type="hidden" name="body" id="" value="<?php echo $body; ?>">
      <input type="hidden" name="back" id="" value="">
      <input type="submit" class="button" value="戻る">
    </form>
  </div>
  <?php
}elseif(!empty($_POST['title']) && !empty($_POST['grade']) && !empty($_POST['body']) && !isset($_POST['back'])){
  // complete
  $title = $_POST['title'];
  $grade = $_POST['grade'];
  $body = $_POST['body'];
  $sql = 'INSERT INTO questions (user_id, title, grade, body ) VALUES (:user_id, :title, :grade, :body);';
  // バインドしたい値を配列にする
  $bind_params = array();
  $bind_params[':user_id'] = $user_id;
  $bind_params[':title'] = $title;
  $bind_params[':grade'] = $grade;
  $bind_params[':body'] = $body;
  // sqlとバインド変数を渡す
  $DB->setSQL($sql);
  $DB->setBind($bind_params);
  // SQLを実行する
  $DB->execute();
  ?>
  <div class="wrapper">
    <p>質問の投稿が完了しました</p>
    <div class="flex">
      <a href="index.php" class="btn1">トップへ</a>
      <a href="list.php?type=1" class="btn1">質問一覧へ</a>
    </div>
  </div>
  <?php
}else{
?>
  <div class="wrapper">
    <div class="form_wrap">
      <h2 class="title">質問作成</h2>
      <hr>
      <form action="" method="get" class="new_form">
        <ul>
          <li>
            <label for="title">質問タイトル</label>
            <input class="q_form" type="text" name="title" value="<?php if(isset($_POST['title'])){ echo $_POST['title']; }?>" required>
          </li>
          <li>
            <label for="grade">学年</label>
            <select name="grade" id="">
              <option disabled value="<?php 
              if(isset($_POST['grade'])){
                echo $_POST['grade'];
                $grade_name = $functions->gradeJudge($_POST['grade']);
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
              if(isset($_POST['body'])){
                echo $_POST['body'];
              }
              ?></textarea>
          </li>
          <li>
            <input type="submit" class="button-right" value="確認">
          </li>
        </ul>
      </form>
    </div>
  </div>
  <div class="wrapper-nocolor">
    <a href="index.php" class="btn">戻る</a>
  </div>
</body>
</html>
<?php
}