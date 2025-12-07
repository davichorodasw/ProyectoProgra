<?php
if (defined('NAV_INCLUIDO')) {
    return;
}
define('NAV_INCLUIDO', true);

if (!defined('BASE_PATH')) {
    $pathsFiles = [
        __DIR__ . '/../config/paths.php',
        __DIR__ . '/../../config/paths.php'
    ];

    foreach ($pathsFiles as $pathsFile) {
        if (file_exists($pathsFile)) {
            require_once $pathsFile;
            break;
        }
    }

    if (!defined('BASE_PATH')) {
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
}

$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$user_name = $logged_in ? ($_SESSION['user_name'] ?? 'Usuario') : '';
$user_role = $logged_in && isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'user';

$cart_count = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $cart_count += $item['cantidad'] ?? 1;
    }
}

$current_script = $_SERVER['PHP_SELF'] ?? '';
$active_page = '';

if (strpos($current_script, 'index.php') !== false) {
    $active_page = 'inicio';
} elseif (strpos($current_script, 'cds.php') !== false) {
    $active_page = 'cds';
} elseif (strpos($current_script, 'vinilos.php') !== false) {
    $active_page = 'vinilos';
} elseif (strpos($current_script, 'carrito.php') !== false) {
    $active_page = 'carrito';
} elseif (strpos($current_script, 'login.php') !== false) {
    $active_page = 'login';
} elseif (strpos($current_script, 'mi-cuenta.php') !== false) {
    $active_page = 'mi-cuenta';
} elseif (strpos($current_script, 'admin_dashboard.php') !== false) {
    $active_page = 'admin_dashboard';
}
?>

<nav class="main-nav">
    <a href="<?php echo url('index.php'); ?>"
        class="nav-link <?php echo $active_page === 'inicio' ? 'active' : ''; ?>">
        Inicio
    </a>

    <a href="<?php echo url('views/cds.php'); ?>"
        class="nav-link <?php echo $active_page === 'cds' ? 'active' : ''; ?>">
        CDs
    </a>

    <a href="<?php echo url('views/vinilos.php'); ?>"
        class="nav-link <?php echo $active_page === 'vinilos' ? 'active' : ''; ?>">
        Vinilos
    </a>

    <?php if (!$logged_in || ($logged_in && $user_role !== 'admin')): ?>
        <a href="<?php echo url('views/carrito.php'); ?>"
            class="nav-link cart-link <?php echo $active_page === 'carrito' ? 'active' : ''; ?>">
            Carrito
            <?php if ($cart_count > 0): ?>
                <span class="cart-badge"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </a>
    <?php endif; ?>

    <?php if ($logged_in): ?>
        <div class="dropdown" id="userDropdownContainer">
            <button class="dropbtn" id="userDropdownBtn">
                <span class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
                    <?php if ($user_role === 'admin'): ?>
                        <span class="admin-indicator" title="Administrador">👑</span>
                    <?php endif; ?>
                    <span class="dropdown-arrow">▼</span>
                </span>
            </button>
            <div class="dropdown-content" id="userDropdownContent">
                <?php if ($user_role === 'admin'): ?>
                    <a href="<?php echo url('views/admin_dashboard.php'); ?>"
                        class="dropdown-item <?php echo $active_page === 'admin_dashboard' ? 'active' : ''; ?>">
                        <span class="item-icon">📊</span>
                        <span class="item-text">Panel Admin</span>
                    </a>
                <?php endif; ?>

                <a href="<?php echo url('views/mi-cuenta.php'); ?>"
                    class="dropdown-item <?php echo $active_page === 'mi-cuenta' ? 'active' : ''; ?>">
                    <span class="item-icon">👤</span>
                    <span class="item-text">Mi Cuenta</span>
                </a>

                <a href="<?php echo url('php/logout.php'); ?>" class="dropdown-item logout-item">
                    <span class="item-icon">🚪</span>
                    <span class="item-text">Cerrar Sesión</span>
                </a>
            </div>
        </div>
    <?php else: ?>
        <a href="<?php echo url('views/login.php'); ?>"
            class="nav-link login-link <?php echo $active_page === 'login' ? 'active' : ''; ?>">
            Iniciar Sesión
        </a>
    <?php endif; ?>
</nav>

<script>
    console.log('BASE_URL:', '<?php echo BASE_URL; ?>');
    console.log('Document location:', window.location.href);
</script>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/dropdown.css">
<script src="<?php echo BASE_URL; ?>js/dropdown.js" defer></script>

<script>
    console.log('Dropdown resources loaded for page:', '<?php echo $active_page; ?>');
</script>