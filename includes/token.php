<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

function gerarToken()
{
    return $_SESSION['token'];
}

function validarToken($token)
{
    return isset($_SESSION['token']) && hash_equals($_SESSION['token'], $token);
}
?>
