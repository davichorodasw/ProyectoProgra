<?php session_start(); ?>
<?php
$pageTitle = "CDs - Ritmo Retro";
$currentPage = "cds";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
?>
<?php include "../componentes/header.php"; ?>
<?php include "../componentes/nav.php"; ?>

<main class="main-content">
    <div class="page-header">
        <h1>Nuestra Colección de CDs</h1>
        <p>Descubre la mejor música en formato CD con la calidad que mereces</p>
    </div>

    <?php if (isset($_SESSION['identity']) && $_SESSION['identity']->rol == 'admin'): ?>
        <div class="admin-controls" style="text-align: center; margin: 20px 0;">
            <a href="crear_cd.php"
                class="button button-red"
                style="background-color: #e74c3c; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                + Añadir Nuevo CD
            </a>
        </div>
    <?php endif; ?>

    <?php $searchPlaceholder = "Buscar CDs por título, artista o género..."; ?>

    <section class="filters-section">
        <div class="filters">
            <div class="filter-group">
                <label for="genre">Género:</label>
                <select id="genre">
                    <option value="">Todos los géneros</option>
                    <option value="rock">Rock</option>
                    <option value="pop">Pop</option>
                    <option value="jazz">Jazz</option>
                    <option value="clasica">Música Clásica</option>
                    <option value="electronica">Electrónica</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="price">Precio:</label>
                <select id="price">
                    <option value="">Todos los precios</option>
                    <option value="0-15">Hasta $15</option>
                    <option value="15-25">$15 - $25</option>
                    <option value="25-40">$25 - $40</option>
                    <option value="40+">Más de $40</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="sort">Ordenar por:</label>
                <select id="sort">
                    <option value="popular">Más populares</option>
                    <option value="newest">Más recientes</option>
                    <option value="price-low">Precio: menor a mayor</option>
                    <option value="price-high">Precio: mayor a menor</option>
                </select>
            </div>
        </div>
    </section>

    <section class="products-section">
        <div class="products-grid">

            <?php
            // 1. CONEXIÓN RÁPIDA (Idealmente esto iría en un archivo config/db.php)
            $db = new mysqli('localhost', 'root', '', 'ritmoretro');
            $db->set_charset("utf8");

            // 2. CONSULTA A LA BASE DE DATOS (Solo CDs)
            $sql = "SELECT * FROM productos WHERE tipo = 'cd' ORDER BY id DESC";
            $productos = $db->query($sql);

            // 3. BUCLE PARA MOSTRAR PRODUCTOS
            if ($productos && $productos->num_rows > 0):
                while ($prod = $productos->fetch_object()):
            ?>
                    <div class="product-card cd-card">
                        <div class="product-image cd-image">
                            <span class="cd-label">CD</span>

                            <?php
                            // Si la imagen es 'default.png' o viene vacía, usa una genérica
                            $img = $prod->imagen != null ? $prod->imagen : 'default.png';
                            // Asumimos que guardas las imágenes en ../img/covers/
                            ?>
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
                <p>No hay CDs disponibles en este momento.</p>
            <?php endif; ?>

        </div>
    </section>

    <section class="cds-info">
        <div class="info-content">
            <h2>¿Por qué elegir CDs?</h2>
            <div class="info-grid">
                <div class="info-item">
                    <h3>Calidad de Sonido Superior</h3>
                    <p>Los CDs ofrecen audio digital de alta calidad sin pérdidas, perfecto para los amantes del sonido puro.</p>
                </div>
                <div class="info-item">
                    <h3>Durabilidad</h3>
                    <p>Resistentes al desgaste y con una vida útil prolongada, tus CDs te acompañarán por años.</p>
                </div>
                <div class="info-item">
                    <h3>Portabilidad</h3>
                    <p>Lleva tu música favorita a cualquier lugar y disfrútala en reproductores de CD, coches y más.</p>
                </div>
                <div class="info-item">
                    <h3>Contenido Exclusivo</h3>
                    <p>Muchos CDs incluyen booklet con letras, fotos y contenido adicional no disponible en streaming.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include "../componentes/footer.php"; ?>