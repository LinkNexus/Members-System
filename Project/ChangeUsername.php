<?php

session_start();

require_once 'Include/Functions.php';
require_once 'Include/Config.php';

verify_if_logged();

if (!empty($_POST)){

    $user_id = $_SESSION['user_infos']->id;

    $request = $link->prepare('SELECT * FROM users WHERE id = :id AND (modified_at IS NULL OR modified_at <= DATE_SUB(NOW(), INTERVAL 1 MINUTE))');
    $request->execute(['id' => $user_id]);
    $result = $request->fetch();

    if ($result) {
        if(empty($_POST['username']) && !preg_match('/^[a-z0-9A-Z_]+$/', $_POST['username'])){
            $_SESSION['flash']['alert'] = 'Username is not valid (Alphanumeric)';
        } else {
            $request = $link->prepare('UPDATE users SET username = :username, modified_at = NOW() WHERE id = :id');
            $request->execute([
                'username' => $_POST['username'],
                'id' => $user_id
            ]);

            $request = $link->prepare('SELECT * FROM users WHERE id = :id');
            $request->execute(['id' => $result->id]);
            $result = $request->fetch();
            $_SESSION['user_infos'] = $result;
            $_SESSION['flash']['success'] = 'Your Username has been successfully modified';
        }
    } else {
        $_SESSION['flash']['alert'] = 'You need to wait 1 Day before changing your Username';
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
<div class="login-box">
    <h2>Change Username</h2>
    <form action="" method="post">
        <div class="user-box">
            <input type="text" name="username" required="">
            <label>New Username</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
