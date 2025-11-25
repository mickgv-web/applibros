<header class="site-header">
    <div class="logo">
        <a href="index.php">
            <img src="img/logo.png" alt="Logo del sitio">
        </a>
        <span>App Libros</span>
    </div>
    <div class="user-actions">
        <?php if (!empty($_SESSION['login'])): ?>
            <div class="dropdown">
                <button class="dropdown-toggle">
                    <?php echo htmlspecialchars($_SESSION['login']); ?>
                </button>
                <div class="dropdown-menu">
                    <a href="dashboard.php">Mi Dashboard</a>
                    <a href="logout.php">Cerrar sesión</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn-login">Iniciar sesión</a>
        <?php endif; ?>
    </div>
</header>
