<?php
session_start();

if (
    !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true ||
    !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'
) {
    header('Location: ../index.php?error=Acceso+no+autorizado');
    exit;
}

$pageTitle = "Dashboard Admin - Ritmo Retro";
$currentPage = "admin";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
$extraCss = "../css/admin.css";

require_once '../php/conexion.php';
$conn = conectarDB();

$queries = [
    'total_productos' => "SELECT COUNT(*) as total FROM productos",
    'total_cds' => "SELECT COUNT(*) as total FROM productos WHERE tipo = 'cd'",
    'total_vinilos' => "SELECT COUNT(*) as total FROM productos WHERE tipo = 'vinilo'",
    'productos_bajo_stock' => "SELECT COUNT(*) as total FROM productos WHERE stock < 10",
    'total_ventas' => "SELECT COUNT(*) as total FROM pedidos WHERE estado = 'completado'",
    'ventas_pendientes' => "SELECT COUNT(*) as total FROM pedidos WHERE estado = 'pendiente'",
    'total_usuarios' => "SELECT COUNT(*) as total FROM usuarios",
    'ingresos_totales' => "SELECT SUM(total) as total FROM pedidos WHERE estado = 'completado'"
];

$estadisticas = [];
foreach ($queries as $key => $query) {
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $estadisticas[$key] = $row['total'] ?? 0;
    } else {
        $estadisticas[$key] = 0;
    }
}

// Últimos pedidos
$query_pedidos = "SELECT p.*, u.nombre as cliente 
                  FROM pedidos p 
                  JOIN usuarios u ON p.usuario_id = u.id 
                  ORDER BY p.fecha_pedido DESC 
                  LIMIT 5";
$result_pedidos = mysqli_query($conn, $query_pedidos);
$ultimos_pedidos = mysqli_fetch_all($result_pedidos, MYSQLI_ASSOC);

// Productos con bajo stock
$query_bajo_stock = "SELECT * FROM productos WHERE stock < 10 ORDER BY stock ASC LIMIT 5";
$result_bajo_stock = mysqli_query($conn, $query_bajo_stock);
$bajo_stock = mysqli_fetch_all($result_bajo_stock, MYSQLI_ASSOC);

// Últimos productos añadidos
$query_ultimos_productos = "SELECT * FROM productos ORDER BY id DESC LIMIT 5";
$result_ultimos_productos = mysqli_query($conn, $query_ultimos_productos);
$ultimos_productos = mysqli_fetch_all($result_ultimos_productos, MYSQLI_ASSOC);

mysqli_close($conn);

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content admin-dashboard">
    <div class="page-header">
        <h1>Dashboard Administrativo</h1>
        <p>Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?></p>
    </div>

    <div class="admin-container">
        <!-- Sidebar de navegación admin -->
        <div class="admin-sidebar">
            <nav class="admin-nav">
                <a href="admin_dashboard.php" class="nav-item active">
                    <span class="nav-icon">📊</span>
                    Dashboard
                </a>
                <a href="gestion_productos.php" class="nav-item">
                    <span class="nav-icon">📦</span>
                    Productos
                </a>
                <a href="todos_pedidos.php" class="nav-item">
                    <span class="nav-icon">🛒</span>
                    Pedidos
                </a>
                <a href="gestion_usuarios.php" class="nav-item">
                    <span class="nav-icon">👥</span>
                    Usuarios
                </a>
                <a href="crear_cd.php" class="nav-item">
                    <span class="nav-icon">💿</span>
                    Nuevo CD
                </a>
                <a href="crear_vinilo.php" class="nav-item">
                    <span class="nav-icon">🎵</span>
                    Nuevo Vinilo
                </a>
                <a href="../index.php" class="nav-item">
                    <span class="nav-icon">🏠</span>
                    Volver a Tienda
                </a>
                <a href="../php/logout.php" class="nav-item logout">
                    <span class="nav-icon">🚪</span>
                    Cerrar Sesión
                </a>
            </nav>
        </div>

        <!-- Contenido principal -->
        <div class="admin-content">
            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #3498db;">
                        📦
                    </div>
                    <div class="stat-info">
                        <h3><?= $estadisticas['total_productos'] ?></h3>
                        <p>Productos Total</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #9b59b6;">
                        💿
                    </div>
                    <div class="stat-info">
                        <h3><?= $estadisticas['total_cds'] ?></h3>
                        <p>CDs</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #1abc9c;">
                        🎵
                    </div>
                    <div class="stat-info">
                        <h3><?= $estadisticas['total_vinilos'] ?></h3>
                        <p>Vinilos</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #e74c3c;">
                        ⚠️
                    </div>
                    <div class="stat-info">
                        <h3><?= $estadisticas['productos_bajo_stock'] ?></h3>
                        <p>Stock Bajo</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #2ecc71;">
                        🛒
                    </div>
                    <div class="stat-info">
                        <h3><?= $estadisticas['total_ventas'] ?></h3>
                        <p>Ventas</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #f39c12;">
                        ⏳
                    </div>
                    <div class="stat-info">
                        <h3><?= $estadisticas['ventas_pendientes'] ?></h3>
                        <p>Pedidos Pendientes</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #34495e;">
                        👥
                    </div>
                    <div class="stat-info">
                        <h3><?= $estadisticas['total_usuarios'] ?></h3>
                        <p>Usuarios</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background-color: #27ae60;">
                        💰
                    </div>
                    <div class="stat-info">
                        <h3>$<?= number_format($estadisticas['ingresos_totales'] ?? 0, 2) ?></h3>
                        <p>Ingresos Totales</p>
                    </div>
                </div>
            </div>

            <!-- Secciones principales -->
            <div class="content-grid">
                <!-- Últimos pedidos -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Últimos Pedidos</h3>
                        <a href="todos_pedidos.php" class="ver-todo">Ver todos →</a>
                    </div>

                    <?php if (empty($ultimos_pedidos)): ?>
                        <p class="empty-message">No hay pedidos recientes</p>
                    <?php else: ?>
                        <div class="pedidos-list">
                            <?php foreach ($ultimos_pedidos as $pedido): ?>
                                <div class="pedido-item">
                                    <div class="pedido-info">
                                        <h4>Pedido #<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></h4>
                                        <p class="pedido-cliente"><?= htmlspecialchars($pedido['cliente']) ?></p>
                                        <p class="pedido-fecha"><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></p>
                                    </div>
                                    <div class="pedido-estado">
                                        <span class="status-badge status-<?= $pedido['estado'] ?>">
                                            <?= ucfirst($pedido['estado']) ?>
                                        </span>
                                        <span class="pedido-total">$<?= number_format($pedido['total'], 2) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Productos con bajo stock -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Productos con Bajo Stock</h3>
                        <a href="gestion_productos.php?filter=low-stock" class="ver-todo">Ver todos →</a>
                    </div>

                    <?php if (empty($bajo_stock)): ?>
                        <p class="empty-message">Todos los productos tienen stock suficiente</p>
                    <?php else: ?>
                        <div class="stock-list">
                            <?php foreach ($bajo_stock as $producto): ?>
                                <div class="stock-item">
                                    <div class="stock-info">
                                        <h4><?= htmlspecialchars($producto['titulo']) ?></h4>
                                        <p class="stock-artista"><?= htmlspecialchars($producto['artista']) ?></p>
                                        <p class="stock-tipo"><?= $producto['tipo'] == 'cd' ? 'CD' : 'Vinilo' ?></p>
                                    </div>
                                    <div class="stock-cantidad <?= $producto['stock'] < 5 ? 'stock-critico' : '' ?>">
                                        <span class="stock-label">Stock:</span>
                                        <span class="stock-value"><?= $producto['stock'] ?></span>
                                    </div>
                                    <div class="stock-acciones">
                                        <a href="editar_producto.php?id=<?= $producto['id'] ?>" class="btn-small">
                                            <span class="btn-icon">✏️</span>
                                            Editar
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Últimos productos añadidos -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Últimos Productos Añadidos</h3>
                        <a href="gestion_productos.php" class="ver-todo">Ver todos →</a>
                    </div>

                    <?php if (empty($ultimos_productos)): ?>
                        <p class="empty-message">No hay productos</p>
                    <?php else: ?>
                        <div class="productos-list">
                            <?php foreach ($ultimos_productos as $producto): ?>
                                <div class="producto-item">
                                    <div class="producto-imagen">
                                        <img src="../img/covers/<?= htmlspecialchars($producto['imagen']) ?>"
                                            alt="<?= htmlspecialchars($producto['titulo']) ?>"
                                            onerror="this.src='../img/covers/default.png'">
                                    </div>
                                    <div class="producto-info">
                                        <h4><?= htmlspecialchars($producto['titulo']) ?></h4>
                                        <p class="producto-artista"><?= htmlspecialchars($producto['artista']) ?></p>
                                        <div class="producto-details">
                                            <span class="producto-tipo"><?= $producto['tipo'] == 'cd' ? 'CD' : 'Vinilo' ?></span>
                                            <span class="producto-precio">$<?= number_format($producto['precio'], 2) ?></span>
                                            <span class="producto-stock">Stock: <?= $producto['stock'] ?></span>
                                        </div>
                                    </div>
                                    <div class="producto-acciones">
                                        <a href="editar_producto.php?id=<?= $producto['id'] ?>" class="btn-icon-small" title="Editar">
                                            ✏️
                                        </a>
                                        <a href="borrar_producto.php?id=<?= $producto['id'] ?>" class="btn-icon-small btn-danger" title="Eliminar">
                                            🗑️
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Acciones rápidas -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Acciones Rápidas</h3>
                    </div>
                    <div class="quick-actions">
                        <a href="crear_cd.php" class="quick-action">
                            <span class="action-icon">💿</span>
                            <span class="action-text">Nuevo CD</span>
                        </a>
                        <a href="crear_vinilo.php" class="quick-action">
                            <span class="action-icon">🎵</span>
                            <span class="action-text">Nuevo Vinilo</span>
                        </a>
                        <a href="gestion_productos.php" class="quick-action">
                            <span class="action-icon">📋</span>
                            <span class="action-text">Gestionar Productos</span>
                        </a>
                        <a href="todos_pedidos.php" class="quick-action">
                            <span class="action-icon">📊</span>
                            <span class="action-text">Ver Reportes</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<link rel="stylesheet" href="../css/admin.css">
<script src="../js/admin.js"></script>

<?php include "../componentes/footer.php"; ?>