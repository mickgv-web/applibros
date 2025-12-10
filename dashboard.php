<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/require_login.php';
require_once 'includes/db_connect.php';

$userId = $_SESSION['usuario_id'];
$user   = $_SESSION['login'];

// Borrar libro por ID
if (isset($_GET['delete'])) {
    $libroId = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM libro WHERE libro_id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $libroId, $userId);

    if ($stmt->execute()) {
        header("Location: dashboard.php?status=deleted");
        exit;
    } else {
        header("Location: dashboard.php?status=error");
        exit;
    }
}

// Consultar libros del usuario
$stmt = $conn->prepare("SELECT libro_id, titulo, sinopsis, autor, portada 
                        FROM libro 
                        WHERE usuario_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$libros = $result->fetch_all(MYSQLI_ASSOC);

$title = "Mi Dashboard";

ob_start(); ?>
<div class="admin-card">
    <h1>Bienvenido, <?php echo htmlspecialchars($user); ?></h1>

    <div class="acciones">
        <a href="add-book.php" class="btn btn--primary">
            <i class="fa-solid fa-plus"></i> Añadir nuevo libro
        </a>
        <a href="logout.php" class="btn btn--secondary">Cerrar sesión</a>
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
                    <div class="acciones">
                        <a href="edit-book.php?id=<?php echo $libro['libro_id']; ?>" class="btn btn--primary">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                        <a href="dashboard.php?delete=<?php echo $libro['libro_id']; ?>" class="btn btn--danger" onclick="return confirm('¿Seguro que deseas borrar este libro?');">
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
