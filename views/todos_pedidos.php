<?php
session_start();

if (
    !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true ||
    !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'
) {
    header('Location: ../index.php?error=Acceso+no+autorizado');
    exit;
}

$pageTitle = "Todos los Pedidos - Ritmo Retro";
$currentPage = "admin";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
$extraCss = ["../css/admin.css", "../css/todos-pedidos.css"];

require_once '../php/conexion.php';
$conn = conectarDB();

$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';
$estado = $_GET['estado'] ?? '';
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 25;
$offset = ($page - 1) * $limit;

// Construir consulta base
$query = "SELECT p.*, u.nombre as cliente_nombre, u.email as cliente_email, 
                 (SELECT COUNT(*) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) as total_productos,
                 (SELECT SUM(dp.cantidad) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) as total_items
          FROM pedidos p 
          JOIN usuarios u ON p.usuario_id = u.id 
          WHERE 1=1";

$count_query = "SELECT COUNT(*) as total 
                FROM pedidos p 
                JOIN usuarios u ON p.usuario_id = u.id 
                WHERE 1=1";

// Aplicar filtros
$params = [];
$types = "";

if ($search) {
    $search_term = "%" . mysqli_real_escape_string($conn, $search) . "%";
    $query .= " AND (u.nombre LIKE ? OR u.email LIKE ? OR p.id LIKE ?)";
    $count_query .= " AND (u.nombre LIKE ? OR u.email LIKE ? OR p.id LIKE ?)";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "sss";
}

if ($estado && $estado !== 'all') {
    $query .= " AND p.estado = ?";
    $count_query .= " AND p.estado = ?";
    $params[] = $estado;
    $types .= "s";
}

if ($fecha_inicio) {
    $query .= " AND DATE(p.fecha_pedido) >= ?";
    $count_query .= " AND DATE(p.fecha_pedido) >= ?";
    $params[] = $fecha_inicio;
    $types .= "s";
}

if ($fecha_fin) {
    $query .= " AND DATE(p.fecha_pedido) <= ?";
    $count_query .= " AND DATE(p.fecha_pedido) <= ?";
    $params[] = $fecha_fin;
    $types .= "s";
}

if ($filter === 'recent') {
    $query .= " AND p.fecha_pedido >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $count_query .= " AND p.fecha_pedido >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($filter === 'pending') {
    $query .= " AND p.estado = 'pendiente'";
    $count_query .= " AND p.estado = 'pendiente'";
} elseif ($filter === 'completed') {
    $query .= " AND p.estado = 'completado'";
    $count_query .= " AND p.estado = 'completado'";
} elseif ($filter === 'cancelled') {
    $query .= " AND p.estado = 'cancelado'";
    $count_query .= " AND p.estado = 'cancelado'";
} elseif ($filter === 'high-value') {
    $query .= " AND p.total >= 100";
    $count_query .= " AND p.total >= 100";
}

// Ordenar y limitar
$query .= " ORDER BY p.fecha_pedido DESC LIMIT ? OFFSET ?";
$count_params = $params;
$count_types = $types;

// Contar total de registros
$stmt_count = mysqli_prepare($conn, $count_query);
if (!empty($count_params)) {
    mysqli_stmt_bind_param($stmt_count, $count_types, ...$count_params);
}
mysqli_stmt_execute($stmt_count);
$result_count = mysqli_stmt_get_result($stmt_count);
$total_rows = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_rows / $limit);
mysqli_stmt_close($stmt_count);

// Obtener pedidos
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pedidos = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Estadísticas
$stats_query = "SELECT 
    COUNT(*) as total_pedidos,
    SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) as completados,
    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) as cancelados,
    SUM(total) as ingresos_totales,
    AVG(total) as promedio_compra
    FROM pedidos";

if (!empty($params)) {
    // Remover los parámetros de limit y offset para las estadísticas
    $stats_params = array_slice($params, 0, -2);
    $stats_types = substr($types, 0, -2);

    $stats_query = str_replace("WHERE 1=1", "WHERE 1=1", $stats_query);
    $stats_query .= substr($query, strpos($query, "WHERE 1=1") + 10);
    $stats_query = str_replace("ORDER BY p.fecha_pedido DESC LIMIT ? OFFSET ?", "", $stats_query);

    $stmt_stats = mysqli_prepare($conn, $stats_query);
    if (!empty($stats_params)) {
        mysqli_stmt_bind_param($stmt_stats, $stats_types, ...$stats_params);
    }
} else {
    $stmt_stats = mysqli_prepare($conn, $stats_query);
}

mysqli_stmt_execute($stmt_stats);
$result_stats = mysqli_stmt_get_result($stmt_stats);
$estadisticas = mysqli_fetch_assoc($result_stats);

mysqli_stmt_close($stmt);
mysqli_stmt_close($stmt_stats);
mysqli_close($conn);

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content admin-dashboard todos-pedidos-page">
    <div class="page-header">
        <h1>Todos los Pedidos</h1>
        <p>Gestiona y visualiza todos los pedidos del sistema</p>
    </div>

    <div class="admin-container">
        <?php include "admin_sidebar.php"; ?>

        <div class="admin-content">
            <!-- Estadísticas rápidas -->
            <div class="stats-cards">
                <div class="stat-card-mini">
                    <span class="stat-icon-mini">📊</span>
                    <span class="stat-value-mini"><?= $estadisticas['total_pedidos'] ?? 0 ?></span>
                    <p class="stat-label-mini">Total Pedidos</p>
                </div>
                <div class="stat-card-mini">
                    <span class="stat-icon-mini">💰</span>
                    <span class="stat-value-mini">$<?= number_format($estadisticas['ingresos_totales'] ?? 0, 2) ?></span>
                    <p class="stat-label-mini">Ingresos Totales</p>
                </div>
                <div class="stat-card-mini">
                    <span class="stat-icon-mini">✅</span>
                    <span class="stat-value-mini"><?= $estadisticas['completados'] ?? 0 ?></span>
                    <p class="stat-label-mini">Completados</p>
                </div>
                <div class="stat-card-mini">
                    <span class="stat-icon-mini">⏳</span>
                    <span class="stat-value-mini"><?= $estadisticas['pendientes'] ?? 0 ?></span>
                    <p class="stat-label-mini">Pendientes</p>
                </div>
            </div>

            <!-- Barra de herramientas -->
            <div class="toolbar">
                <div class="search-box">
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Buscar por cliente o ID..."
                            value="<?= htmlspecialchars($search) ?>">
                        <select name="estado">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" <?= $estado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="procesando" <?= $estado === 'procesando' ? 'selected' : '' ?>>Procesando</option>
                            <option value="completado" <?= $estado === 'completado' ? 'selected' : '' ?>>Completado</option>
                            <option value="cancelado" <?= $estado === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                        <button type="submit">
                            <span class="search-icon">🔍</span>
                            Buscar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filtros rápidos -->
            <div class="filters">
                <a href="?filter=all<?= $search ? '&search=' . urlencode($search) : '' ?><?= $estado ? '&estado=' . urlencode($estado) : '' ?>"
                    class="filter-btn <?= $filter === 'all' ? 'active' : '' ?>">
                    Todos
                </a>
                <a href="?filter=recent<?= $search ? '&search=' . urlencode($search) : '' ?><?= $estado ? '&estado=' . urlencode($estado) : '' ?>"
                    class="filter-btn <?= $filter === 'recent' ? 'active' : '' ?>">
                    Últimos 7 días
                </a>
                <a href="?filter=pending<?= $search ? '&search=' . urlencode($search) : '' ?><?= $estado ? '&estado=' . urlencode($estado) : '' ?>"
                    class="filter-btn <?= $filter === 'pending' ? 'active' : '' ?>">
                    Pendientes
                </a>
                <a href="?filter=completed<?= $search ? '&search=' . urlencode($search) : '' ?><?= $estado ? '&estado=' . urlencode($estado) : '' ?>"
                    class="filter-btn <?= $filter === 'completed' ? 'active' : '' ?>">
                    Completados
                </a>
                <a href="?filter=high-value<?= $search ? '&search=' . urlencode($search) : '' ?><?= $estado ? '&estado=' . urlencode($estado) : '' ?>"
                    class="filter-btn <?= $filter === 'high-value' ? 'active' : '' ?>">
                    +$100
                </a>
            </div>

            <!-- Tabla de pedidos -->
            <div class="table-container">
                <table class="pedidos-table">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Cliente</th>
                            <th>Detalles</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidos)): ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <div class="empty-icon">📦</div>
                                        <h3>No se encontraron pedidos</h3>
                                        <p>No hay pedidos que coincidan con los criterios de búsqueda.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr>
                                    <td class="pedido-id">#<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <div class="pedido-cliente">
                                            <span class="cliente-nombre"><?= htmlspecialchars($pedido['cliente_nombre']) ?></span>
                                            <span class="cliente-email"><?= htmlspecialchars($pedido['cliente_email']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pedido-detalles">
                                            <div class="detalle-item">
                                                <span class="detalle-label">Productos:</span>
                                                <span class="detalle-value"><?= $pedido['total_productos'] ?></span>
                                            </div>
                                            <div class="detalle-item">
                                                <span class="detalle-label">Items:</span>
                                                <span class="detalle-value"><?= $pedido['total_items'] ?></span>
                                            </div>
                                            <div class="detalle-item">
                                                <span class="detalle-label">Pago:</span>
                                                <span class="detalle-value"><?= ucfirst($pedido['metodo_pago']) ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pedido-fecha">
                                            <span class="fecha-completa"><?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?></span>
                                            <span class="fecha-hora"><?= date('H:i:s', strtotime($pedido['fecha_pedido'])) ?></span>
                                        </div>
                                    </td>
                                    <td class="pedido-total">$<?= number_format($pedido['total'], 2) ?></td>
                                    <td>
                                        <div class="estado-container">
                                            <select class="status-select status-<?= $pedido['estado'] ?>"
                                                data-pedido-id="<?= $pedido['id'] ?>"
                                                onchange="actualizarEstado(<?= $pedido['id'] ?>, this.value)">
                                                <option value="pendiente" <?= $pedido['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                <option value="procesando" <?= $pedido['estado'] === 'procesando' ? 'selected' : '' ?>>Procesando</option>
                                                <option value="completado" <?= $pedido['estado'] === 'completado' ? 'selected' : '' ?>>Completado</option>
                                                <option value="cancelado" <?= $pedido['estado'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                            </select>
                                            <small style="color: #6c757d; font-size: 0.8rem;">
                                                Última actualización: <?= date('d/m/Y', strtotime($pedido['fecha_actualizacion'] ?? $pedido['fecha_pedido'])) ?>
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&filter=<?= $filter ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $estado ? '&estado=' . urlencode($estado) : '' ?>"
                            class="page-link">← Anterior</a>
                    <?php endif; ?>

                    <span class="page-info">
                        Página <?= $page ?> de <?= $total_pages ?>
                    </span>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <a href="?page=<?= $i ?>&filter=<?= $filter ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $estado ? '&estado=' . urlencode($estado) : '' ?>"
                            class="page-link <?= $i === $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>&filter=<?= $filter ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $estado ? '&estado=' . urlencode($estado) : '' ?>"
                            class="page-link">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    function actualizarEstado(pedidoId, nuevoEstado) {
        if (confirm('¿Cambiar estado del pedido #' + pedidoId.toString().padStart(6, '0') + ' a ' + nuevoEstado + '?')) {
            fetch('../php/actualizar_estado_pedido.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'pedido_id=' + pedidoId + '&estado=' + nuevoEstado
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar clase del select
                        const select = document.querySelector(`select[data-pedido-id="${pedidoId}"]`);
                        select.className = 'status-select status-' + nuevoEstado;

                        // Mostrar notificación
                        alert('Estado actualizado correctamente');
                    } else {
                        alert('Error: ' + data.message);
                        // Revertir al estado anterior
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar el estado');
                    location.reload();
                });
        } else {
            // Revertir al estado anterior
            location.reload();
        }
    }
</script>

<?php include "../componentes/footer.php"; ?>