<?php
// DB操作に関する関数のクラス

class db{
  // プロパティ（クラス内で使う変数）
  private $user = '';
  private $password = '';
  private $db_name = 'try_db2';
  private $host = '';
  private $dbh;
  private $sql;
  private $bind_params = array();
  private $stmt = "";

  // インターフェース（外とやり取りをするための関数）
  // SQLを受け取る
  public function setSQL($sql){
    $this->sql = $sql;
  }
  // バインドする値を受け取る
  public function setBind($bind_params){
    $this->bind_params = $bind_params;
  }
  // 今登録したユーザーのIDをとってくる
  public function getID(){
    return $this->dbh->lastInsertId();
  }

  // メソッド
  // この関数ひとつ実行するだけでバインドしてsql流してくれるすごいやつ
  public function execute(){
    // dbhが設定されていないときは設定する
    if(empty($this->dbh)){
      $this->dbConnect();
    }
    try{
      // 例外処理の事前処理としてデータベースでエラーが起こったら例外を発令するようにする
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // sqlをセットして、バインド
      if($this->bind()){
        // こっちに来ているならバインド成功している
        // sqlを実行する
        $this->execSQL();
      }else{
        echo "バインド失敗";
        exit;
      }
    }catch(Exception $e){
      echo "失敗しました" . $e->getMessage();
      exit;
    }
  }

  // 一件の結果を取得する
  public function fetch(){
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }
  // 結果の一覧を取得する
  public function fetchAll(){
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // トランザクション系
    // トランザクション開始
    public function beginTransaction(){
      try{
          // データベース接続処理を行う(接続されていない場合だけ)
          if ( empty( $this->dbh ) ) {
              $this->dbConnect();
          }
          $this->dbh->beginTransaction();
      } catch (Exception $e) {
          echo "失敗しました。" . $e->getMessage();
          exit;
      }
    }

    // コミット
    public function commit(){
      $this->dbh->commit();
    }

    // ロールバック
    public function rollback(){
      $this->dbh->rollback();
    }

  // 内部メソッド

  // データベースに接続する
  protected function dbConnect(){
    $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->db_name", "$this->user", "$this->password" );
  }

  // 受け取ったバインドする値をバインドする
  protected function Bind(){
    // この関数の中でもらってきた値を使いやすく変数に入れておく
    $bind_params = $this->bind_params;
    // sqlをstmt(変数)にセット
    $this->stmt = $this->dbh->prepare($this->sql);
    // $bind_paramsに入っている値を１つずつバインドしていく
    foreach($bind_params as $key => &$param){
      $this->stmt->bindparam( $key, $param);
    }
    // これらの処理が正常に終了したならtrueを返す
    return true;
  }

  // sqlを実行する
  protected function execSQL(){
    $this->stmt->execute();
  }
}