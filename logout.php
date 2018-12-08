<?php
    session_start();
    unset($_SESSION);
    session_destroy();
    $_SESSION = array();
    setcookie();
    header('Location: login.php');
?>