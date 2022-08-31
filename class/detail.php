<?php
class detail{
  // このクラスを立ち上げた時点でデータベースに接続できるようにファイルを読み込んでおく(今この時点でデータベースに接続するわけではない)
  public function __construct(){
    // dbのクラスのことが書いてあるファイルを読み込む
    require_once('db.php');
  }
  // プロパティ（クラス内で使う変数）
  // インターフェース
  public function detail($q_id){
    $sql = "SELECT user_id, title, grade, body, solve_check, q.updated_at, users.name as username
       FROM questions as q
       INNER JOIN users
       ON q.user_id = users.id
       WHERE q.id = :q_id;";

      // バインドしたい値を配列にする
      $bind_params = array();
      $bind_params[':q_id'] = $q_id;
      // sqlとバインド変数を渡す
      $DB = new db();
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();
      // 結果を取得する
      $result = $DB->fetch();
      return $result;
  }
  
  // 解答を表示する
  public function a_detail($a_id){
    $sql = "SELECT user_id, answer, users.name as username
              FROM answers as a
              INNER JOIN users
              ON a.user_id = users.id
              WHERE a.id = :a_id;";
    // バインドしたい値を配列にする
    $bind_params = array();
    $bind_params[':a_id'] = $a_id;
    // sqlとバインド変数を渡す
    $DB = new db();
    $DB->setSQL($sql);
    $DB->setBind($bind_params);
    // SQLを実行する
    $DB->execute();
    // 結果を取得する
    $result = $DB->fetch();
    return $result;
  }

  // メソッド

  // 内部メソッド
}