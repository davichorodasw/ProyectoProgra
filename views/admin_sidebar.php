<div class="admin-sidebar">
    <div class="sidebar-header">
        <h3><?= htmlspecialchars($_SESSION['user_name']) ?></h3>
        <p class="admin-role">Administrador</p>
    </div>

    <nav class="admin-nav">
        <a href="admin_dashboard.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? 'active' : '' ?>">
            <span class="nav-icon">📊</span>
            Dashboard
        </a>
        <a href="gestion_productos.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'gestion_productos.php' ? 'active' : '' ?>">
            <span class="nav-icon">📦</span>
            Productos
        </a>
        <a href="todos_pedidos.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'todos_pedidos.php' ? 'active' : '' ?>">
            <span class="nav-icon">🛒</span>
            Pedidos
        </a>
        <a href="gestion_usuarios.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'gestion_usuarios.php' ? 'active' : '' ?>">
            <span class="nav-icon">👥</span>
            Usuarios
        </a>
        <div class="nav-divider"></div>
        <a href="crear_cd.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'crear_cd.php' ? 'active' : '' ?>">
            <span class="nav-icon">💿</span>
            Nuevo CD
        </a>
        <a href="crear_vinilo.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'crear_vinilo.php' ? 'active' : '' ?>">
            <span class="nav-icon">🎵</span>
            Nuevo Vinilo
        </a>
        <div class="nav-divider"></div>
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