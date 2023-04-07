<?php

session_start();
require_once 'Include/Functions.php';
verify_if_logged();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TheBlog</title>
    <link rel="stylesheet" href="Styles/Forms.css">
    <link rel="stylesheet" href="Styles/Style.css">
</head>
<body>
<?php require_once 'Include/Nav-Bar.php'?>
<?php if (isset($_SESSION['flash'])): ?>

    <?php foreach ($_SESSION['flash'] as $type => $message): ?>

        <div class="<?php echo $type . '-msg'?>">
            <?= $message ?>
        </div>

    <?php endforeach; ?>

    <?php unset($_SESSION['flash']) ?>

<?php endif; ?>
<div class="info-box">
    <h2>Account</h2>
    <div class="info">
        Username: <?php echo $_SESSION['user_infos']->username; ?>
    </div>
    <div class="info">
        Email: <?php echo $_SESSION['user_infos']->email; ?>
    </div>
    <div class="info">
        Account's Date of Creation: <?php echo $_SESSION['user_infos']->confirmed_at; ?>
    </div>
    <a href="ChangePassword.php">Change Password</a>
</div>
</body>
</html>
