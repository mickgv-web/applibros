<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="site-header">
    <div class="logo">
        <a href="index.php">
            <img src="img/logo.png" alt="Logo del sitio">
            <span>App Libros</span>
        </a>
    </div>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <?php if (!empty($_SESSION['usuario_id'])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="user-actions">
        <?php if (!empty($_SESSION['login'])): ?>
            <div class="dropdown">
                <button class="dropdown-toggle">
                    <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['login']); ?>
                </button>
                <div class="dropdown-menu">
                    <a href="dashboard.php"><i class="fa-solid fa-table-columns"></i> Mi Dashboard</a>
                    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn-login"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a>
            <a href="register.php" class="btn-register"><i class="fa-solid fa-user-plus"></i> Registrarse</a>
        <?php endif; ?>
    </div>
</header>
