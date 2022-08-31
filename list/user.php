<?php
$num = ($now_page - 1) * 10;
// 並び順の判定
switch($order){
  case 0:
    $sql = "SELECT questions.id, title, grade, solve_check, users.name as name 
              FROM questions
            INNER JOIN users
              ON questions.user_id = users.id
            WHERE questions.user_id = :user_id
            ORDER BY id DESC
            LIMIT 10 OFFSET $num";
  break;
  case 1:
    $sql = "SELECT questions.id, title, grade, solve_check, users.name as name
              FROM questions
            INNER JOIN users
              ON questions.user_id = users.id
            WHERE questions.user_id = :user_id
            LIMIT 10 OFFSET $num";
  break;
  default: echo "エラーが発生しました。";
}


// バインドしたい値を配列にする
$bind_params = array();
$bind_params[':user_id'] = $user_id;
// sqlとバインド変数を渡す
$DB->setSQL($sql);
$DB->setBind($bind_params);
// SQLを実行する
$DB->execute();

// 結果を取得
$result = $DB->fetchAll();
