<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db_connect.php'; // conexión a la BD

$title = "Inicio";

// Consultar todos los libros (catálogo general)
$stmt = $conn->prepare("SELECT titulo, sinopsis, autor, portada FROM libro ORDER BY libro_id DESC");
$stmt->execute();
$result = $stmt->get_result();
$libros = $result->fetch_all(MYSQLI_ASSOC);

ob_start(); ?>
    <!-- Sección de acceso (solo si no hay login) -->
    <?php if (empty($_SESSION['usuario_id'])): ?>
    <section class="acceso">
        <h2>Accede a tu cuenta</h2>
        <p class="intro">Disfruta de todas las funcionalidades registrándote o iniciando sesión.</p>
        <div class="acciones">
            <a href="login.php" class="btn btn--primary">Iniciar Sesión</a>
            <a href="register.php" class="btn btn--accent">Registrarse</a>
        </div>
    </section>
    <?php else: ?>
    <section class="acceso">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['login']); ?></h2>
        <div class="acciones">
            <a href="dashboard.php" class="btn btn--primary">Ir al Dashboard</a>
            <a href="logout.php" class="btn btn--accent">Cerrar Sesión</a>
        </div>
    </section>
    <?php endif; ?>

    <!-- Sección de libros -->
    <section class="libros">
        <h2>Catálogo de Libros</h2>
        <div class="libros-grid">
            <?php if (!empty($libros)): ?>
                <?php foreach ($libros as $libro): ?>
                    <div class="libro-card">
                        <?php if (!empty($libro['portada'])): ?>
                            <img src="<?php echo htmlspecialchars($libro['portada']); ?>" alt="<?php echo htmlspecialchars($libro['titulo']); ?>">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                        <?php if (!empty($libro['autor'])): ?>
                            <p><strong>Autor:</strong> <?php echo htmlspecialchars($libro['autor']); ?></p>
                        <?php endif; ?>
                        <p><?php echo htmlspecialchars($libro['sinopsis']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay libros registrados todavía.</p>
            <?php endif; ?>
        </div>
    </section>
<?php
$content = ob_get_clean();

include 'layout.php';
