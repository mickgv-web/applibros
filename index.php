<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['libros'])) {
    $_SESSION['libros'] = [];
}

$title = "Inicio";

ob_start(); ?>
    <!-- Sección de acceso (solo si no hay login) -->
    <?php if (!isset($_SESSION['login'])): ?>
    <section class="acceso">
        <h2>Accede a tu cuenta</h2>
        <p class="intro">Disfruta de todas las funcionalidades registrándote o iniciando sesión.</p>
        <div class="acciones">
            <a href="login.php" class="btn-login">Iniciar Sesión</a>
            <a href="register.php" class="btn-register">Registrarse</a>
        </div>
    </section>
    <?php endif; ?>

    <!-- Sección de libros -->
    <section class="libros">
        <h2>Catálogo de Libros</h2>
        <div class="libros-grid">
            <?php
            $hayLibros = false;
            foreach ($_SESSION['libros'] as $usuario => $librosUsuario) {
                if (!empty($librosUsuario)) {
                    foreach ($librosUsuario as $libro) {
                        $hayLibros = true; ?>
                        <div class="libro-card">
                            <img src="<?php echo htmlspecialchars($libro['imagen']); ?>" alt="<?php echo htmlspecialchars($libro['titulo']); ?>">
                            <h3><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                            <p><?php echo htmlspecialchars($libro['sinopsis']); ?></p>
                        </div>
                    <?php }
                }
            }
            if (!$hayLibros): ?>
                <p>No hay libros registrados todavía.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Botones de exportar/importar -->
    <section class="data-actions">
        <form action="export.php" method="post">
            <button type="submit" class="btn-secondary">Exportar a JSON</button>
        </form>
        <form action="import.php" method="post">
            <button type="submit" class="btn-register">Importar desde JSON</button>
        </form>
    </section>
<?php
$content = ob_get_clean();

include 'layout.php';
