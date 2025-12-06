<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ./login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: mi-cuenta.php');
    exit;
}

$pedido_id = intval($_GET['id']);

require_once '../php/conexion.php';
$conn = conectarDB();

$query_pedido = "SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email 
                 FROM pedidos p 
                 JOIN usuarios u ON p.usuario_id = u.id 
                 WHERE p.id = ? AND (p.usuario_id = ? OR ? = 'admin')";

$usuario_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'] ?? 'user';
$is_admin = ($user_role === 'admin');

$stmt_pedido = mysqli_prepare($conn, $query_pedido);
mysqli_stmt_bind_param($stmt_pedido, "iis", $pedido_id, $usuario_id, $user_role);
mysqli_stmt_execute($stmt_pedido);
$result_pedido = mysqli_stmt_get_result($stmt_pedido);

if (mysqli_num_rows($result_pedido) === 0) {
    mysqli_close($conn);
    header('Location: mi-cuenta.php?error=Pedido+no+encontrado');
    exit;
}

$pedido = mysqli_fetch_assoc($result_pedido);
mysqli_stmt_close($stmt_pedido);

$query_detalles = "SELECT dp.*, pr.titulo, pr.artista, pr.imagen, pr.tipo 
                   FROM detalles_pedido dp 
                   JOIN productos pr ON dp.producto_id = pr.id 
                   WHERE dp.pedido_id = ?";
$stmt_detalles = mysqli_prepare($conn, $query_detalles);
mysqli_stmt_bind_param($stmt_detalles, "i", $pedido_id);
mysqli_stmt_execute($stmt_detalles);
$result_detalles = mysqli_stmt_get_result($stmt_detalles);

$detalles = [];
while ($row = mysqli_fetch_assoc($result_detalles)) {
    $detalles[] = $row;
}

mysqli_stmt_close($stmt_detalles);
mysqli_close($conn);

$pageTitle = "Detalle del Pedido - Ritmo Retro";
$currentPage = "mi-cuenta";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content detalle-pedido-page">
    <div class="page-header">
        <div class="header-content">
            <h1>Detalle del Pedido</h1>
            <p>Pedido #<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></p>
        </div>
        <a href="mi-cuenta.php#mis-pedidos" class="button button-outline">
            ← Volver a Mis Pedidos
        </a>
    </div>

    <div class="pedido-container">
        <div class="pedido-info-card">
            <h3>Información del Pedido</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Fecha:</span>
                    <span class="info-value"><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Estado:</span>
                    <span class="info-value status-badge status-<?= $pedido['estado'] ?>">
                        <?= ucfirst($pedido['estado']) ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total:</span>
                    <span class="info-value total-amount">$<?= number_format($pedido['total'], 2) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Método de Pago:</span>
                    <span class="info-value"><?= ucfirst($pedido['metodo_pago']) ?></span>
                </div>
            </div>
        </div>

        <!-- Información de envío -->
        <div class="pedido-info-card">
            <h3>Información de Envío</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Cliente:</span>
                    <span class="info-value"><?= htmlspecialchars($pedido['usuario_nombre']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= htmlspecialchars($pedido['usuario_email']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value"><?= htmlspecialchars($pedido['direccion_envio']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ciudad:</span>
                    <span class="info-value"><?= htmlspecialchars($pedido['ciudad']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Código Postal:</span>
                    <span class="info-value"><?= htmlspecialchars($pedido['codigo_postal']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Teléfono:</span>
                    <span class="info-value"><?= htmlspecialchars($pedido['telefono']) ?></span>
                </div>
            </div>
        </div>

        <!-- Productos del pedido -->
        <div class="pedido-productos">
            <h3>Productos en este pedido</h3>

            <?php if (empty($detalles)): ?>
                <p class="empty-message">No se encontraron productos en este pedido.</p>
            <?php else: ?>
                <div class="productos-list">
                    <?php foreach ($detalles as $detalle): ?>
                        <div class="producto-item">
                            <div class="producto-imagen">
                                <img src="../img/covers/<?= htmlspecialchars($detalle['imagen']) ?>"
                                    alt="<?= htmlspecialchars($detalle['titulo']) ?>"
                                    onerror="this.src='../img/covers/default.jpg'">
                            </div>
                            <div class="producto-info">
                                <h4><?= htmlspecialchars($detalle['titulo']) ?></h4>
                                <p class="producto-artista"><?= htmlspecialchars($detalle['artista']) ?></p>
                                <p class="producto-tipo"><?= $detalle['tipo'] == 'cd' ? 'CD' : 'Vinilo' ?></p>
                            </div>
                            <div class="producto-cantidad">
                                <span class="cantidad-label">Cantidad:</span>
                                <span class="cantidad-value"><?= $detalle['cantidad'] ?></span>
                            </div>
                            <div class="producto-precio">
                                <span class="precio-unitario">$<?= number_format($detalle['precio_unitario'], 2) ?> c/u</span>
                                <span class="subtotal">$<?= number_format($detalle['subtotal'], 2) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="resumen-total">
                    <div class="total-item">
                        <span>Subtotal:</span>
                        <span>$<?= number_format($pedido['total'] - ($pedido['total'] >= 50 ? 0 : 5.00), 2) ?></span>
                    </div>
                    <div class="total-item">
                        <span>Envío:</span>
                        <span><?= $pedido['total'] >= 50 ? 'GRATIS' : '$5.00' ?></span>
                    </div>
                    <div class="total-item total-final">
                        <span>Total pagado:</span>
                        <span class="total-amount">$<?= number_format($pedido['total'], 2) ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($is_admin): ?>
            <div class="admin-actions">
                <h3>Acciones de Administrador</h3>
                <div class="action-buttons">
                    <button class="button" onclick="cambiarEstado(<?= $pedido['id'] ?>, 'procesando')">
                        Marcar como Procesando
                    </button>
                    <button class="button button-green" onclick="cambiarEstado(<?= $pedido['id'] ?>, 'completado')">
                        Marcar como Completado
                    </button>
                    <button class="button button-red" onclick="cambiarEstado(<?= $pedido['id'] ?>, 'cancelado')">
                        Cancelar Pedido
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
    .detalle-pedido-page {
        padding: 20px 0;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
        padding: 0 20px;
    }

    .header-content h1 {
        margin: 0 0 5px 0;
    }

    .header-content p {
        margin: 0;
        color: #666;
    }

    .pedido-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        gap: 25px;
    }

    .pedido-info-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .pedido-info-card h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        color: #333;
        font-weight: 500;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pendiente {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-procesando {
        background-color: #cce5ff;
        color: #004085;
    }

    .status-completado {
        background-color: #d4edda;
        color: #155724;
    }

    .status-cancelado {
        background-color: #f8d7da;
        color: #721c24;
    }

    .total-amount {
        color: #e74c3c;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .pedido-productos {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .pedido-productos h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
    }

    .empty-message {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .productos-list {
        display: grid;
        gap: 15px;
        margin-bottom: 25px;
    }

    .producto-item {
        display: grid;
        grid-template-columns: 80px 1fr auto auto;
        gap: 20px;
        align-items: center;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .producto-imagen img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
    }

    .producto-info h4 {
        margin: 0 0 5px 0;
        color: #333;
    }

    .producto-artista {
        color: #666;
        margin: 0 0 5px 0;
        font-size: 0.9rem;
    }

    .producto-tipo {
        color: #999;
        margin: 0;
        font-size: 0.85rem;
    }

    .producto-cantidad {
        text-align: center;
    }

    .cantidad-label {
        display: block;
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .cantidad-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
    }

    .producto-precio {
        text-align: right;
    }

    .precio-unitario {
        display: block;
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .subtotal {
        font-size: 1.1rem;
        font-weight: bold;
        color: #333;
    }

    .resumen-total {
        border-top: 2px solid #eee;
        padding-top: 20px;
    }

    .total-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        color: #666;
    }

    .total-item.total-final {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
        border-top: 1px solid #ddd;
        margin-top: 10px;
        padding-top: 15px;
    }

    .admin-actions {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .admin-actions h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .button-green {
        background-color: #27ae60;
        border-color: #27ae60;
    }

    .button-green:hover {
        background-color: #219653;
        border-color: #219653;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .producto-item {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 15px;
        }

        .producto-imagen {
            justify-self: center;
        }

        .producto-precio {
            text-align: center;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .button {
            width: 100%;
        }
    }
</style>

<script>
    function cambiarEstado(pedidoId, nuevoEstado) {
        if (confirm(`¿Estás seguro de cambiar el estado del pedido a "${nuevoEstado}"?`)) {
            alert('Funcionalidad en desarrollo. Se cambiaría a: ' + nuevoEstado);
        }
    }
</script>

<?php include "../componentes/footer.php"; ?>