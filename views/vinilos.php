<?php session_start(); ?>
<?php
$pageTitle = "Vinilos - Ritmo Retro";
$currentPage = "vinilos";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
?>
<?php include "../componentes/header.php"; ?>
<?php include "../componentes/nav.php"; ?>

<main class="main-content">
    <div class="page-header">
        <h1>Colección de Vinilos</h1>
        <p>Experimenta la magia del sonido analógico con nuestros vinilos de edición especial</p>
    </div>

    <?php if (isset($_SESSION['identity']) && $_SESSION['identity']->rol == 'admin'): ?>
        <div class="admin-controls" style="text-align: center; margin: 20px 0;">
            <a href="crear_vinilo.php"
                class="button button-red"
                style="background-color: #e74c3c; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                + Añadir Nuevo Vinilo
            </a>
        </div>
    <?php endif; ?>

    <?php $searchPlaceholder = "Buscar vinilos por título, artista o género..."; ?>

    <div class="search-bar-container" style="max-width: 800px; margin: 30px auto; text-align: center;">
        <form method="GET" action="" style="display: inline-block; width: 100%;">
            <div style="position: relative; max-width: 600px; margin: 0 auto;">
                <input type="text"
                    name="q"
                    placeholder="<?= $searchPlaceholder ?>"
                    value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
                    style="width: 100%; padding: 14px 50px 14px 20px; font-size: 1.1em; border: 2px solid #ddd; border-radius: 50px; outline: none;">
                <button type="submit" style="position: absolute; right: 8px; top: 6px; background: #e74c3c; color: white; border: none; padding: 10px 16px; border-radius: 50px; cursor: pointer;">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <section class="filters-section">
        <form method="GET" action="" class="filters-form">
            <div class="filters">

                <?php if (isset($_GET['q'])): ?>
                    <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q']) ?>">
                <?php endif; ?>

                <?php
                $currentPageType = 'vinilo';
                ?>

                <div class="filter-group">
                    <label for="genero">Género:</label>
                    <select name="genero" id="genero">
                        <option value="">Todos los géneros</option>
                        <?php
                        $generos = ['rock', 'pop', 'jazz', 'clasica', 'electronica', 'blues', 'soul', 'funk'];
                        foreach ($generos as $g) {
                            $selected = (isset($_GET['genero']) && $_GET['genero'] === $g) ? 'selected' : '';
                            echo "<option value='$g' $selected>" . ucfirst($g) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="precio">Precio:</label>
                    <select name="precio" id="precio">
                        <option value="">Todos los precios</option>
                        <option value="0-15" <?= (isset($_GET['precio']) && $_GET['precio'] === '0-15') ? 'selected' : '' ?>>Hasta $15</option>
                        <option value="15-25" <?= (isset($_GET['precio']) && $_GET['precio'] === '15-25') ? 'selected' : '' ?>>$15 - $25</option>
                        <option value="25-40" <?= (isset($_GET['precio']) && $_GET['precio'] === '25-40') ? 'selected' : '' ?>>$25 - $40</option>
                        <option value="40+" <?= (isset($_GET['precio']) && $_GET['precio'] === '40+') ? 'selected' : '' ?>>Más de $40</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="orden">Ordenar por:</label>
                    <select name="orden" id="orden">
                        <option value="reciente" <?= (!isset($_GET['orden']) || $_GET['orden'] === 'reciente') ? 'selected' : '' ?>>Más recientes</option>
                        <option value="precio_asc" <?= (isset($_GET['orden']) && $_GET['orden'] === 'precio_asc') ? 'selected' : '' ?>>Precio: menor a mayor</option>
                        <option value="precio_desc" <?= (isset($_GET['orden']) && $_GET['orden'] === 'precio_desc') ? 'selected' : '' ?>>Precio: mayor a menor</option>
                        <option value="titulo" <?= (isset($_GET['orden']) && $_GET['orden'] === 'titulo') ? 'selected' : '' ?>>Título A-Z</option>
                    </select>
                </div>

                <div class="filter-group">
                    <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        Aplicar Filtros
                    </button>
                    <a href="vinilos.php" style="margin-left: 10px; color: #e74c3c; text-decoration: underline;">Limpiar</a>
                </div>
            </div>
        </form>
    </section>

    <section class="products-section">
        <div class="products-grid">

            <?php
            $db = new mysqli('localhost', 'root', '', 'ritmoretro');
            $db->set_charset("utf8");

            $sql = "SELECT * FROM productos WHERE tipo = 'vinilo'";
            $conditions = [];

            if (!empty($_GET['q'])) {
                $q = $db->real_escape_string($_GET['q']);
                $conditions[] = "(titulo LIKE '%$q%' OR artista LIKE '%$q%')";
            }

            if (!empty($_GET['genero'])) {
                $genero = $db->real_escape_string($_GET['genero']);
                $conditions[] = "genero = '$genero'";
            }

            if (!empty($_GET['precio'])) {
                switch ($_GET['precio']) {
                    case '0-15':
                        $conditions[] = "precio <= 15";
                        break;
                    case '15-25':
                        $conditions[] = "precio BETWEEN 15 AND 25";
                        break;
                    case '25-40':
                        $conditions[] = "precio BETWEEN 25 AND 40";
                        break;
                    case '40+':
                        $conditions[] = "precio > 40";
                        break;
                }
            }

            if (!empty($conditions)) {
                $sql .= " AND " . implode(" AND ", $conditions);
            }

            $orden = "id DESC";
            if (isset($_GET['orden'])) {
                switch ($_GET['orden']) {
                    case 'precio_asc':
                        $orden = "precio ASC";
                        break;
                    case 'precio_desc':
                        $orden = "precio DESC";
                        break;
                    case 'titulo':
                        $orden = "titulo ASC";
                        break;
                    case 'reciente':
                        $orden = "id DESC";
                        break;
                }
            }
            $sql .= " ORDER BY $orden";

            $productos = $db->query($sql);
            ?>

            <?php if ($productos && $productos->num_rows > 0): ?>
                <?php while ($prod = $productos->fetch_object()): ?>
                    <div class="product-card vinyl-card">
                        <div class="product-image vinyl-image">
                            <span class="vinyl-label">Vinilo</span>
                            <?php
                            $img = (!empty($prod->imagen) && $prod->imagen !== 'default.png') ? $prod->imagen : 'default.png';
                            ?>
                            <img src="../img/covers/<?= htmlspecialchars($img) ?>"
                                alt="<?= htmlspecialchars($prod->titulo) ?>"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>

                        <div class="product-info">
                            <h3><?= htmlspecialchars($prod->titulo) ?></h3>
                            <p class="artist"><?= htmlspecialchars($prod->artista) ?></p>
                            <p class="genre"><?= ucfirst(htmlspecialchars($prod->genero)) ?></p>

                            <p style="font-size: 0.9em; color: #666;">
                                <?= htmlspecialchars(substr($prod->descripcion, 0, 60)) ?>...
                            </p>

                            <div class="price-section">
                                <span class="price">$<?= number_format($prod->precio, 2) ?></span>
                            </div>

                            <form action="../procesos/agregar_carrito.php" method="POST">
                                <input type="hidden" name="producto_id" value="<?= $prod->id ?>">
                                <button type="submit" class="add-to-cart">Añadir al Carrito</button>
                            </form>

                            <?php if (isset($_SESSION['identity']) && $_SESSION['identity']->rol == 'admin'): ?>
                                <div class="admin-actions" style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px; display:flex; gap:5px; justify-content:center;">
                                    <a href="editar_producto.php?id=<?= $prod->id ?>"
                                        style="background-color: #f39c12; color: white; padding: 5px 15px; font-size: 0.8rem; text-decoration: none; border-radius: 3px; font-weight:bold;">
                                        Editar
                                    </a>
                                    <a href="borrar_producto.php?id=<?= $prod->id ?>"
                                        onclick="return confirm('¿Estás seguro de eliminar este álbum?');"
                                        style="background-color: #c0392b; color: white; padding: 5px 15px; font-size: 0.8rem; text-decoration: none; border-radius: 3px; font-weight:bold;">
                                        Eliminar
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; grid-column: 1 / -1; font-size:1.2em; color:#666;">
                    No se encontraron resultados para tu búsqueda.
                    <br><a href="vinilos.php" style="color:#e74c3c;">Ver todos</a>
                </p>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php include "../componentes/footer.php"; ?>