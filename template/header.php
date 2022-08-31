<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $window_title; ?></title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <a href="index.php" class="white title">算数/数学疑問解決</a>
      <?php
        if(!empty($user_id)){
          echo "<a href='logout.php' class='white right'>ログアウト</a>";
        }
      ?>
  </header>
  <p class="error"><?php if(isset($_SESSION['error'])){ echo $_SESSION['error']; unset($_SESSION['error']);} ?></p>