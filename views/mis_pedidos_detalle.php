<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: mis_pedidos.php');
    exit;
}

$pedido_id = intval($_GET['id']);
$usuario_id = $_SESSION['user_id'];

require_once '../php/conexion.php';
$conn = conectarDB();

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM pedidos WHERE id = ? AND usuario_id = ?"
);
mysqli_stmt_bind_param($stmt, "ii", $pedido_id, $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pedido = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$pedido) {
    header('Location: mis_pedidos.php');
    exit;
}

$stmt_det = mysqli_prepare(
    $conn,
    "SELECT dp.*, p.titulo, p.artista, p.imagen, p.tipo
     FROM detalles_pedido dp
     JOIN productos p ON dp.producto_id = p.id
     WHERE dp.pedido_id = ?"
);
mysqli_stmt_bind_param($stmt_det, "i", $pedido_id);
mysqli_stmt_execute($stmt_det);
$detalles = mysqli_stmt_get_result($stmt_det);
mysqli_stmt_close($stmt_det);

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content todos-pedidos-page">
    <div class="page-header">
        <h1>Detalle del Pedido #<?= str_pad($pedido_id, 6, '0', STR_PAD_LEFT) ?></h1>
        <p>Mira el contenido de tu pedido</p>
    </div>

    <div class="admin-content" style="margin: 0 auto; max-width: 1200px;">
        <div class="pedido-info" style="margin-bottom:30px;">
            <p><strong>Estado:</strong>
                <span class="status-select status-<?= $pedido['estado'] ?>" style="pointer-events:none;">
                    <?= ucfirst($pedido['estado']) ?>
                </span>
            </p>
            <p><strong>Fecha:</strong> <?= $pedido['fecha_pedido'] ?></p>
            <p><strong>Total:</strong> $<?= number_format($pedido['total'], 2) ?></p>
        </div>

        <div class="table-container">
            <table class="pedidos-table">
                <thead>
                    <tr>
                        <th>Portada</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio U.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($detalles)): ?>
                        <tr>
                            <td>
                                <img src="img/covers/<?= htmlspecialchars($row['imagen']) ?>"
                                    style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($row['titulo']) ?></strong><br>
                                <span><?= htmlspecialchars($row['artista']) ?></span><br>
                                <small><?= strtoupper($row['tipo']) ?></small>
                            </td>
                            <td><?= $row['cantidad'] ?></td>
                            <td>$<?= number_format($row['precio_unitario'], 2) ?></td>
                            <td>$<?= number_format($row['cantidad'] * $row['precio_unitario'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            <a href="mis_pedidos.php" class="filter-btn" style="padding:10px 18px; text-decoration:none;">
                ← Volver
            </a>
        </div>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>