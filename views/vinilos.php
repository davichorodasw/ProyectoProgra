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

    <section class="filters-section">
        <div class="filters">
            <div class="filter-group">
                <label for="genre">Género:</label>
                <select id="genre">
                    <option value="">Todos los géneros</option>
                    <option value="rock">Rock</option>
                    <option value="jazz">Jazz</option>
                    <option value="blues">Blues</option>
                    <option value="soul">Soul</option>
                    <option value="funk">Funk</option>
                    <option value="clasica">Clásica</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="condition">Estado:</label>
                <select id="condition">
                    <option value="">Todos los estados</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="like-new">Como nuevo</option>
                    <option value="muy-bueno">Muy bueno</option>
                    <option value="bueno">Bueno</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="edition">Edición:</label>
                <select id="edition">
                    <option value="">Todas las ediciones</option>
                    <option value="original">Original</option>
                    <option value="reedicion">Reedición</option>
                    <option value="limitada">Edición Limitada</option>
                    <option value="numerada">Numerada</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="sort">Ordenar por:</label>
                <select id="sort">
                    <option value="popular">Más populares</option>
                    <option value="newest">Más recientes</option>
                    <option value="year-old">Año: más antiguos</option>
                    <option value="year-new">Año: más nuevos</option>
                    <option value="price-low">Precio: menor a mayor</option>
                    <option value="price-high">Precio: mayor a menor</option>
                </select>
            </div>
        </div>
    </section>

    <section class="products-section">
        <div class="products-grid">

            <?php
            $db = new mysqli('localhost', 'root', '', 'ritmoretro');
            $db->set_charset("utf8");

            // CAMBIO AQUÍ: Filtramos por 'vinilo'
            $sql = "SELECT * FROM productos WHERE tipo = 'vinilo' ORDER BY id DESC";
            $productos = $db->query($sql);

            if ($productos && $productos->num_rows > 0):
                while ($prod = $productos->fetch_object()):
            ?>
                    <div class="product-card vinyl-card">
                        <div class="product-image vinyl-image">
                            <span class="vinyl-label">Vinilo</span>

                            <?php $img = $prod->imagen != null ? $prod->imagen : 'default.png'; ?>
                            <img src="../img/covers/<?= $img ?>" alt="<?= $prod->titulo ?>" style="width:100%; height:100%; object-fit:cover;">
                        </div>

                        <div class="product-info">
                            <h3><?= $prod->titulo ?></h3>
                            <p class="artist"><?= $prod->artista ?></p>

                            <p class="genre" style="font-size: 0.9em; color: #666;">
                                <?= substr($prod->descripcion, 0, 30) ?>...
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

                <?php
                endwhile;
            else:
                ?>
                <p>No hay Vinilos disponibles en este momento.</p>
            <?php endif; ?>

        </div>
    </section>

    <!-- Resto del contenido... -->
</main>

<?php include "../componentes/footer.php"; ?>