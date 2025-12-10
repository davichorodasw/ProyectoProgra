<?php
session_start();

$pageTitle = "Ritmo Retro";
$currentPage = "inicio";

require_once 'config/paths.php';
require_once 'php/manejoProductos.php';

include "componentes/header.php";
include "componentes/nav.php";
?>

<main class="main-content">
    <section class="hero">
        <div class="hero-content">
            <h2>Descubre el sonido auténtico</h2>
            <p>
                Explora nuestra colección de vinilos y CDs que capturan la esencia
                de la música en su forma más pura.
            </p>
            <div class="hero-buttons">
                <a href="./views/vinilos.php" class="btn btn-primary">Ver Vinilos</a>
                <a href="./views/cds.php" class="btn btn-secondary">Ver CDs</a>
            </div>
        </div>
    </section>

    <section class="featured-products">
        <h2>Productos Destacados</h2>
        <div class="products-grid">

            <?php
            $productos = obtenerProductosDestacados();

            if (!empty($productos)):
                foreach ($productos as $prod):
                    $img = (!empty($prod->imagen) && $prod->imagen !== 'default.png') ? $prod->imagen : 'default.png';
                    $tipoLabel = $prod->tipo === 'cd' ? 'CD' : 'Vinilo';
                    $tipoClass = $prod->tipo === 'cd' ? 'cd-card' : 'vinyl-card';
                    $tipoImageClass = $prod->tipo === 'cd' ? 'cd-image' : 'vinyl-image';
                    $tipoLabelClass = $prod->tipo === 'cd' ? 'cd-label' : 'vinyl-label';
            ?>
                    <div class="product-card <?= $tipoClass ?> featured-card">
                        <div class="product-image <?= $tipoImageClass ?>">
                            <span class="<?= $tipoLabelClass ?>"><?= $tipoLabel ?></span>
                            <a href="views/<?= $prod->tipo === 'cd' ? 'cds' : 'vinilos' ?>.php">
                                <img src="img/covers/<?= htmlspecialchars($img) ?>"
                                    alt="<?= htmlspecialchars($prod->titulo) ?>"
                                    style="width:100%; height:100%; object-fit:cover;">
                            </a>
                        </div>

                        <div class="product-info">
                            <h3>
                                <a href="views/<?= $prod->tipo === 'cd' ? 'cds' : 'vinilos' ?>.php"
                                    style="color:inherit; text-decoration:none;">
                                    <?= htmlspecialchars($prod->titulo) ?>
                                </a>
                            </h3>
                            <p class="artist"><?= htmlspecialchars($prod->artista) ?></p>
                            <p class="genre" style="font-size:0.9em; color:#e74c3c; margin:5px 0;">
                                <?= ucfirst(htmlspecialchars($prod->genero)) ?>
                            </p>

                            <div class="price-section" style="margin:10px 0;">
                                <span class="price">$<?= number_format($prod->precio, 2) ?></span>
                            </div>

                            <form action="procesos/agregar_carrito.php" method="POST" style="margin:0;">
                                <input type="hidden" name="producto_id" value="<?= $prod->id ?>">
                                <button type="submit" class="add-to-cart"
                                    style="width:100%; padding:10px; font-size:0.9em;">
                                    Añadir al Carrito
                                </button>
                            </form>
                        </div>
                    </div>
                <?php
                endforeach;
            else:
                ?>
                <p style="grid-column: 1 / -1; text-align:center; color:#666; font-size:1.1em;">
                    No hay productos disponibles aún.
                </p>
            <?php endif; ?>
        </div>
    </section>

    <section class="about">
        <div class="about-content">
            <h2>Nuestra Pasión por la Música</h2>
            <p>
                En Ritmo Retro creemos que la música debe vivirse, no solo
                escucharse. Cada vinilo y CD que ofrecemos ha sido cuidadosamente
                seleccionado para brindarte una experiencia musical única.
            </p>
            <div class="features">
                <div class="feature">
                    <h3>Calidad Garantizada</h3>
                    <p>
                        Todos nuestros productos son originales y de la más alta
                        calidad.
                    </p>
                </div>
                <div class="feature">
                    <h3>Envío Rápido</h3>
                    <p>
                        Recibe tu música en perfectas condiciones y en tiempo récord.
                    </p>
                </div>
                <div class="feature">
                    <h3>Atención Personalizada</h3>
                    <p>
                        Nuestro equipo está aquí para ayudarte a encontrar exactamente
                        lo que buscas.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include "componentes/footer.php"; ?>