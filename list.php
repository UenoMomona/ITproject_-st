<?php
$window_title = "list";
require_once( 'template/logincheck.php' );
require_once( 'class/function.php' );
$functions = new functions();
require_once( 'class/db.php' );
$DB = new db();


// 今のURLをとってくる
$now_url = $_SERVER['REQUEST_URI'];

// 今のページの設定をする（初期値は1）
$now_page = 1;
if(isset($_GET['page'])){
  $now_page = $_GET['page'];
}

// ゲットでorderが送られてきていたら変える
if(isset($_GET['order'])){
  switch($_GET['order']){
    case 0: $order = 0; break;
    case 1: $order = 1; break;
    default: echo "エラーが発生しました";
  }
}else{
  // 表示順を決める（初期値は0→新しい順）
  $order = 0;
}
// 新しい順の時は古い順にするためのボタンを置いておき、古い順になっているときには、新しい順にするためのボタンを置いておく
if($order == 0){
  // 今新しい順だから
  $order_btn = "古い順";
  $order_link = $now_url . "&order=1";
}elseif($order == 1){
  $order_btn = "新しい順";
  $order_link = $now_url . "&order=0";
}


// all
// grade
// solved
switch($_GET['type']){
  case 1: require_once('list/all.php'); break;
  case 2: require_once('list/grade.php'); break;
  case 3: require_once('list/solved.php'); break;
  case 4: require_once('list/user.php'); break;
  default: $_SESSION['error'] = "typeが設定されていません";
          header('Location: index.php');
          exit;
          break;
}

// 結果取得後
include_once( 'template/header.php' );
if( $result != null){
    // 質問の表示順を変える
    echo "<div class='wrapper'>";
    echo "<h2>$list_name</h2>";
    echo "<a href='" . $order_link . "' class='order'>" . $order_btn . "</a>";
  foreach($result as $row){    
    $grade_name = $functions->gradeJudge($row['grade']);
    echo "<div class='post'><ul>";
    echo "<div class='flex-list'><li class='name'>" . $row['name'] . "</li>";
    echo "<li class='grade'><a href='list.php?type=2&grade=" . $row['grade'] . "'>" . $grade_name . "</a></li></div>";
    echo "<li class='title'><a href='detail.php?q_id=" . $row['id'] . "' class='q_title'>" . $row['title'] . "</a></li>";
    echo "<li class='solve'>" . $functions->solveCheck($row['solve_check']) . "</li>";
    echo "</ul></div>";
  }
}else{
  echo "<div class='wrapper'>";
  echo "<P>" . $list_name . "はありません</p>";

}

?>
</div>
<div class="wrapper-nocolor">
  <a href="index.php" class="btn">トップへ戻る</a>
</div>