<?php
$window_title = "user";
require_once( 'template/logincheck.php' );
require_once( 'class/db.php' );
$DB = new db();
require_once( 'class/detail.php');
$detail = new detail();

// GETからq_idをとって来る
if(isset($_GET['q_id'])){
  $q_id = $_GET['q_id'];
}else {
  // q_idが送られてきていない場合はどの質問かわからないためall_listに返す
  header('Location: list.php?type=1');
}

$result = $detail->detail($q_id);
$result_user_id = $result['user_id'];
$solve_check = $result['solve_check'];

include_once( 'template/header.php' );
if( strcmp($result_user_id, $user_id) == 0){
  switch($solve_check){
    case 0: $sql = "UPDATE questions SET solve_check = 1 WHERE id = :q_id;"; break;
    case 1: $sql = "UPDATE questions SET solve_check = 0 WHERE id = :q_id;"; break;
    default: return "エラーが発生しました";
  }
  // バインドしたい値を配列にする
  $bind_params = array();
  $bind_params[':q_id'] = $q_id;
  // sqlとバインド変数を渡す
  $DB = new db();
  $DB->setSQL($sql);
  $DB->setBind($bind_params);
  // SQLを実行する
  $DB->execute();
  echo "<div class='wrapper'>";
  echo "変更が完了しました";
}else{
  $_SESSION['error'] = "エラーが発生しました";
  exit;
}

?>
  <div class="flex">
    <!-- 質問詳細に戻る -->
    <a href="detail.php?q_id=<?php echo $q_id; ?>" class="btn1">質問詳細に戻る</a>
    <!-- トップへ戻る -->
    <a href="index.php" class="btn1">トップへ戻る</a>
    <!-- 質問一覧へ戻る -->
    <a href="list.php?type=1" class="btn1">質問一覧へ戻る</a>
  </div>
</div>