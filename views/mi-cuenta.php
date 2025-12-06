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
$extraCss = "../css/mi-cuenta.css";

require_once '../php/conexion.php';
$conn = conectarDB();

$usuario_id = $_SESSION['user_id'];

$query = "SELECT p.*, 
                 (SELECT COUNT(*) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) as total_productos,
                 (SELECT SUM(dp.cantidad) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) as total_items
          FROM pedidos p 
          WHERE p.usuario_id = ? 
          ORDER BY p.fecha_pedido DESC 
          LIMIT 10";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$pedidos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pedidos[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content mi-cuenta-page">
    <div class="page-header">
        <h1>Mi Cuenta</h1>
        <p>Gestiona tu información y pedidos</p>
    </div>

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
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user'): ?>
                    <a href="#mis-pedidos" class="nav-item" data-target="mis-pedidos">
                        <span class="nav-icon=">📦</span>
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

                <div class="info-card">
                    <div class="info-row">
                        <span class="info-label">Nombre completo:</span>
                        <span class="info-value"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?= htmlspecialchars($_SESSION['user_email']) ?></span>
                    </div>
                </div>

                <div class="account-actions">
                    <button class="button button-outline" onclick="alert('Funcionalidad en desarrollo')">
                        <span class="btn-icon">✏️</span>
                        Editar Perfil
                    </button>
                    <button class="button button-outline" onclick="alert('Funcionalidad en desarrollo')">
                        <span class="btn-icon">🔒</span>
                        Cambiar Contraseña
                    </button>
                </div>
            </div>

            <div id="mis-pedidos" class="content-section">
                <div class="section-header">
                    <h2>Mis Pedidos</h2>
                    <p>Historial de tus compras recientes</p>
                </div>

                <?php if (empty($pedidos)): ?>
                    <div class="empty-orders">
                        <div class="empty-icon">📦</div>
                        <h3>Aún no tienes pedidos</h3>
                        <p>Cuando realices una compra, aparecerá aquí tu historial de pedidos.</p>
                        <a href="cds.php" class="button button-red">
                            <span class="btn-icon">🛒</span>
                            Comenzar a Comprar
                        </a>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($pedidos as $pedido): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div class="order-info">
                                        <h4>Pedido #<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></h4>
                                        <p class="order-date"><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></p>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge status-<?= $pedido['estado'] ?>">
                                            <?= ucfirst($pedido['estado']) ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="order-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Productos:</span>
                                        <span class="detail-value"><?= $pedido['total_productos'] ?> productos</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Items totales:</span>
                                        <span class="detail-value"><?= $pedido['total_items'] ?> unidades</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Total:</span>
                                        <span class="detail-value total-amount">$<?= number_format($pedido['total'], 2) ?></span>
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
                                    <!--<?php if ($pedido['estado'] === 'pendiente'): ?>
                                        <button class="button button-small" onclick="alert('Funcionalidad en desarrollo')">
                                            <span class="btn-icon">❌</span>
                                            Cancelar Pedido
                                        </button>
                                    <?php endif; ?>-->
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="orders-footer">
                        <p>Mostrando <?= count($pedidos) ?> pedidos recientes</p>
                        <a href="todos_pedidos.php" class="button button-link">
                            Ver todos los pedidos →
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

<link rel="stylesheet" href="../css/mi-cuenta.css">
<script src="../js/mi-cuenta.js"></script>

<?php include "../componentes/footer.php"; ?>