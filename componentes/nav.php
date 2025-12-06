<?php
if (defined('NAV_INCLUIDO')) {
    return;
}
define('NAV_INCLUIDO', true);

$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$user_name = $logged_in ? $_SESSION['user_name'] : '';

$cart_count = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $cart_count += isset($item['cantidad']) ? $item['cantidad'] : 1;
    }
}
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

    <a href="<?php echo isset($basePath)
                    ? $basePath . 'views/carrito.php'
                    : './views/carrito.php'; ?>"
        class="cart-link"
        <?php echo isset($currentPage) && $currentPage === 'carrito' ? 'class="active cart-link"' : 'class="cart-link"'; ?>>
        Carrito
        <?php if ($cart_count > 0): ?>
            <span class="cart-count"><?= $cart_count ?></span>
        <?php endif; ?>
    </a>

    <?php if ($logged_in): ?>
        <div class="dropdown">
            <a href="#" class="dropbtn">
                <!-- <span class="user-icon">👤</span> -->
                <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
                <span class="dropdown-arrow">▼</span>
            </a>
            <div class="dropdown-content">
                <a href="<?php echo isset($basePath)
                                ? $basePath . 'views/mi-cuenta.php'
                                : './views/mi-cuenta.php'; ?>"
                    <?php echo isset($currentPage) && $currentPage === 'mi-cuenta' ? 'class="active"' : ''; ?>>
                    Mi Cuenta
                </a>
                <a href="<?php echo isset($basePath)
                                ? $basePath . 'php/logout.php'
                                : './php/logout.php'; ?>">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    <?php else: ?>
        <a href="<?php echo isset($basePath)
                        ? $basePath . 'views/login.php'
                        : './views/login.php'; ?>"
            <?php echo isset($currentPage) && $currentPage === 'login' ? 'class="active"' : ''; ?>>
            Iniciar sesión
        </a>
    <?php endif; ?>

    <!-- <a href="<?php echo isset($basePath)
                        ? $basePath . 'views/contacto.php'
                        : './views/contacto.php'; ?>"
        <?php echo isset($currentPage) && $currentPage === 'contacto' ? 'class="active"' : ''; ?>>
        Contacto
    </a> -->
</nav>

<link rel="stylesheet" href="<?php echo isset($basePath) ? $basePath . 'css/dropdown.css' : '../css/dropdown.css'; ?>" />
<script src="<?php echo isset($basePath) ? $basePath . 'js/dropdown.js' : '../js/dropdown.js'; ?>" defer></script>