<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = "Mis Pedidos - Ritmo Retro";
$currentPage = "mi-cuenta";

$cssPath = "../css/styles.css";
$extraCss = ["../css/todos-pedidos.css"];
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";

require_once '../php/conexion.php';
$conn = conectarDB();

$usuario_id = $_SESSION['user_id'];
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$stmt_total = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total FROM pedidos WHERE usuario_id = ?"
);
mysqli_stmt_bind_param($stmt_total, "i", $usuario_id);
mysqli_stmt_execute($stmt_total);
$result_total = mysqli_stmt_get_result($stmt_total);
$total_rows = mysqli_fetch_assoc($result_total)['total'];
$total_pages = ceil($total_rows / $limit);
mysqli_stmt_close($stmt_total);

$stmt = mysqli_prepare(
    $conn,
    "SELECT p.*, 
        (SELECT SUM(dp.cantidad) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) AS total_items,
        (SELECT COUNT(*) FROM detalles_pedido dp WHERE dp.pedido_id = p.id) AS total_productos
    FROM pedidos p
    WHERE p.usuario_id = ?
    ORDER BY p.fecha_pedido DESC
    LIMIT ? OFFSET ?"
);
mysqli_stmt_bind_param($stmt, "iii", $usuario_id, $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pedidos = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content todos-pedidos-page">
    <div class="page-header">
        <h1>Mis Pedidos</h1>
        <p>Sigue el estado y progreso de tus pedidos</p>
    </div>

    <div class="admin-content" style="margin: 0 auto; max-width: 1200px;">
        <div class="table-container">
            <table class="pedidos-table">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Detalles</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pedidos)): ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state">
                                    <div class="empty-icon">📭</div>
                                    <h3>No tienes pedidos registrados</h3>
                                    <p>Cuando compres algo, aparecerá aquí.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td class="pedido-id">#<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></td>

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
                                    <span class="status-select status-<?= $pedido['estado'] ?>" style="pointer-events:none;">
                                        <?= ucfirst($pedido['estado']) ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="mis_pedidos_detalle.php?id=<?= $pedido['id'] ?>"
                                        class="filter-btn"
                                        style="padding:6px 12px; text-decoration:none;">
                                        Ver detalles
                                    </a>
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
                    <a href="?page=<?= $page - 1 ?>" class="page-link">← Anterior</a>
                <?php endif; ?>

                <span class="page-info">
                    Página <?= $page ?> de <?= $total_pages ?>
                </span>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="page-link">Siguiente →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>