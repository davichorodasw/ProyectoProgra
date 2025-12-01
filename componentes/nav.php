<?php
// componentes/nav.php
if (defined('NAV_INCLUIDO')) {
    return;
}
define('NAV_INCLUIDO', true);

$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$user_name = $logged_in ? $_SESSION['user_name'] : '';
?>
<nav>
    <a href="<?php echo isset($basePath)
                    ? $basePath . 'index.php'
                    : './index.php'; ?>"
        <?php echo isset($currentPage) && $currentPage === 'inicio' ? 'class="active"' : ''; ?>>
        Inicio
    </a>

    <a href="<?php echo isset($basePath)
                    ? $basePath . 'views/cds.php'
                    : './views/cds.php'; ?>"
        <?php echo isset($currentPage) && $currentPage === 'cds' ? 'class="active"' : ''; ?>>
        CDs
    </a>

    <a href="<?php echo isset($basePath)
                    ? $basePath . 'views/vinilos.php'
                    : './views/vinilos.php'; ?>"
        <?php echo isset($currentPage) && $currentPage === 'vinilos' ? 'class="active"' : ''; ?>>
        Vinilos
    </a>

    <?php if ($logged_in): ?>
        <!-- Menú desplegable para usuario logueado -->
        <div class="dropdown">
            <a href="#" class="dropbtn">
                <span class="user-icon">👤</span>
                <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
                <span class="dropdown-arrow">▼</span>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo isset($basePath)
                                ? $basePath . 'views/mi-cuenta.php'
                                : './views/mi-cuenta.php'; ?>"
                    <?php echo isset($currentPage) && $currentPage === 'mi-cuenta' ? 'class="active"' : ''; ?>>
                    <span class="dropdown-icon">📋</span> Mi Cuenta
                </a>
                <a href="<?php echo isset($basePath)
                                ? $basePath . 'php/logout.php'
                                : './php/logout.php'; ?>">
                    <span class="dropdown-icon">🚪</span> Cerrar Sesión
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Usuario no logueado -->
        <a href="<?php echo isset($basePath)
                        ? $basePath . 'views/login.php'
                        : './views/login.php'; ?>"
            <?php echo isset($currentPage) && $currentPage === 'login' ? 'class="active"' : ''; ?>>
            Iniciar sesión
        </a>
    <?php endif; ?>

    <a href="<?php echo isset($basePath)
                    ? $basePath . 'views/contacto.php'
                    : './views/contacto.php'; ?>"
        <?php echo isset($currentPage) && $currentPage === 'contacto' ? 'class="active"' : ''; ?>>
        Contacto
    </a>
</nav>

<!-- Incluir CSS y JS del dropdown -->
<link rel="stylesheet" href="<?php echo isset($basePath) ? $basePath . 'css/dropdown.css' : '../css/dropdown.css'; ?>" />
<script src="<?php echo isset($basePath) ? $basePath . 'js/dropdown.js' : '../js/dropdown.js'; ?>" defer></script>