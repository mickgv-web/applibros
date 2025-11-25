<?php
require_once 'includes/require_login.php';

if (!isset($_SESSION['libros'])) {
    $_SESSION['libros'] = [];
}

$user = $_SESSION['login'];
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo   = trim($_POST['titulo'] ?? '');
    $sinopsis = trim($_POST['sinopsis'] ?? '');
    $imagen   = $_FILES['imagen'] ?? null;

    if ($titulo !== '' && $sinopsis !== '' && $imagen && $imagen['error'] === UPLOAD_ERR_OK) {
        // Validar tipo MIME permitido
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
        if (in_array($imagen['type'], $tiposPermitidos)) {
            $destino = 'uploads/' . basename($imagen['name']);
            if (move_uploaded_file($imagen['tmp_name'], $destino)) {
                // Inicializar array de libros del usuario si no existe
                if (!isset($_SESSION['libros'][$user])) {
                    $_SESSION['libros'][$user] = [];
                }
                $_SESSION['libros'][$user][] = [
                    'titulo'   => $titulo,
                    'sinopsis' => $sinopsis,
                    'imagen'   => $destino
                ];
                // Redirigir al dashboard con estado "added"
                header("Location: dashboard.php?status=added");
                exit;
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
        // Campos incompletos o sin imagen
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
