<?php

session_start();
require_once 'Include/Functions.php';
require_once 'Include/Config.php';
require_once 'Include/mailer.php';

if (!empty($_POST)) {

    $errors = array();

    if (empty($_POST['username']) || !preg_match('/^[a-z0-9A-Z_]+$/', $_POST['username'])) {
        $errors['username'] = 'Username is not valid (Alphanumeric)';
    } else {
        $request = $link->prepare('SELECT id FROM users WHERE username = :username');
        $request->execute(['username' => $_POST['username']]);
        $result = $request->fetch();

        if ($result) {
            $errors['username'] = 'Username is already used';
        }
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email is not valid';
    } else {
        $request = $link->prepare('SELECT id FROM users WHERE email = :email');
        $request->execute(['email' => $_POST['email']]);
        $result = $request->fetch();

        if ($result) {
            $errors['email'] = 'Email is already used';
        }
    }

    if (empty($_POST['password']) || strlen($_POST['password']) < 5) {
        $errors['password'] = 'Password is not valid and must contain at least 5 Characters';
    }

    if (($_POST['password'] != $_POST['confirm_password'])) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    if (empty($errors)) {
        $request = $link->prepare('INSERT INTO users(username, email, password, confirmation_token) VALUES (:username, :email, :password, :confirmation_token)');
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $token = str_random(60);
        $request->execute([
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $password,
            'confirmation_token' => $token
        ]);
        try {
            $user_id = $link->lastInsertId();
            sendWithGmail($_POST['email'], 'Account Confirmation', "In order to confirm your Account, click on this link\n\nhttp://localhost/Project/Confirm.php?id=$user_id&token=$token");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $_SESSION['flash']['success'] = 'A Confirmation Mail has been sent to you';
        header('Location: Login.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TheBlog</title>
    <link rel="stylesheet" href="Styles/Forms.css">
</head>

<body>
    <?php include_once 'Include/Nav-Bar.php' ?>
    <?php if (!empty($errors)) : ?>
        <div class="error-msg">
            <p>The Information were not filled correctly. These are the possible errors:</p>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <div class="login-box">
        <h2>Register</h2>
        <form action="" method="post">
            <div class="user-box">
                <input type="text" name="username" required="">
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="email" name="email" required="">
                <label>Email</label>
            </div>
            <div class="user-box">
                <input class="password" type="password" name="password" required="">
                <label class="label_password">Password</label>
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