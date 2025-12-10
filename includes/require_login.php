<?php
// Aseguramos que la sesión esté activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Comprobamos si hay usuario logueado (id y nombre)
if (empty($_SESSION['usuario_id']) || empty($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
