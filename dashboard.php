<?php
require_once 'includes/require_login.php';

if (!isset($_SESSION['libros'])) {
    $_SESSION['libros'] = [];
}

$user = $_SESSION['login'];

// Inicializar array de libros del usuario si no existe
if (!isset($_SESSION['libros'][$user])) {
    $_SESSION['libros'][$user] = [];
}

// Borrar libro por índice
if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    if (isset($_SESSION['libros'][$user][$index])) {
        unset($_SESSION['libros'][$user][$index]);
        $_SESSION['libros'][$user] = array_values($_SESSION['libros'][$user]);
        // Redirigir con estado "deleted"
        header("Location: dashboard.php?status=deleted");
        exit;
    } else {
        header("Location: dashboard.php?status=error");
        exit;
    }
}

$title = "Mi Dashboard";

ob_start(); ?>
<div class="admin-card">
    <h1>Bienvenido, <?php echo htmlspecialchars($user); ?></h1>

    <div class="acciones">
        <a href="add-book.php" class="btn-add">
            <i class="fa-solid fa-plus"></i> Añadir nuevo libro
        </a>
        <a href="logout.php" class="btn-secondary">Cerrar sesión</a>
    </div>

    <!-- Mensajes según parámetro status -->
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'registered'): ?>
            <div class="alert success">Usuario registrado correctamente. ¡Bienvenido al dashboard!</div>
        <?php elseif ($_GET['status'] === 'added'): ?>
            <div class="alert success">Libro añadido correctamente.</div>
        <?php elseif ($_GET['status'] === 'deleted'): ?>
            <div class="alert success">Libro borrado correctamente.</div>
        <?php elseif ($_GET['status'] === 'edited'): ?>
            <div class="alert success">Libro editado correctamente.</div>
        <?php elseif ($_GET['status'] === 'error'): ?>
            <div class="alert error">Ha ocurrido un error en la operación.</div>
        <?php endif; ?>
    <?php endif; ?>

    <h2>Mis Libros</h2>
    <div class="libros-grid">
        <?php if (!empty($_SESSION['libros'][$user])): ?>
            <?php foreach ($_SESSION['libros'][$user] as $index => $libro): ?>
                <div class="libro-card">
                    <img src="<?php echo htmlspecialchars($libro['imagen']); ?>" alt="<?php echo htmlspecialchars($libro['titulo']); ?>">
                    <h3><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($libro['sinopsis']); ?></p>
                    <div class="acciones-libro">   
                        <a href="edit-book.php?id=<?php echo $index; ?>" class="acciones-button edit-icon">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                        <a href="dashboard.php?delete=<?php echo $index; ?>" class="acciones-button delete-icon">
                            <i class="fa-solid fa-xmark"></i> Borrar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No has añadido ningún libro todavía.</p>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include 'layout.php';
