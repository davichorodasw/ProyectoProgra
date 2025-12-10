<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: gestion_usuarios.php');
    exit;
}
$id = intval($_GET['id']);

require_once '../php/manejoUsuarios.php';

$usuario = obtenerUsuarioPorId($id);

if (!$usuario) {
    header('Location: gestion_usuarios.php?error=Usuario+no+encontrado');
    exit;
}

$pedidos = obtenerPedidosDeUsuario($id);

mysqli_close($conn);

$pageTitle = "Detalle de Usuario - Ritmo Retro";
$additionalCSS = ["css/admin.css"];
include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content admin-dashboard">
    <div class="page-header">
        <h1>Detalle del Usuario #<?= $usuario['id'] ?></h1>
    </div>

    <div class="admin-container">
        <?php include "admin_sidebar.php"; ?>

        <div class="admin-content">
            <div class="content-card">
                <div class="card-header">
                    <h3>Información Personal</h3>
                    <a href="gestion_usuarios.php" class="ver-todo">Volver a lista</a>
                </div>

                <table class="admin-table" style="max-width:700px;">
                    <tr>
                        <th>Nombre</th>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td><?= htmlspecialchars($usuario['telefono'] ?: '—') ?></td>
                    </tr>
                    <tr>
                        <th>Rol</th>
                        <td><strong><?= $usuario['rol'] === 'admin' ? 'Administrador' : 'Usuario' ?></strong></td>
                    </tr>
                    <tr>
                        <th>Fecha de registro</th>
                        <td><?= date('d/m/Y H:i', strtotime($usuario['fecha_registro'])) ?></td>
                    </tr>
                </table>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h3>Historial de Pedidos (<?= count($pedidos) ?>)</h3>
                </div>
                <?php if (empty($pedidos)): ?>
                    <p class="empty-message">Este usuario aún no ha realizado ningún pedido.</p>
                <?php else: ?>
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidos as $p): ?>
                                    <tr>
                                        <td><a href="detalle_pedido.php?id=<?= $p['id'] ?>">#<?= str_pad($p['id'], 6, '0', STR_PAD_LEFT) ?></a></td>
                                        <td><?= date('d/m/Y H:i', strtotime($p['fecha_pedido'])) ?></td>
                                        <td>$<?= number_format($p['total'], 2) ?></td>
                                        <td><span class="status-badge status-<?= $p['estado'] ?>"><?= ucfirst($p['estado']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>