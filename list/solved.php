<?php
if(isset($_GET['solve_check'])){
  $solve_check = $_GET['solve_check'];
}else{
  $_SESSION['error'] = "エラーが発生しました";
  header('Location: index.php');
  exit;
}
$num = ($now_page - 1) * 10;
// 並び順の判定
switch($order){
  case 0:
    $sql = "SELECT questions.id, title, grade, solve_check, users.name 
              FROM questions
            INNER JOIN users
              ON questions.user_id = users.id
            WHERE solve_check = :solve_check
            ORDER BY id DESC
            LIMIT 10 OFFSET $num";
  break;
  case 1:
    $sql = "SELECT questions.id, title, grade, solve_check, users.name 
              FROM questions
            INNER JOIN users
              ON questions.user_id = users.id
            WHERE solve_check = :solve_check
            LIMIT 10 OFFSET $num";
  break;
  default: echo "エラーが発生しました。";
}


// バインドしたい値を配列にする
$bind_params = array();
$bind_params[':solve_check'] = $solve_check;
// sqlとバインド変数を渡す
$DB->setSQL($sql);
$DB->setBind($bind_params);

// SQLを実行する
$DB->execute();

// 結果を取得
$result = $DB->fetchAll();

$solve = $functions->solveCheck($solve_check);
$list_name = $solve . "の質問";