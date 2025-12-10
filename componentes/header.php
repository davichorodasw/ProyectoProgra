<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle : "Ritmo Retro"; ?></title>

    <?php
    $pathsFiles = [
        __DIR__ . '/../config/paths.php',
        __DIR__ . '/../../config/paths.php'
    ];

    $pathsLoaded = false;
    foreach ($pathsFiles as $pathsFile) {
        if (file_exists($pathsFile)) {
            require_once $pathsFile;
            $pathsLoaded = true;
            break;
        }
    }

    if (!$pathsLoaded) {
        define('BASE_PATH', '/');
        define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/');

        function url($path = '')
        {
            return BASE_PATH . ltrim($path, '/');
        }

        function asset($path)
        {
            return BASE_URL . ltrim($path, '/');
        }
    }
    ?>

    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>" />

    <?php if (isset($currentPage) && $currentPage === "login"): ?>
        <link rel="stylesheet" href="<?php echo asset('css/auth.css'); ?>" />
    <?php endif; ?>

    <?php if (isset($currentPage) && $currentPage === "contacto"): ?>
        <link rel="stylesheet" href="<?php echo asset('css/contacto.css'); ?>" />
    <?php endif; ?>

    <?php if (isset($extraCss)): ?>
        <?php if (is_array($extraCss)): ?>
            <?php foreach ($extraCss as $css): ?>
                <link rel="stylesheet" href="<?= $css ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <link rel="stylesheet" href="<?= $extraCss ?>">
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($additionalCSS) && is_array($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo asset($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>

    <div class="header">
        <div class="header-content">
            <h1>Ritmo Retro</h1>
            <img src="<?php echo asset('img/RitmoRetro.png'); ?>" alt="Logo Ritmo Retro" />
            <h2>En físico, todo es mejor</h2>
        </div>
    </div>

    <script src="<?php echo asset('js/notification.js'); ?>"></script>

    <?php
    if (isset($additionalJS) && is_array($additionalJS)) {
        foreach ($additionalJS as $js) {
            echo "<script src='" . asset($js) . "'></script>";
        }
    }
    ?>

</body>

</html>