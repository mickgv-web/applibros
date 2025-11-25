<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['login']); // eliminar usuario actual
header("Location: login.php");
exit;
