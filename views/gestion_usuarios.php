<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php?error=Acceso+no+autorizado');
    exit;
}

$pageTitle = "Gestión de Usuarios - Ritmo Retro";
$additionalCSS = ["css/admin.css"];

require_once '../php/manejoUsuarios.php';

$search = $_GET['search'] ?? '';
$rol     = $_GET['rol'] ?? '';
$page    = max(1, intval($_GET['page'] ?? 1));
$limit   = 25;
$offset  = ($page - 1) * $limit;

$total_rows = contarUsuariosFiltrados($search, $rol);
$total_pages = ceil($total_rows / $limit);

$usuarios = obtenerUsuariosFiltrados($search, $rol, $limit, $offset);

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content admin-dashboard">
    <div class="page-header">
        <h1>Gestión de Usuarios</h1>
        <p>Visualiza y elimina usuarios si es necesario</p>
    </div>

    <div class="admin-container">
        <?php include "admin_sidebar.php"; ?>

        <div class="admin-content">

            <div class="stats-grid">
                <?php
                $totalRow = obtenerTotalUsuarios();
                $total = $totalRow['total'] ?? 0;
                $adminsRow = obtenerTotalAdmins();
                $admins = $adminsRow['admins'] ?? 0;
                ?>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color:#34495e;">👤</div>
                    <div class="stat-info">
                        <h3><?= $total ?></h3>
                        <p>Total Usuarios</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background-color:#e74c3c;">👨🏻‍💻</div>
                    <div class="stat-info">
                        <h3><?= $admins ?></h3>
                        <p>Administradores</p>
                    </div>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-box">
                    <form method="GET">
                        <input type="text" name="search" placeholder="Buscar por nombre o email..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit"><span class="search-icon">Buscar</span></button>
                    </form>
                </div>
                <div class="toolbar-actions">
                    <a href="gestion_usuarios.php" class="button button-blue">Limpiar filtros</a>
                </div>
            </div>

            <div class="filters">
                <a href="gestion_usuarios.php" class="filter-btn <?= $rol === '' ? 'active' : '' ?>">Todos</a>
                <a href="?rol=admin" class="filter-btn <?= $rol === 'admin' ? 'active' : '' ?>">Administradores</a>
                <a href="?rol=user" class="filter-btn <?= $rol === 'user' ? 'active' : '' ?>">Usuarios</a>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Registrado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay usuarios</td>
                            </tr>
                            <?php else: foreach ($usuarios as $u): ?>
                                <tr>
                                    <td>#<?= $u['id'] ?></td>
                                    <td><?= htmlspecialchars($u['nombre']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['telefono'] ?: '-') ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $u['rol'] ?>">
                                            <?= $u['rol'] === 'admin' ? 'Admin' : 'Usuario' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($u['fecha_registro'])) ?></td>
                                    <td class="action-buttons">
                                        <a href="detalle_usuario.php?id=<?= $u['id'] ?>" class="btn-action btn-view" title="Ver detalle">👁️</a>
                                        <a href="../php/eliminar_usuario.php?id=<?= $u['id'] ?>"
                                            class="btn-action btn-delete"
                                            onclick="return confirm('¿Estás seguro de eliminar al usuario «<?= htmlspecialchars($u['nombre']) ?>»? Esta acción NO se puede deshacer.')"
                                            title="Eliminar">🗑️</a>
                                    </td>
                                </tr>
                        <?php endforeach;
                        endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&rol=<?= $rol ?>" class="page-link">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&rol=<?= $rol ?>"
                            class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&rol=<?= $rol ?>" class="page-link">Siguiente</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>