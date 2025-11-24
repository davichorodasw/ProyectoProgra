<nav>
    <a href="<?php echo isset($basePath)
        ? $basePath . "index.php"
        : "./index.php"; ?>" <?php echo isset($currentPage) &&
$currentPage === "inicio"
    ? 'class="active"'
    : ""; ?>>Inicio</a>
    <a href="<?php echo isset($basePath)
        ? $basePath . "views/cds.php"
        : "./views/cds.php"; ?>" <?php echo isset($currentPage) &&
$currentPage === "cds"
    ? 'class="active"'
    : ""; ?>>CDs</a>
    <a href="<?php echo isset($basePath)
        ? $basePath . "views/vinilos.php"
        : "./views/vinilos.php"; ?>" <?php echo isset($currentPage) &&
$currentPage === "vinilos"
    ? 'class="active"'
    : ""; ?>>Vinilos</a>
    <a href="/login">Iniciar sesión</a>
    <a href="/contacto">Contacto</a>
</nav>