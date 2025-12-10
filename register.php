<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db_connect.php'; // conexión usando config.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['usuario'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');

    // Validaciones básicas
    if ($usuario !== '' && $email !== '' && $password !== '' && $password2 !== '') {
        if ($password !== $password2) {
            header("Location: register.php?status=pass_mismatch");
            exit;
        }

        // Comprobar si ya existe usuario o email
        $stmt = $conn->prepare("SELECT usuario_id FROM usuario WHERE nombre = ? OR email = ?");
        $stmt->bind_param("ss", $usuario, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Usuario o email ya existe
            header("Location: register.php?status=exists");
            exit;
        }

        // Insertar nuevo usuario con password hash
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuario (nombre, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $usuario, $hash, $email);

        if ($stmt->execute()) {
            $_SESSION['usuario_id'] = $stmt->insert_id;
            $_SESSION['login'] = $usuario;
            header("Location: dashboard.php?status=registered");
            exit;
        } else {
            header("Location: register.php?status=error");
            exit;
        }
    } else {
        header("Location: register.php?status=error");
        exit;
    }
}

$title = "Registro de Usuario";

ob_start(); ?>
<div class="formulario-login">
    <h2>Registro de nuevo usuario</h2>
    <form action="register.php" method="post">
        <label for="usuario">Nombre de usuario</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <label for="password2">Repite la contraseña</label>
        <input type="password" id="password2" name="password2" required>

        <input type="submit" value="Registrar">
    </form>

    <!-- Mensajes en caso de error -->
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'error'): ?>
            <div class="alert error">Ha ocurrido un error en el registro. Inténtalo de nuevo.</div>
        <?php elseif ($_GET['status'] === 'pass_mismatch'): ?>
            <div class="alert error">Las contraseñas no coinciden.</div>
        <?php elseif ($_GET['status'] === 'exists'): ?>
            <div class="alert error">El usuario o email ya existe.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include 'layout.php';
