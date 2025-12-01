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

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <!-- Usuario logueado -->
        <a href="<?php echo isset($basePath)
                        ? $basePath . "views/mi-cuenta.php"
                        : "./views/mi-cuenta.php"; ?>" <?php echo isset($currentPage) &&
                                                            $currentPage === "mi-cuenta"
                                                            ? 'class="active"'
                                                            : ""; ?>>Mi Cuenta</a>
        <a href="<?php echo isset($basePath)
                        ? $basePath . "php/logout.php"
                        : "./php/logout.php"; ?>">Cerrar Sesión</a>
    <?php else: ?>
        <!-- Usuario no logueado -->
        <a href="<?php echo isset($basePath)
                        ? $basePath . "views/login.php"
                        : "./views/login.php"; ?>" <?php echo isset($currentPage) &&
                                                        $currentPage === "login"
                                                        ? 'class="active"'
                                                        : ""; ?>>Iniciar sesión</a>
        <?php /*<a href="<?php echo isset($basePath)
                        ? $basePath . "views/register.php"
                        : "./views/register.php"; ?>" <?php echo isset($currentPage) &&
                                                            $currentPage === "register"
                                                            ? 'class="active"'
                                                            : ""; ?>>Registrarse</a> */ ?>
    <?php endif; ?>

    <a href="<?php echo isset($basePath)
                    ? $basePath . "views/contacto.php"
                    : "./views/contacto.php"; ?>" <?php echo isset($currentPage) &&
                                                        $currentPage === "contacto"
                                                        ? 'class="active"'
                                                        : ""; ?>>Contacto</a>
</nav>