<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar arrays si no existen
if (!isset($_SESSION['libros'])) {
    $_SESSION['libros'] = [];
}
if (!isset($_SESSION['miembros'])) {
    $_SESSION['miembros'] = [];
}

// Construir estructura completa
$data = [
    'libros'   => $_SESSION['libros'],
    'miembros' => $_SESSION['miembros']
];

// Guardar en fichero JSON
$file = __DIR__ . '/data/data.json';
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Location: index.php?status=exported');
exit;
