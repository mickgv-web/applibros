<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar la estructura de miembros si no existe
if (!isset($_SESSION['miembros'])) {
    $_SESSION['miembros'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario !== '' && $password !== '') {
        if (isset($_SESSION['miembros'][$usuario])) {
            // Usuario ya existe → redirigir con error
            header("Location: register.php?status=error");
            exit;
        } else {
            // Guardamos el usuario y contraseña en la sesión
            $_SESSION['miembros'][$usuario] = $password;

            // Iniciar sesión automáticamente
            $_SESSION['login'] = $usuario;

            // Redirigir al dashboard con estado "registered"
            header("Location: dashboard.php?status=registered");
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
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Registrar">
        </form>

        <!-- Mensajes en caso de error -->
        <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
            <div class="alert error">Ha ocurrido un error en el registro. Inténtalo de nuevo.</div>
        <?php endif; ?>
    </div>
<?php
$content = ob_get_clean();

include 'layout.php';
