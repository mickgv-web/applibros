<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar estructura de miembros si no existe
if (!isset($_SESSION['miembros'])) {
    $_SESSION['miembros'] = [];
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario !== '' && $password !== '') {
        if (isset($_SESSION['miembros'][$usuario]) && $_SESSION['miembros'][$usuario] === $password) {
            // Login correcto
            $_SESSION['login'] = $usuario;
            header("Location: dashboard.php"); // redirige al dashboard
            exit;
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
