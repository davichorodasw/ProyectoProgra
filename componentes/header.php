<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle : "Ritmo Retro"; ?></title>
    <link rel="stylesheet" href="<?php echo isset($cssPath)
        ? $cssPath
        : "css/styles.css"; ?>" />
    <?php if (isset($currentPage) && $currentPage === "login"): ?>
        <link rel="stylesheet" href="<?php echo isset($cssPath)
            ? str_replace("styles.css", "auth.css", $cssPath)
            : "css/auth.css"; ?>" />
    <?php endif; ?>
</head>
<body>
    
<div class="header">
    <div class="header-content">
        <h1>Ritmo Retro</h1>
        <img src="<?php echo isset($imgPath)
            ? $imgPath
            : "img/RitmoRetro.png"; ?>" alt="Logo Ritmo Retro" />
        <h2>En físico, todo es mejor</h2>
    </div>
</div>