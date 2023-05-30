<?php

session_start();

require_once 'Include/Config.php';
require_once 'Include/Functions.php';

if (isset($_GET['id']) && isset($_GET['token'])){
    $request = $link->prepare('SELECT * FROM users WHERE id = :id AND reset_token IS NOT NULL AND reset_token = :reset_token AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
    $request->execute([
            'id' => $_GET['id'],
        'reset_token' => $_GET['token']
    ]);
    $result = $request->fetch();
    if ($result){
        if (!empty($_POST)){
            if (!empty($_POST['new_password']) && ($_POST['new_password'] == $_POST['confirm_password'])){
                $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
                $request = $link->prepare('UPDATE users SET password = :password, reset_at = NULL, reset_token = NULL');
                $request->execute(['password' => $password]);
                $_SESSION['flash']['success'] = 'Your Password has been successfully modified';
                $_SESSION['user_infos'] = $result;
                header('Location: Account.php');
                exit();
            }
        }
    } else{
        $_SESSION['flash']['alert'] = 'This Token is not valid';
        header('Location: Login.php');
        exit();
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
<div class="login-box">
    <h2>Reset Password</h2>
    <form action="" method="post">
        <div class="user-box">
            <input type="password" name="new_password" required="">
            <label>New Password</label>
        </div>
        <div class="user-box">
            <input type="password" name="confirm_password" required="">
            <label>Confirm Password</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
