<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$file = __DIR__ . '/data/data.json';
if (file_exists($file)) {
    $jsonData = file_get_contents($file);
    $data = json_decode($jsonData, true);

    if (is_array($data)) {
        if (isset($data['libros']) && is_array($data['libros'])) {
            $_SESSION['libros'] = $data['libros'];
        }
        if (isset($data['miembros']) && is_array($data['miembros'])) {
            $_SESSION['miembros'] = $data['miembros'];
        }
    }
}

header('Location: index.php?status=imported');
exit;
