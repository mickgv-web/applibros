<?php
require_once 'includes/db_connect.php';

if (!isset($_GET['id'])) {
    die("Libro no especificado.");
}

$id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT titulo, autor, sinopsis, portada FROM libro WHERE libro_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$libro = $result->fetch_assoc();

if (!$libro) {
    die("Libro no encontrado.");
}

$title = $libro['titulo'];

ob_start(); ?>
<section class="detalle-libro">
    <div class="detalle-grid">
        <!-- Portada -->
        <div class="detalle-portada">
            <?php if (!empty($libro['portada'])): ?>
                <img src="<?php echo htmlspecialchars($libro['portada']); ?>" alt="<?php echo htmlspecialchars($libro['titulo']); ?>">
            <?php else: ?>
                <div class="placeholder-portada">Sin portada</div>
            <?php endif; ?>
        </div>

        <!-- Información -->
        <div class="detalle-info">
            <h2><?php echo htmlspecialchars($libro['titulo']); ?></h2>
            <?php if (!empty($libro['autor'])): ?>
                <p><strong>Autor:</strong> <?php echo htmlspecialchars($libro['autor']); ?></p>
            <?php endif; ?>
            <p><?php echo nl2br(htmlspecialchars($libro['sinopsis'])); ?></p>

            <div class="acciones">
                <a href="index.php" class="btn btn--secondary">Volver al catálogo</a>
                <a href="edit-book.php?id=<?php echo $id; ?>" class="btn btn--primary">Editar</a>
            </div>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include 'layout.php';
