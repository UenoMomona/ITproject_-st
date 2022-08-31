<?php
$window_title = 'index';
require_once( 'template/logincheck.php' );
include_once( 'template/header.php' );
// ユーザー名を受け取る
$user_name = $checker->getName($user_id);
?>
<div class="flex">
  <h2 class="greeting">こんにちは <?php echo $user_name; ?>さん</h2>
  <a href="user.php?type=4" class="btn w105">ユーザー情報</a>
</div>
  <div class="wrapper">
    <main>
    
      <div class="flex">
        <a href="q_new.php" class="btn1">質問する</a>
        <a href="list.php?type=3&solve_check=0" class="btn1">解答する</a>
      </div>
      <div class="flex">
        <a href="list.php?type=1" class="btn1">すべての質問から探す</a>
        <a href="list.php?type=3&solve_check=1" class="btn1">解決済みの質問から探す</a>
      </div>
      <hr>
        <h2 class="title">学年別に探す</h2>
        <div class="flex">
          <a href="list.php?type=2&grade=1" class="grade">1年生</a>
          <a href="list.php?type=2&grade=2" class="grade">2年生</a>
          <a href="list.php?type=2&grade=3" class="grade">3年生</a>
        </div>
        <div class="flex">
          <a href="list.php?type=2&grade=4" class="grade">4年生</a>
          <a href="list.php?type=2&grade=5" class="grade">5年生</a>
          <a href="list.php?type=2&grade=6" class="grade">6年生</a>
        </div>
        <div class="flex">
          <a href="list.php?type=2&grade=7" class="grade">中学1年生</a>
          <a href="list.php?type=2&grade=8" class="grade">中学2年生</a>
          <a href="list.php?type=2&grade=9" class="grade">中学3年生</a>
        </div>
        <div class="flex">
          <a href="list.php?type=2&grade=10" class="grade">高校1年生</a>
          <a href="list.php?type=2&grade=11" class="grade">高校2年生</a>
          <a href="list.php?type=2&grade=12" class="grade">高校3年生</a>
        </div>
        
    </main>
  </div>

</body>
</html>