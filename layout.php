<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = $title ?? "App Libros";
$content = $content ?? "<p>Contenido vacío</p>";
$scripts = $scripts ?? []; // JS opcionales por página
$styles  = $styles  ?? []; // CSS opcionales por página
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    <?php foreach ($styles as $href): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($href); ?>">
    <?php endforeach; ?>
    <script src="js/header.js" defer></script>
    <?php foreach ($scripts as $src): ?>
        <script src="<?php echo htmlspecialchars($src); ?>" defer></script>
    <?php endforeach; ?>    
</head>
<body>
    <?php require_once 'includes/header.php'; ?>

    <main class="admin-container">
        <?php echo $content; ?>
    </main>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>