<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db_connect.php'; // conexión a la BD

$title = "Inicio";

// Construcción dinámica de la consulta
$sql = "SELECT libro_id, titulo, sinopsis, autor, portada FROM libro WHERE 1=1";
$params = [];
$types  = "";

// Filtros dinámicos
if (!empty($_GET['titulo'])) {
    $sql .= " AND titulo LIKE ?";
    $params[] = "%" . $_GET['titulo'] . "%";
    $types   .= "s";
}

if (!empty($_GET['autor'])) {
    $sql .= " AND autor LIKE ?";
    $params[] = "%" . $_GET['autor'] . "%";
    $types   .= "s";
}

$sql .= " ORDER BY libro_id DESC";
$stmt = $conn->prepare($sql);

// Bind dinámico si hay parámetros
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$libros = $result->fetch_all(MYSQLI_ASSOC);

ob_start(); ?>
<!-- Sección de libros -->
<section class="libros">
    <h2>Catálogo de Libros</h2>

    <!-- Buscador -->
    <form method="get" class="buscador">
        <input type="text" name="titulo" placeholder="Buscar por título"
            value="<?php echo htmlspecialchars($_GET['titulo'] ?? ''); ?>">
        <input type="text" name="autor" placeholder="Buscar por autor"
            value="<?php echo htmlspecialchars($_GET['autor'] ?? ''); ?>">
        <button type="submit" class="btn btn--primary">Buscar</button>
    </form>

    <div class="libros-grid">
        <?php if (!empty($libros)): ?>
            <?php foreach ($libros as $libro): ?>
                <div class="libro-card">
                    <a href="book.php?id=<?php echo $libro['libro_id']; ?>" class="libro-link">
                        <?php if (!empty($libro['portada'])): ?>
                            <img src="<?php echo htmlspecialchars($libro['portada']); ?>" alt="<?php echo htmlspecialchars($libro['titulo']); ?>">
                        <?php endif; ?>
                        <h3><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                        <?php if (!empty($libro['autor'])): ?>
                            <p><strong>Autor:</strong> <?php echo htmlspecialchars($libro['autor']); ?></p>
                        <?php endif; ?>
                        <p><?php echo htmlspecialchars($libro['sinopsis']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No se encontraron libros con esos criterios.</p>
        <?php endif; ?>
    </div>
</section>
<?php
$content = ob_get_clean();

include 'layout.php';
