<?php
session_start();

if (
    !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true ||
    !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'
) {
    header('Location: ../index.php?error=Acceso+no+autorizado');
    exit;
}

$pageTitle = "Gestión de Productos - Ritmo Retro";
$currentPage = "admin";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
$extraCss = "../css/admin.css";

require_once '../php/conexion.php';
$conn = conectarDB();

$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM productos WHERE 1=1";
$count_query = "SELECT COUNT(*) as total FROM productos WHERE 1=1";

if ($search) {
    $search_term = "%" . mysqli_real_escape_string($conn, $search) . "%";
    $query .= " AND (titulo LIKE ? OR artista LIKE ?)";
    $count_query .= " AND (titulo LIKE ? OR artista LIKE ?)";
}

if ($filter === 'cds') {
    $query .= " AND tipo = 'cd'";
    $count_query .= " AND tipo = 'cd'";
} elseif ($filter === 'vinilos') {
    $query .= " AND tipo = 'vinilo'";
    $count_query .= " AND tipo = 'vinilo'";
} elseif ($filter === 'low-stock') {
    $query .= " AND stock < 10";
    $count_query .= " AND stock < 10";
} elseif ($filter === 'out-of-stock') {
    $query .= " AND stock = 0";
    $count_query .= " AND stock = 0";
}

$query .= " ORDER BY id DESC LIMIT ? OFFSET ?";

$stmt_count = mysqli_prepare($conn, $count_query);
if ($search) {
    mysqli_stmt_bind_param($stmt_count, "ss", $search_term, $search_term);
}
mysqli_stmt_execute($stmt_count);
$result_count = mysqli_stmt_get_result($stmt_count);
$total_rows = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_rows / $limit);
mysqli_stmt_close($stmt_count);

$stmt = mysqli_prepare($conn, $query);
if ($search) {
    mysqli_stmt_bind_param($stmt, "ssii", $search_term, $search_term, $limit, $offset);
} else {
    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$productos = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_stmt_close($stmt);
mysqli_close($conn);

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content admin-dashboard">
    <div class="page-header">
        <h1>Gestión de Productos</h1>
        <p>Administra el catálogo de productos</p>
    </div>

    <div class="admin-container">
        <?php include "admin_sidebar.php"; ?>

        <div class="admin-content">
            <div class="toolbar">
                <div class="search-box">
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Buscar productos..."
                            value="<?= htmlspecialchars($search) ?>">
                        <?php if ($filter !== 'all'): ?>
                            <input type="hidden" name="filter" value="<?= $filter ?>">
                        <?php endif; ?>
                        <button type="submit">
                            <span class="search-icon">🔍</span>
                        </button>
                    </form>
                </div>

                <div class="toolbar-actions">
                    <a href="crear_cd.php" class="button button-red">
                        <span class="btn-icon">💿</span>
                        Nuevo CD
                    </a>
                    <a href="crear_vinilo.php" class="button button-blue">
                        <span class="btn-icon">🎵</span>
                        Nuevo Vinilo
                    </a>
                </div>
            </div>

            <div class="filters">
                <a href="?filter=all<?= $search ? '&search=' . urlencode($search) : '' ?>"
                    class="filter-btn <?= $filter === 'all' ? 'active' : '' ?>">
                    Todos (<?= $total_rows ?>)
                </a>
                <a href="?filter=cds<?= $search ? '&search=' . urlencode($search) : '' ?>"
                    class="filter-btn <?= $filter === 'cds' ? 'active' : '' ?>">
                    CDs
                </a>
                <a href="?filter=vinilos<?= $search ? '&search=' . urlencode($search) : '' ?>"
                    class="filter-btn <?= $filter === 'vinilos' ? 'active' : '' ?>">
                    Vinilos
                </a>
                <a href="?filter=low-stock<?= $search ? '&search=' . urlencode($search) : '' ?>"
                    class="filter-btn <?= $filter === 'low-stock' ? 'active' : '' ?>">
                    Stock Bajo
                </a>
                <a href="?filter=out-of-stock<?= $search ? '&search=' . urlencode($search) : '' ?>"
                    class="filter-btn <?= $filter === 'out-of-stock' ? 'active' : '' ?>">
                    Sin Stock
                </a>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Artista</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="9" class="text-center">
                                    No se encontraron productos
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td>#<?= $producto['id'] ?></td>
                                    <td>
                                        <img src="../img/covers/<?= htmlspecialchars($producto['imagen']) ?>"
                                            alt="<?= htmlspecialchars($producto['titulo']) ?>"
                                            class="product-thumb"
                                            onerror="this.src='../img/covers/default.png'">
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($producto['titulo']) ?></strong>
                                        <?php if (strlen($producto['descripcion']) > 0): ?>
                                            <p class="description-truncate">
                                                <?= htmlspecialchars(substr($producto['descripcion'], 0, 100)) ?>...
                                            </p>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($producto['artista']) ?></td>
                                    <td>
                                        <span class="badge <?= $producto['tipo'] === 'cd' ? 'badge-blue' : 'badge-purple' ?>">
                                            <?= $producto['tipo'] === 'cd' ? 'CD' : 'Vinilo' ?>
                                        </span>
                                    </td>
                                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                                    <td>
                                        <span class="stock-indicator <?= $producto['stock'] < 10 ? 'stock-low' : '' ?>">
                                            <?= $producto['stock'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= $producto['stock'] > 0 ? 'active' : 'inactive' ?>">
                                            <?= $producto['stock'] > 0 ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="editar_producto.php?id=<?= $producto['id'] ?>"
                                                class="btn-action btn-edit" title="Editar">
                                                ✏️
                                            </a>
                                            <a href="borrar_producto.php?id=<?= $producto['id'] ?>"
                                                class="btn-action btn-delete"
                                                onclick="return confirm('¿Eliminar este producto?')"
                                                title="Eliminar">
                                                🗑️
                                            </a>
                                            <a href="../index.php?product=<?= $producto['id'] ?>"
                                                class="btn-action btn-view" title="Ver en tienda" target="_blank">
                                                👁️
                                            </a>
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
                        <a href="?page=<?= $page - 1 ?>&filter=<?= $filter ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                            class="page-link">← Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <a href="?page=<?= $i ?>&filter=<?= $filter ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                            class="page-link <?= $i === $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>&filter=<?= $filter ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                            class="page-link">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="summary-card">
                <h3>Resumen del Catálogo</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <span class="summary-label">Total Productos:</span>
                        <span class="summary-value"><?= $total_rows ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">CDs:</span>
                        <span class="summary-value"><?= array_reduce($productos, function ($carry, $item) {
                                                        return $carry + ($item['tipo'] === 'cd' ? 1 : 0);
                                                    }, 0) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Vinilos:</span>
                        <span class="summary-value"><?= array_reduce($productos, function ($carry, $item) {
                                                        return $carry + ($item['tipo'] === 'vinilo' ? 1 : 0);
                                                    }, 0) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .search-box form {
        display: flex;
        gap: 10px;
    }

    .search-box input {
        padding: 10px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        width: 300px;
        font-size: 0.95rem;
    }

    .search-box input:focus {
        outline: none;
        border-color: #3498db;
    }

    .search-box button {
        padding: 10px 20px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .search-box button:hover {
        background: #2980b9;
    }

    .toolbar-actions {
        display: flex;
        gap: 15px;
    }

    .filters {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 16px;
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 20px;
        text-decoration: none;
        color: #495057;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table thead {
        background: #f8f9fa;
    }

    .admin-table th {
        padding: 15px;
        text-align: left;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .admin-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .admin-table tr:hover {
        background-color: #f8f9fa;
    }

    .product-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
    }

    .description-truncate {
        margin: 5px 0 0 0;
        color: #6c757d;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-blue {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-purple {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .stock-indicator {
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 6px;
        background: #d4edda;
        color: #155724;
    }

    .stock-indicator.stock-low {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
    }

    .btn-edit:hover {
        background: #bbdefb;
    }

    .btn-delete {
        background: #ffebee;
        color: #d32f2f;
        border: 1px solid #ffcdd2;
    }

    .btn-delete:hover {
        background: #ffcdd2;
    }

    .btn-view {
        background: #e8f5e9;
        color: #388e3c;
        border: 1px solid #c8e6c9;
    }

    .btn-view:hover {
        background: #c8e6c9;
    }

    .text-center {
        text-align: center;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin: 25px 0;
    }

    .page-link {
        padding: 8px 14px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        text-decoration: none;
        color: #495057;
        transition: all 0.3s ease;
    }

    .page-link:hover,
    .page-link.active {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }

    .summary-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .summary-card h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .summary-label {
        color: #6c757d;
        font-weight: 500;
    }

    .summary-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    @media (max-width: 992px) {
        .toolbar {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .search-box input {
            width: 100%;
        }

        .toolbar-actions {
            flex-wrap: wrap;
            justify-content: center;
        }

        .admin-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<?php include "../componentes/footer.php"; ?>