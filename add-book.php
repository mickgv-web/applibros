<?php
require_once 'includes/require_login.php';
require_once 'includes/db_connect.php'; // conexión a la BD

$userId = $_SESSION['usuario_id'];
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo   = trim($_POST['titulo'] ?? '');
    $sinopsis = trim($_POST['sinopsis'] ?? '');
    $autor    = trim($_POST['autor'] ?? '');
    $imagen   = $_FILES['imagen'] ?? null;

    if ($titulo !== '' && $sinopsis !== '' && $autor !== '' && $imagen && $imagen['error'] === UPLOAD_ERR_OK) {
        // Validar tipo MIME permitido
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
        if (in_array($imagen['type'], $tiposPermitidos)) {
            // Crear nombre único para la imagen
            $nombreArchivo = time() . "_" . basename($imagen['name']);
            $destino = 'uploads/' . $nombreArchivo;

            if (move_uploaded_file($imagen['tmp_name'], $destino)) {
                // Insertar libro en la BD
                $stmt = $conn->prepare("INSERT INTO libro (titulo, sinopsis, autor, portada, usuario_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $titulo, $sinopsis, $autor, $destino, $userId);

                if ($stmt->execute()) {
                    header("Location: dashboard.php?status=added");
                    exit;
                } else {
                    header("Location: dashboard.php?status=error");
                    exit;
                }
            } else {
                // Error al mover archivo
                header("Location: dashboard.php?status=error");
                exit;
            }
        } else {
            // Formato no permitido
            header("Location: dashboard.php?status=error");
            exit;
        }
    } else {
        // Campos incompletos
        header("Location: dashboard.php?status=error");
        exit;
    }
}

$title = "Añadir Libro";

ob_start(); ?>
<div class="formulario-login">
    <h2>Dar de alta un nuevo libro</h2>
    <form action="add-book.php" method="post" enctype="multipart/form-data">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="autor">Autor</label>
        <input type="text" id="autor" name="autor" required>

        <label for="sinopsis">Sinopsis</label>
        <textarea id="sinopsis" name="sinopsis" rows="4" required></textarea>

        <label for="imagen">Imagen (JPG, PNG o WebP)</label>
        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp" required>

        <input type="submit" value="Añadir libro">
    </form>
</div>
<?php
$content = ob_get_clean();
include 'layout.php';
