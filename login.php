<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db_connect.php'; // conexión con config.php

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario !== '' && $password !== '') {
        // Buscar usuario en la BD
        $stmt = $conn->prepare("SELECT usuario_id, nombre, password FROM usuario WHERE nombre = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Verificar contraseña con password_verify
            if (password_verify($password, $row['password'])) {
                // Login correcto → guardar datos en sesión
                $_SESSION['usuario_id'] = $row['usuario_id'];
                $_SESSION['login'] = $row['nombre'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Debes introducir usuario y contraseña.";
    }
}

$title = "Login";

ob_start(); ?>
<div class="formulario-login">
    <h2>Iniciar sesión</h2>
    <form action="login.php" method="post">
        <label for="usuario">Nombre de usuario</label>
        <input type="text" id="usuario" name="usuario" required>
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Entrar">
    </form>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- Botón de acceso al registro -->
    <div class="acciones">
        <a href="register.php" class="btn-secondary">¿No tienes cuenta? Regístrate aquí</a>
    </div>
</div>
<?php
$content = ob_get_clean();
include 'layout.php';
