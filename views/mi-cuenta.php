<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ./login.php?error=Por+favor+inicia+sesión+para+acceder+a+esta+página');
    exit;
}

$pageTitle = "Mi Cuenta - Ritmo Retro";
$currentPage = "mi-cuenta";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
$additionalCSS = ["css/mi-cuenta.css"];
$additionalJS = ["js/mi-cuenta.js"];

require_once '../php/conexion.php';
require_once '../php/manejoUsuarios.php';
$conn = conectarDB();

$usuario_id = $_SESSION['user_id'];

$notificacion = null;

// Obtener datos actuales del usuario
$user = obtenerPerfilUsuario($usuario_id);

// Procesar edición de perfil
if (isset($_POST['editar_perfil'])) {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Validar email único (excepto el propio)
    if (verificarEmailParaActualizar($email, $usuario_id)) {
        $notificacion = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'El email ya está en uso.'
        ];
    } elseif (empty($nombre) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $notificacion = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Datos inválidos.'
        ];
    } else {
        if (actualizarPerfilUsuario($usuario_id, $nombre, $email)) {
            $_SESSION['user_name'] = $nombre;  // Actualizar sesión
            $notificacion = [
                'type' => 'success',
                'title' => 'Éxito',
                'message' => 'Perfil actualizado correctamente.'
            ];
            // Refrescar datos
            $user['nombre'] = $nombre;
            $user['email'] = $email;
        } else {
            $notificacion = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No se pudo actualizar el perfil.'
            ];
        }
    }
}

// Procesar cambio de contraseña
if (isset($_POST['cambiar_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Obtener password actual
    $row_pass = obtenerPasswordUsuario($usuario_id);

    if (!password_verify($old_password, $row_pass['password'])) {
        $notificacion = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Contraseña actual incorrecta.'
        ];
    } elseif ($new_password !== $confirm_password) {
        $notificacion = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Las nuevas contraseñas no coinciden.'
        ];
    } elseif (strlen($new_password) < 8) {
        $notificacion = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'La nueva contraseña debe tener al menos 8 caracteres.'
        ];
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        if (actualizarPasswordUsuario($usuario_id, $hashed_password)) {
            $notificacion = [
                'type' => 'success',
                'title' => 'Éxito',
                'message' => 'Contraseña actualizada correctamente.'
            ];
        } else {
            $notificacion = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No se pudo actualizar la contraseña.'
            ];
        }
    }
}

$pedidos = obtenerPedidosUsuarioResumen($usuario_id, 10);

mysqli_close($conn);

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content mi-cuenta-page">
    <div class="page-header">
        <h1>Mi Cuenta</h1>
        <p>Gestiona tu información y pedidos</p>
    </div>

    <?php if ($notificacion): ?>
        <div id="php-notification"
            data-type="<?= htmlspecialchars($notificacion['type']) ?>"
            data-title="<?= htmlspecialchars($notificacion['title']) ?>"
            data-message="<?= htmlspecialchars($notificacion['message']) ?>"
            style="display: none;">
        </div>
        <script>
            if (typeof checkForPHPNotification === 'function') {
                checkForPHPNotification();
            }
        </script>
    <?php endif; ?>

    <div class="account-container">
        <div class="account-sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <span class="avatar-icon">👤</span>
                </div>
                <h3><?= htmlspecialchars($_SESSION['user_name']) ?></h3>
            </div>

            <nav class="account-nav">
                <a href="#mis-datos" class="nav-item active" data-target="mis-datos">
                    <span class="nav-icon">📋</span>
                    Mis Datos
                </a>
                <?php if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'): ?>
                    <a href="#mis-pedidos" class="nav-item" data-target="mis-pedidos">
                        <span class="nav-icon">📦</span>
                        Mis Pedidos
                    </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="#admin-panel" class="nav-item" data-target="admin-panel">
                        <span class="nav-icon">⚙️</span>
                        Panel Admin
                    </a>
                <?php endif; ?>
                <a href="../php/logout.php" class="nav-item logout">
                    <span class="nav-icon">🚪</span>
                    Cerrar Sesión
                </a>
            </nav>
        </div>

        <div class="account-content">
            <div id="mis-datos" class="content-section active">
                <h2>Información Personal</h2>

                <form method="POST" class="info-form">
                    <div class="info-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                    </div>

                    <div class="info-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <button type="submit" name="editar_perfil" class="button button-primary">Guardar Cambios</button>
                </form>

                <h2>Cambiar Contraseña</h2>

                <form method="POST" class="info-form">
                    <div class="info-group">
                        <label for="old_password">Contraseña Actual:</label>
                        <input type="password" id="old_password" name="old_password" required>
                    </div>

                    <div class="info-group">
                        <label for="new_password">Nueva Contraseña:</label>
                        <input type="password" id="new_password" name="new_password" required minlength="8">
                    </div>

                    <div class="info-group">
                        <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                    </div>

                    <button type="submit" name="cambiar_password" class="button button-primary">Cambiar Contraseña</button>
                </form>
            </div>

            <div id="mis-pedidos" class="content-section">
                <h2>Mis Pedidos Recientes</h2>

                <?php if (empty($pedidos)): ?>
                    <div class="no-orders">
                        <span class="no-orders-icon">📭</span>
                        <p>No tienes pedidos recientes</p>
                        <a href="../index.php" class="button button-primary">Ir a la tienda</a>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($pedidos as $pedido): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span class="order-id">Pedido #<?= $pedido['id'] ?></span>
                                    <span class="order-status <?= strtolower($pedido['estado']) ?>">
                                        <?= ucfirst($pedido['estado']) ?>
                                    </span>
                                </div>

                                <div class="order-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Fecha:</span>
                                        <span class="detail-value"><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Total:</span>
                                        <span class="detail-value">$<?= number_format($pedido['total'], 2) ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Productos:</span>
                                        <span class="detail-value"><?= $pedido['total_productos'] ?> (<?= $pedido['total_items'] ?> items)</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Método de pago:</span>
                                        <span class="detail-value"><?= ucfirst($pedido['metodo_pago']) ?></span>
                                    </div>
                                </div>

                                <div class="order-actions">
                                    <a href="detalle_pedido.php?id=<?= $pedido['id'] ?>" class="button button-small button-outline">
                                        <span class="btn-icon">👁️</span>
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="orders-footer">
                        <p>Mostrando <?= count($pedidos) ?> pedidos recientes</p>
                    </div>

                    <div class="orders-footer">
                        <a href="mis_pedidos.php" class="button button-primary">
                            Ver todos mis pedidos
                        </a>
                    </div>

                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <div id="admin-panel" class="content-section">
                    <h2>Panel de Administración</h2>

                    <div class="admin-grid">
                        <a href="crear_cd.php" class="admin-card">
                            <span class="admin-icon">💿</span>
                            <h4>Crear CD</h4>
                            <p>Añadir nuevo producto CD al catálogo</p>
                        </a>

                        <a href="crear_vinilo.php" class="admin-card">
                            <span class="admin-icon">🎵</span>
                            <h4>Crear Vinilo</h4>
                            <p>Añadir nuevo producto vinilo al catálogo</p>
                        </a>

                        <a href="todos_pedidos.php" class="admin-card">
                            <span class="admin-icon">📊</span>
                            <h4>Ver Todos los Pedidos</h4>
                            <p>Gestionar pedidos de todos los usuarios</p>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>