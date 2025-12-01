<?php
session_start();
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

    <?php
    $searchPlaceholder = "Buscar vinilos por título, artista o género...";
    include "../componentes/search-bar.php";
    ?>

    <?php if (isset($_GET["q"]) && !empty(trim($_GET["q"]))): ?>
        <?php $searchTerm = htmlspecialchars(trim($_GET["q"])); ?>
        <div class="search-results-header">
            <h3>Resultados de búsqueda para: <span class="search-term">"<?php echo $searchTerm; ?>"</span></h3>
            <p class="search-results-count">Mostrando vinilos que coinciden con tu búsqueda</p>
        </div>
    <?php endif; ?>

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
            <div class="product-card vinyl-card">
                <div class="product-image vinyl-image">
                    <span class="vinyl-label">Vinilo</span>
                    <span class="condition-badge excelente">Excelente</span>
                </div>
                <div class="product-info">
                    <h3>Rumours</h3>
                    <p class="artist">Fleetwood Mac</p>
                    <p class="details">1977 • Edición Original • 180g</p>
                    <div class="rating">
                        ★★★★★ <span class="rating-count">(203)</span>
                    </div>
                    <div class="price-section">
                        <span class="price">$34.99</span>
                        <span class="original-price">$39.99</span>
                    </div>
                    <button class="add-to-cart">Añadir al Carrito</button>
                </div>
            </div>

        </div>
    </section>
</main>

<?php include "../componentes/footer.php"; ?>