<?php

session_start();

require_once 'Include/Functions.php';
require_once 'Include/Config.php';

verify_if_logged();

if (!empty($_POST)){
    if (password_verify($_POST['og_password'], $_SESSION['user_infos']->password)){
        if (empty($_POST['new_password']) || strlen($_POST['new_password']) < 5 || $_POST['new_password'] != $_POST['confirm_password']){
            $_SESSION['flash']['alert'] = 'New Password must be filled, must contain at least 5 Characters and Passwords must correspond';
        } else {
            $user_id = $_SESSION['user_infos']->id;
            $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $request = $link->prepare('UPDATE users SET password = :password WHERE id = :id');
            $request->execute([
                'password' => $password,
                'id' => $user_id
            ]);
            $_SESSION['flash']['success'] = 'Your Password has been updated';
        }
    } else{
        $_SESSION['flash']['alert'] = "Your Account's Password is incorrect";
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
<?php require_once 'Include/Nav-Bar.php' ?>
<?php if (isset($_SESSION['flash'])): ?>

    <?php foreach ($_SESSION['flash'] as $type => $message): ?>

        <div class="<?php echo $type . '-msg'?>">
            <?= $message ?>
        </div>

    <?php endforeach; ?>

    <?php unset($_SESSION['flash']) ?>

<?php endif; ?>
<div class="login-box">
    <h2>Change Password</h2>
    <form action="" method="post">
        <div class="user-box">
            <input type="password" name="og_password" required="">
            <label>Old Password</label>
        </div>
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
