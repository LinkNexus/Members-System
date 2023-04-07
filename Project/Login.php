<?php

session_start();

require 'Include/Config.php';
require_once 'Include/Functions.php';

reconnect_from_cookie();


if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){
    $request = $link->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
    $request->execute(['username' => $_POST['username']]);
    $result = $request->fetch();
    if ($result && password_verify($_POST['password'], $result->password)){
        $_SESSION['user_infos'] = $result;
        $_SESSION['flash']['success'] = 'You are now connected';
        if ($_POST['remember']){
            $remember_token = str_random(250);
            $request = $link->prepare('UPDATE users SET remember_token = :remember_token WHERE id = :id');
            $request->execute([
                'remember_token' => $remember_token,
                'id' => $result->id
            ]);
            setcookie('remember', $result->id . '==' . $remember_token . sha1($result->id . 'TheBlog'), time() + 60 * 60 * 24 * 7);
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
    <title>TheBlog</title>
    <link rel="stylesheet" href="Styles/Forms.css">
</head>
<body>
<?php include_once 'Include/Nav-Bar.php'?>
<?php if (isset($_SESSION['flash'])): ?>

    <?php foreach ($_SESSION['flash'] as $type => $message): ?>

        <div class="<?php echo $type . '-msg'?>">
            <?= $message ?>
        </div>

    <?php endforeach; ?>

    <?php unset($_SESSION['flash']) ?>

<?php endif; ?>
<div class="login-box">
    <h2>Login</h2>
    <form action="" method="post">
        <div class="user-box">
            <input type="text" name="username" required="">
            <label>Username or Email</label>
        </div>
        <div class="user-box">
            <input type="password" name="password" required="">
            <label>Password</label>
        </div>
        <div class="user-box">
            <label>Remember me</label>
            <input type="checkbox" name="remember" class="remember">
        </div>
        <button type="submit">Submit</button>
        <a href="ForgotPassword">Forgot Password?</a>
    </form>
</div>
</body>
</html>

