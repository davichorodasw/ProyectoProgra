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
    <a href="<?php echo isset($basePath)
        ? $basePath . "views/login.php"
        : "./views/login.php"; ?>" <?php echo isset($currentPage) &&
$currentPage === "login"
    ? 'class="active"'
    : ""; ?>>Iniciar sesión</a>
    <a href="<?php echo isset($basePath)
        ? $basePath . "views/contacto.php"
        : "./views/contacto.php"; ?>" <?php echo isset($currentPage) &&
$currentPage === "contacto"
    ? 'class="active"'
    : ""; ?>>Contacto</a>
</nav>