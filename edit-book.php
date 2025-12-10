<?php
require_once 'includes/require_login.php';
require_once 'includes/db_connect.php';

$userId = $_SESSION['usuario_id'];

// Obtener ID del libro a editar
$libroId = isset($_GET['id']) ? (int)$_GET['id'] : null;
if ($libroId === null) {
    header("Location: dashboard.php?status=error");
    exit;
}

// Consultar libro del usuario
$stmt = $conn->prepare("SELECT titulo, sinopsis, autor, portada FROM libro WHERE libro_id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $libroId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$libro = $result->fetch_assoc();

if (!$libro) {
    header("Location: dashboard.php?status=error");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo   = trim($_POST['titulo'] ?? '');
    $autor    = trim($_POST['autor'] ?? '');
    $sinopsis = trim($_POST['sinopsis'] ?? '');
    $imagen   = $_FILES['imagen'] ?? null;

    if ($titulo !== '' && $autor !== '' && $sinopsis !== '') {
        $portada = $libro['portada']; // mantener portada actual

        // Si se sube nueva imagen
        if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($imagen['type'], $tiposPermitidos)) {
                $nombreArchivo = time() . "_" . basename($imagen['name']);
                $destino = 'uploads/' . $nombreArchivo;
                if (move_uploaded_file($imagen['tmp_name'], $destino)) {
                    $portada = $destino;
                }
            }
        }

        // Actualizar libro en la BD
        $stmt = $conn->prepare("UPDATE libro SET titulo = ?, autor = ?, sinopsis = ?, portada = ? WHERE libro_id = ? AND usuario_id = ?");
        $stmt->bind_param("ssssii", $titulo, $autor, $sinopsis, $portada, $libroId, $userId);

        if ($stmt->execute()) {
            header("Location: dashboard.php?status=edited");
            exit;
        } else {
            header("Location: dashboard.php?status=error");
            exit;
        }
    } else {
        header("Location: dashboard.php?status=error");
        exit;
    }
}

$title = "Editar Libro";

ob_start(); ?>
<div class="formulario-login">
    <h2>Editar libro</h2>
    <form action="edit-book.php?id=<?php echo $libroId; ?>" method="post" enctype="multipart/form-data">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($libro['titulo']); ?>" required>

        <label for="autor">Autor</label>
        <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>

        <label for="sinopsis">Sinopsis</label>
        <textarea id="sinopsis" name="sinopsis" rows="4" required><?php echo htmlspecialchars($libro['sinopsis']); ?></textarea>

        <label for="imagen">Nueva imagen (opcional)</label>
        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp">

        <?php if (!empty($libro['portada'])): ?>
            <p>Portada actual:</p>
            <img src="<?php echo htmlspecialchars($libro['portada']); ?>" alt="Portada actual" style="max-width:150px;">
        <?php endif; ?>

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
