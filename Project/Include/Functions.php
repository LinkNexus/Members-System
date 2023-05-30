<?php

function debug($variable)
{
    echo '<pre>' . print_r($variable, true) . '</pre>';
}

function str_random($length)
{
    $alphabet = '0123456789qwertzuiopasdfghjklyxcvbnmQWERTZUIOPASDFGHJKLYXCVBNM';
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function verify_if_logged()
{
    if (!isset($_SESSION['user_infos'])) {
        $_SESSION['flash']['alert'] = 'You are not yet authorized to access this page';
        header('Location: Login.php');
        exit();
    }
}

function reconnect_from_cookie()
{
    if (isset($_COOKIE['remember']) && !isset($_SESSION['user_infos'])) {
        require 'Include/Config.php';
        if (!isset($link)) {
            global $link;
        }
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        $request = $link->prepare('SELECT * FROM users WHERE id = :id');
        $request->execute(['id' => $user_id]);
        $result = $request->fetch();
        if ($result) {
            $expected = $user_id . '==' . $result->remember_token . sha1($user_id . 'TheBlog');
            if ($expected == $remember_token) {
                $_SESSION['user_infos'] = $result;
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
            } else {
                setcookie('remember', NULL, -1);
            }
        } else {
            setcookie('remember', NULL, -1);
        }
    }
}
