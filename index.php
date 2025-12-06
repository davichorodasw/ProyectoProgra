<?php
session_start();

$pageTitle = "Ritmo Retro";
$currentPage = "inicio";

require_once 'config/paths.php';

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
            <div class="product-card">
                <div class="product-image"></div>
                <h3>Vinilo Clásico</h3>
                <p>Edición limitada de los mejores álbumes</p>
                <span class="price">$29.99</span>
            </div>
            <div class="product-card">
                <div class="product-image"></div>
                <h3>CD Coleccionista</h3>
                <p>Versiones especiales con contenido exclusivo</p>
                <span class="price">$19.99</span>
            </div>
            <div class="product-card">
                <div class="product-image"></div>
                <h3>Pack Especial</h3>
                <p>Vinilo + CD + contenido digital</p>
                <span class="price">$39.99</span>
            </div>
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