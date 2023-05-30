<?php

session_start();
setcookie('remember', NULL, -1);
unset($_SESSION['user_infos']);
$_SESSION['flash']['success'] = 'You are now disconnected';
header('Location: Login.php');
