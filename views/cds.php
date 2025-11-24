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

    <!-- Barra de Búsqueda para CDs -->
    <?php
    $searchPlaceholder = "Buscar CDs por título, artista o género...";
    include "../componentes/search-bar.php";
    ?>

    <!-- Mostrar resultados de búsqueda si hay término -->
    <?php if (isset($_GET["q"]) && !empty(trim($_GET["q"]))): ?>
        <?php $searchTerm = htmlspecialchars(trim($_GET["q"])); ?>
        <div class="search-results-header">
            <h3>Resultados de búsqueda para: <span class="search-term">"<?php echo $searchTerm; ?>"</span></h3>
            <p class="search-results-count">Mostrando CDs que coinciden con tu búsqueda</p>
        </div>
    <?php endif; ?>

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
            <!-- CD 1 -->
            <div class="product-card cd-card">
                <div class="product-image cd-image">
                    <span class="cd-label">CD</span>
                </div>
                <div class="product-info">
                    <h3>The Dark Side of the Moon</h3>
                    <p class="artist">Pink Floyd</p>
                    <p class="genre">Rock Progresivo • 1973</p>
                    <div class="rating">
                        ★★★★☆ <span class="rating-count">(128)</span>
                    </div>
                    <div class="price-section">
                        <span class="price">$24.99</span>
                        <span class="original-price">$29.99</span>
                    </div>
                    <button class="add-to-cart">Añadir al Carrito</button>
                </div>
            </div>

            <!-- CD 2 -->
            <div class="product-card cd-card">
                <div class="product-image cd-image">
                    <span class="cd-label">CD</span>
                </div>
                <div class="product-info">
                    <h3>Thriller</h3>
                    <p class="artist">Michael Jackson</p>
                    <p class="genre">Pop • 1982</p>
                    <div class="rating">
                        ★★★★★ <span class="rating-count">(256)</span>
                    </div>
                    <div class="price-section">
                        <span class="price">$19.99</span>
                    </div>
                    <button class="add-to-cart">Añadir al Carrito</button>
                </div>
            </div>

            <!-- Resto de CDs... -->
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
