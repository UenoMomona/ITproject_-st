<?php
    $num = ($now_page - 1) * 10;
    switch($order){
      case 0:
        $sql = "SELECT questions.id, title, grade, solve_check, users.name 
                FROM questions
              INNER JOIN users
                ON questions.user_id = users.id
              ORDER BY id DESC
              LIMIT 10 OFFSET $num";
      break;
      case 1:
        $sql = "SELECT questions.id, title, grade, solve_check, users.name 
              FROM questions
            INNER JOIN users
              ON questions.user_id = users.id
            LIMIT 10 OFFSET $num";
      break;
      default: echo "エラーが発生しました。";
    }

  // バインドしたい値を配列にする
  $bind_params = array();
  // sqlとバインド変数を渡す
  $DB->setSQL($sql);
  $DB->setBind($bind_params);
  // SQLを実行する
  $DB->execute();
  
  // 結果を取得
  $result = $DB->fetchAll();

  $list_name = "すべての質問";