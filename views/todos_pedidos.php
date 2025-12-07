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

$query .= " ORDER BY p.fecha_pedido DESC LIMIT ? OFFSET ?";
$count_params = $params;
$count_types = $types;

$stmt_count = mysqli_prepare($conn, $count_query);
if (!empty($count_params)) {
    mysqli_stmt_bind_param($stmt_count, $count_types, ...$count_params);
}
mysqli_stmt_execute($stmt_count);
$result_count = mysqli_stmt_get_result($stmt_count);
$total_rows = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_rows / $limit);
mysqli_stmt_close($stmt_count);

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

$stats_query = "SELECT 
    COUNT(*) as total_pedidos,
    SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) as completados,
    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) as cancelados,
    SUM(total) as ingresos_totales,
    AVG(total) as promedio_compra
    FROM pedidos";

if (!empty($params)) {
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
    (function() {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 400px;
    `;
        document.body.appendChild(toastContainer);

        window.showToast = function(message, type = 'info', duration = 3000) {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            let icon = 'ℹ';
            if (type === 'success') icon = '✓';
            if (type === 'error') icon = '✗';
            if (type === 'warning') icon = '⚠';

            toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-message">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;

            toast.style.cssText = `
            background: ${type === 'success' ? '#4CAF50' : 
                         type === 'error' ? '#f44336' : 
                         type === 'warning' ? '#ff9800' : '#2196F3'};
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: toastSlideIn 0.3s ease;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            min-width: 300px;
            max-width: 400px;
        `;

            const toastStyle = document.createElement('style');
            if (!document.querySelector('#toast-styles')) {
                toastStyle.id = 'toast-styles';
                toastStyle.textContent = `
                @keyframes toastSlideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes toastSlideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                .toast-icon {
                    font-size: 18px;
                    font-weight: bold;
                    flex-shrink: 0;
                }
                .toast-message {
                    flex: 1;
                    line-height: 1.4;
                }
                .toast-close {
                    background: none;
                    border: none;
                    color: white;
                    font-size: 20px;
                    cursor: pointer;
                    padding: 0;
                    line-height: 1;
                    flex-shrink: 0;
                    opacity: 0.8;
                }
                .toast-close:hover {
                    opacity: 1;
                }
            `;
                document.head.appendChild(toastStyle);
            }

            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'toastSlideOut 0.3s ease';
                setTimeout(() => {
                    if (toast.parentNode) toast.remove();
                }, 300);
            }, duration);
        };

        window.showSuccessToast = function(message, duration = 3000) {
            showToast(message, 'success', duration);
        };

        window.showErrorToast = function(message, duration = 5000) {
            showToast(message, 'error', duration);
        };
    })();

    function actualizarEstado(pedidoId, nuevoEstado) {
        if (!confirm('¿Cambiar estado del pedido #' + pedidoId.toString().padStart(6, '0') + ' a "' + nuevoEstado + '"?')) {
            return;
        }

        const formData = new FormData();
        formData.append('pedido_id', pedidoId);
        formData.append('estado', nuevoEstado);

        const select = document.querySelector(`select[data-pedido-id="${pedidoId}"]`);
        const originalState = select.value;
        select.disabled = true;
        select.style.opacity = '0.7';

        fetch('../php/actualizar_estado_pedido.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                select.disabled = false;
                select.style.opacity = '1';

                if (data.success) {
                    select.classList.remove('status-pendiente', 'status-procesando', 'status-completado', 'status-cancelado');
                    select.classList.add('status-' + nuevoEstado);
                    select.value = nuevoEstado;

                    showSuccessToast(`Pedido #${pedidoId.toString().padStart(6, '0')} actualizado a: ${nuevoEstado}`);
                } else {
                    select.value = originalState;
                    showErrorToast(data.message || 'Error al actualizar estado');
                }
            })
            .catch(error => {
                select.disabled = false;
                select.style.opacity = '1';
                select.value = originalState;
                showErrorToast('Error de conexión. Intenta nuevamente.');
                console.error('Error:', error);
            });
    }
</script>

<?php include "../componentes/footer.php"; ?>