<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle : "Ritmo Retro"; ?></title>

    <!-- Cargar configuración de rutas -->
    <?php
    // Intentar cargar paths.php desde diferentes ubicaciones
    $pathsFiles = [
        __DIR__ . '/../config/paths.php',
        __DIR__ . '/../../config/paths.php',
        'config/paths.php'
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
        // Fallback si no existe paths.php
        define('BASE_PATH', '/php/Proyecto1puro/ProyectoProgra/');
        function url($path = '')
        {
            return BASE_PATH . ltrim($path, '/');
        }
        function asset($path)
        {
            return BASE_PATH . ltrim($path, '/');
        }
    }
    ?>

    <!-- Estilos principales -->
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>" />

    <?php if (isset($currentPage) && $currentPage === "login"): ?>
        <link rel="stylesheet" href="<?php echo asset('css/auth.css'); ?>" />
    <?php endif; ?>

    <?php if (isset($currentPage) && $currentPage === "contacto"): ?>
        <link rel="stylesheet" href="<?php echo asset('css/contacto.css'); ?>" />
    <?php endif; ?>

    <?php if (isset($extraCss)): ?>
        <link rel="stylesheet" href="<?= $extraCss ?>">
    <?php endif; ?>

    <!-- NO cargamos dropdown.css aquí, se cargará en nav.php -->
</head>

<body>

    <div class="header">
        <div class="header-content">
            <h1>Ritmo Retro</h1>
            <img src="<?php echo asset('img/RitmoRetro.png'); ?>" alt="Logo Ritmo Retro" />
            <h2>En físico, todo es mejor</h2>
        </div>
    </div>