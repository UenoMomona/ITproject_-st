<?php
$window_title = "detail";
require_once( 'template/logincheck.php' );
require_once( 'class/function.php' );
$functions = new functions();
require_once( 'class/db.php' );
$DB = new db();
require_once( 'class/detail.php' );
$detail = new detail();
include_once( 'template/header.php' );

  // getからq_idをとってくる
  if($_GET['q_id']){
    $q_id = $_GET['q_id'];
    // データを収納するためにresultを配列にしておく
    $result = array();
    // 関数を使って詳細をとってくる
    $result = $detail->detail($q_id);
  }else{
    // q_idが送られてきていない場合はどの詳細を表示すべきかわからないので一覧に戻す
    // 本当はさっきいたところに戻りたいけどどこから来たのかわからないから無理かな
    $_SESSION['error'] = "質問を指定してください";
    header('Location: list.php?type=1');
  }

  ?>
  <div class="wrapper">
    <h2>質問詳細</h2>
    <hr>
    <div class="post">
      <ul>
        <li class="detail-name"><?php echo $result['username'] ?></li>
        <div class="flex-detail">
          <li class="detail-title"><?php echo $result['title'] ?></li>
          <li class="detail-grade"><?php echo $functions->gradeJudge($result['grade']) ?></li>
          <li class="detail-solve"><?php echo $functions->solveCheck($result['solve_check']) ?></li>
        </div>
        <li class="detail-body"><?php echo $result['body'] ?>
          <?php
          // 質問の投稿者と今ログインしている人が同じだったら質問の編集、削除ができるようにする
          if(owner_check($q_id, $user_id)){
            // 編集、削除可
            ?>
            <ul>
              <div class="flex-none">
                <li>
                  <form action="q_edit.php" method="POST" class="hidden-form">
                    <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
                    <input type="hidden" name="form" id="" value="">
                    <input type="submit" class="mini-btn" value="編集">
                  </form>
                </li>
                <li>
                  <form action="q_delete.php" method="POST" class="hidden-form">
                    <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
                    <input type="hidden" name="form" id="" value="">
                    <input type="submit" class="mini-btn" value="削除">
                  </form>
                </li>
                <?php
                // 解決済み
                switch($result['solve_check']){
                  // 次のaタグを押したらsolve.php的なのに飛んでデータベースを変えてここに戻ってくるようにする
                  // とんだ先でもちゃんと投稿者本人であるかの確認をして、確認が取れたらsolve_checkを変更する
                  case 0: echo "<li><a href='solve.php?q_id=" . $q_id . "&solve_check=1'class='mini-btn'>解決済みにする</a></li>"; break;
                  case 1: echo "<li><a href='solve.php?q_id=" . $q_id . "&solve_check=0' class='mini-btn'>未解決にする</a></li>" ;break;
                  default: return "不明";
                }?>
              </div>
            </ul><?php
          }
          ?>
        </li>
      </ul>
      
    </div>
    <!-- // 誰でも(質問者も質問者以外も)解答することができる -->
    <a href="a_new.php?q_id=<?php echo $q_id ?>" class="btn1">解答する</a>
    </div>
    <div class="wrapper">
      <!-- 解答を一覧にして表示する -->
      <h2>解答一覧</h2>
      <hr>
      <?php
      $result = array();
      $a_result = a_list($q_id);
      ?>
      <ul>
        <?php
        if($a_result != null){
          foreach($a_result as $val){
            echo "<li class='detail-name'>" . $val['name'] . "</li>";
            echo "<li class='detail-body'>" . $val['answer'];
            // この解答の投稿者本人のみ編集、削除を可能にする
            if(answer_owner_check($val['a_id'], $user_id)){
              // 編集削除可
              ?>
              <ul>
                <div class="flex-none">
                  <li>
                    <form action="a_edit.php" method="POST" class="hidden-form">
                      <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
                      <input type="hidden" name="a_id" id="" value="<?php echo $val['a_id']; ?>">
                      <input type="hidden" name="form" id="" value="">
                      <input type="submit" class="mini-btn" value="編集">
                    </form>
                  </li>
                  <li>
                    <form action="a_delete.php" method="POST" class="hidden-form">
                      <input type="hidden" name="q_id" id="" value="<?php echo $q_id; ?>">
                      <input type="hidden" name="a_id" id="" value="<?php echo $val['a_id']; ?>">
                      <input type="hidden" name="form" id="" value="">
                      <input type="submit" class="mini-btn" value="削除">
                    </form>
                  </li>
                </div>
              </ul>
            </li>
              <?php
            }
          }
        }else{
          echo "<p>この質問に対する解答はありません";
        }
        ?>
      </ul>
    </div>
    <div class="wrapper-nocolor">
      <a href="list.php?type=1" class="btn">質問一覧へ</a>
    </div>
</body>
</html>

<?php
// このページでしか使わない関数
// 質問者とログイン中のユーザーが同じかを調べる
function owner_check($q_id, $user_id){
    $sql = "SELECT user_id, solve_check FROM questions WHERE id = :q_id;";
    // バインドしたい値を配列にする
    $bind_params = array();
    $bind_params[':q_id'] = $q_id;
    // sqlとバインド変数を渡す
    $DB = new db();
    $DB->setSQL($sql);
    $DB->setBind($bind_params);
    // SQLを実行する
    $DB->execute();
    // 結果を返す
    $result = $DB->fetch();
  if (strcmp($result['user_id'], $user_id) == 0){
    // 質問者とログイン中のユーザーは同じである
    return true;
  }else {
    // 質問者とログイン中のユーザーが異なる
    return false;
  }
}
function answer_owner_check($a_id, $user_id){
    $sql = "SELECT user_id FROM answers WHERE id = :a_id;";
    // バインドしたい値を配列にする
    $bind_params = array();
    $bind_params[':a_id'] = $a_id;
    // sqlとバインド変数を渡す
    $DB = new db();
    $DB->setSQL($sql);
    $DB->setBind($bind_params);
    // SQLを実行する
    $DB->execute();
    // 結果を返す
    $result = $DB->fetch();
  if (strcmp($result['user_id'], $user_id) == 0){
    // 質問者とログイン中のユーザーは同じである
    return true;
  }else {
    // 質問者とログイン中のユーザーが異なる
    return false;
  }
}

// 質問に対する解答を探す
function a_list($q_id){
   $sql = "SELECT answers.id as a_id, users.name, answer 
            FROM answers 
            INNER JOIN users
            ON answers.user_id = users.id
            WHERE question_id = :q_id;";
    // バインドしたい値を配列にする
    $bind_params = array();
    $bind_params[':q_id'] = $q_id;
    // sqlとバインド変数を渡す
    $DB = new db();
    $DB->setSQL($sql);
    $DB->setBind($bind_params);
    // SQLを実行する
    $DB->execute();
    // 結果を返す
    $result = $DB->fetchAll();
   return $result;
}
