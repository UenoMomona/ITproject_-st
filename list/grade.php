<?php
if(!empty($_GET['grade'])){
  $grade = $_GET['grade'];
}else{
  $_SESSION['error'] = "学年を選択してください。";
  header('Location: index.php');
  exit;
}

$num = ($now_page - 1) * 10;
switch($order){
  case 0:
    $sql = "SELECT questions.id, title, grade, solve_check, users.name 
            FROM questions
          INNER JOIN users
            ON questions.user_id = users.id
          WHERE grade = :grade
          ORDER BY id DESC
          LIMIT 10 OFFSET $num";
  break;
  case 1:
    $sql = "SELECT questions.id, title, grade, solve_check, users.name 
          FROM questions
        INNER JOIN users
          ON questions.user_id = users.id
        WHERE grade = :grade
        LIMIT 10 OFFSET $num";
  break;
  default: echo "エラーが発生しました。";
}

  // バインドしたい値を配列にする
  $bind_params = array();
  $bind_params[':grade'] = $grade;
  // sqlとバインド変数を渡す
  $DB->setSQL($sql);
  $DB->setBind($bind_params);

  // SQLを実行する
  $DB->execute();
  
  // 結果を取得
  $result = $DB->fetchAll();

  $grade_name = $functions->gradeJudge($grade);
  $list_name = $grade_name . "の質問";