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

// Obtener índice del libro a editar
$index = isset($_GET['id']) ? (int)$_GET['id'] : null;
if ($index === null || !isset($_SESSION['libros'][$user][$index])) {
    header("Location: dashboard.php?status=error");
    exit;
}

$libro = $_SESSION['libros'][$user][$index];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo   = trim($_POST['titulo'] ?? '');
    $sinopsis = trim($_POST['sinopsis'] ?? '');
    $imagen   = $_FILES['imagen'] ?? null;

    if ($titulo !== '' && $sinopsis !== '') {
        $_SESSION['libros'][$user][$index]['titulo'] = $titulo;
        $_SESSION['libros'][$user][$index]['sinopsis'] = $sinopsis;

        // Si se sube nueva imagen
        if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($imagen['type'], $tiposPermitidos)) {
                $destino = 'uploads/' . basename($imagen['name']);
                if (move_uploaded_file($imagen['tmp_name'], $destino)) {
                    $_SESSION['libros'][$user][$index]['imagen'] = $destino;
                }
            }
        }

        // Redirigir al dashboard con estado "edited"
        header("Location: dashboard.php?status=edited");
        exit;
    } else {
        header("Location: dashboard.php?status=error");
        exit;
    }
}

$title = "Editar Libro";

ob_start(); ?>
<div class="formulario-login">
    <h2>Editar libro</h2>
    <form action="edit-book.php?id=<?php echo $index; ?>" method="post" enctype="multipart/form-data">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>

        <label for="sinopsis">Sinopsis</label>
        <textarea id="sinopsis" name="sinopsis" rows="4" required><?php echo htmlspecialchars($libro['sinopsis']); ?></textarea>

        <label for="imagen">Nueva imagen (opcional)</label>
        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp">

        <input type="submit" value="Guardar cambios" class="btn-add">
    </form>

    <!-- Botón para volver al dashboard -->
    <div class="acciones">
        <a href="dashboard.php" class="btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver al Dashboard</a>
    </div>
</div>
<?php
$content = ob_get_clean();
include 'layout.php';
