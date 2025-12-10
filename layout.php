<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title   = $title   ?? "App Libros";
$content = $content ?? "<p>Contenido vacío</p>";
$scripts = $scripts ?? []; // JS opcionales por página
$styles  = $styles  ?? []; // CSS opcionales por página
$messages = $messages ?? []; // Mensajes globales opcionales
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <meta name="description" content="Aplicación de gestión de libros en PHP/MySQL">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Fuentes y estilos globales -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">

    <!-- Estilos adicionales por página -->
    <?php foreach ($styles as $href): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($href); ?>">
    <?php endforeach; ?>

    <!-- Scripts globales -->
    <script src="js/header.js" defer></script>

    <!-- Scripts adicionales por página -->
    <?php foreach ($scripts as $src): ?>
        <script src="<?php echo htmlspecialchars($src); ?>" defer></script>
    <?php endforeach; ?>
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <!-- Mensajes globales -->
    <?php if (!empty($messages)): ?>
        <div class="global-messages">
            <?php foreach ($messages as $msg): ?>
                <div class="alert <?php echo htmlspecialchars($msg['type']); ?>">
                    <?php echo htmlspecialchars($msg['text']); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <main class="main-container">
        <?php echo $content; ?>
    </main>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
