<?php
// ログインしているかのチェックに利用するためのクラス
class checker{
  // このクラスを立ち上げた時点でデータベースに接続できるようにファイルを読み込んでおく(今この時点でデータベースに接続するわけではない)
  public function __construct(){
      // セッションの利用を宣言する
      // 二重に立ち上がらないようにif文に
      if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    // dbのクラスのことが書いてあるファイルを読み込む
    require_once('db.php');
  }
  // プロパティ（クラス内で使う変数）
  private $now_key = "";
  private $new_key = "";
  private $expire_period = 30;
  // インターフェース
    // ユーザー名を返す
    public function getName($user_id){
      $sql = 'SELECT name FROM users WHERE id = :id;';
      // バインドしたい値を配列にする
      $bind_params = array();
      $bind_params[':id'] = $user_id;
      // sqlとバインド変数を渡す
      $DB = new db();
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();
      // 結果を返す
      $result = $DB->fetch();
      return $result['name'];
    }
  // メソッド
  public function loginCheck(){
    // セッションに認証キーが設定されているのかチェック
    $this->fromSession();
    // DBに残ってる認証キーを持ってくる(ちゃんととってこれた＝ログインされている)
    $result = $this->fromDb();
    if(empty($result)){
      // DBから認証キーをとってこれなかった
      $this->toLogin(); //ログインに飛ばす
      exit;
    }
    // 新しい認証キーを作成
    $this->createKey();
    // 新しい認証キーをDBに登録
    $this->updateKey();
    // セッションの認証キーを新しい認証キーに更新する
    $this->setKey();
    return $result['user_id'];
  }

  // 内部メソッド
    // セッションに認証キーが設定されているのかチェック(入っていた認証キーを持ってくる)
    protected function fromSession(){
      if(!empty($_SESSION['now_key'])){
        $this->now_key = $_SESSION['now_key'];
      }else{ //認証キーが確認できなかったのでログインに飛ばす
        $this->toLogin();
      }
    }
    // DBに残ってる認証キーを持ってくる
    protected function fromDb(){
      $sql = 'SELECT user_id, verify_key, end_time FROM login_checks WHERE verify_key = :now_key AND NOW() < end_time;' ;
      // バインドしたい値を配列にする
      $bind_params = array();
      $bind_params[':now_key'] = $this->now_key;
      // sqlとバインド変数を渡す
      $DB = new db();
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();
      // 結果を返す
      return $DB->fetch();
    }
    // 新しい認証キーを作成
    protected function createKey(){
      $this->new_key = uniqid(bin2hex(random_bytes(1)));
    }
    // 新しい認証キーをDBに登録
    protected function updateKey(){
      $sql = "UPDATE login_checks 
                  SET verify_key = :new_key,
                       end_time = CURRENT_TIMESTAMP + INTERVAL :expire_period MINUTE  
                  WHERE verify_key = :now_key;";
      // バインドしたい値を配列にする
      $bind_params = array();
      $bind_params[':now_key'] = $this->now_key;
      $bind_params[':new_key'] = $this->new_key;
      $bind_params[':expire_period'] = $this->expire_period;
      // sqlとバインド変数を渡す
      $DB = new db();
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();

      $sql = "DELETE FROM login_checks WHERE NOW()>= end_time;";
      // バインドしたい値を配列にする
      $bind_params = array();
      // sqlとバインド変数を渡す
      $DB->setSQL($sql);
      $DB->setBind($bind_params);
      // SQLを実行する
      $DB->execute();
    }
    // セッションの認証キーを新しい認証キーに更新する
    protected function setKey(){
      $_SESSION['now_key'] = $this->new_key;
    }

    // ログインに飛ばす
    protected function toLogin(){
      header('Location: login.php');
    }

}