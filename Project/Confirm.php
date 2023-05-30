<?php

session_start();
require_once 'Include/Config.php';

$user_id = $_GET['id'];
$token = $_GET['token'];

$request = $link->prepare('SELECT * FROM users WHERE id = :id');
$request->execute(['id' => $user_id]);
$result = $request->fetch();

if ($result && $result->confirmation_token == $token){
    $request = $link->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = :id');
    $_SESSION['flash']['success'] = 'Your Account has been successfully confirmed';
    $_SESSION['user_infos'] = $result;
    $request->execute(['id' => $user_id]);
    header('Location: Account.php');
} else{
    $_SESSION['flash']['alert'] = 'Token is not valid';
    header('Location: Login.php');
}