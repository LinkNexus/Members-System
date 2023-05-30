<?php

session_start();

require_once 'Include/Config.php';
require_once 'Include/Functions.php';
require_once 'Include/mailer.php';

if (!empty($_POST) && !empty($_POST['email'])){
    $request = $link->prepare('SELECT * FROM users WHERE email = :email AND confirmed_at IS NOT NULL');
    $request->execute(['email' => $_POST['email']]);
    $result = $request->fetch();
    if ($result){
        $reset_token = str_random(60);
        $request = $link->prepare('UPDATE users SET reset_token = :reset_token, reset_at = NOW() WHERE id = :id');
        $request->execute([
            'reset_token' => $reset_token,
            'id' => $result->id
        ]);
        $_SESSION['flash']['success'] = 'The mail containing the Instructions for the Password Reset has been sent to you';
        try {
            sendWithGmail($_POST['email'], 'Reset of your Password', "In order to reset your Password, click on this link\n\nhttp://localhost/Project/ResetPassword.php?id={$result->id}&token=$reset_token");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        header('Location: Account.php');
        exit();
    } else{
        $_SESSION['flash']['alert'] = 'Connection Information are invalid';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="Styles/Forms.css">
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
<div class="login-box">
    <h2>Forgotten Password</h2>
    <form action="" method="post">
        <div class="user-box">
            <input type="email" name="email" required="">
            <label>Email</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
