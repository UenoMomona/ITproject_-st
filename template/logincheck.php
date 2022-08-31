<?php
    // loginCheck
      // login checkのことが書いてあるファイルを読み込む
      require_once('class/checker.php');
      // checkerオブジェクトを呼びだす
      $checker = new checker();
      // loginCheckの関数を実行する
      $user_id = $checker->loginCheck();
?>