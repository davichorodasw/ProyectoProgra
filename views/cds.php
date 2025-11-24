<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CDs - Ritmo Retro</title>
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Ritmo Retro</h1>
            <img src="../img/RitmoRetro.png" alt="Logo Ritmo Retro" />
            <h2>En físico, todo es mejor</h2>
        </div>
    </div>

    <nav>
        <a href="../index.php">Inicio</a>
        <a href="/CDS" class="active">CDs</a>
        <a href="/vinilos">Vinilos</a>
        <a href="/busqueda">Búsqueda</a>
        <a href="/login">Iniciar sesión</a>
        <a href="/contacto">Contacto</a>
    </nav>

    <main class="main-content">
        <div class="page-header">
            <h1>Nuestra Colección de CDs</h1>
            <p>Descubre la mejor música en formato CD con la calidad que mereces</p>
        </div>

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

                <!-- CD 3 -->
                <div class="product-card cd-card">
                    <div class="product-image cd-image">
                        <span class="cd-label">CD</span>
                    </div>
                    <div class="product-info">
                        <h3>Kind of Blue</h3>
                        <p class="artist">Miles Davis</p>
                        <p class="genre">Jazz • 1959</p>
                        <div class="rating">
                            ★★★★★ <span class="rating-count">(89)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$22.99</span>
                            <span class="original-price">$27.99</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- CD 4 -->
                <div class="product-card cd-card">
                    <div class="product-image cd-image">
                        <span class="cd-label">CD</span>
                    </div>
                    <div class="product-info">
                        <h3>Abbey Road</h3>
                        <p class="artist">The Beatles</p>
                        <p class="genre">Rock • 1969</p>
                        <div class="rating">
                            ★★★★☆ <span class="rating-count">(187)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$21.99</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- CD 5 -->
                <div class="product-card cd-card">
                    <div class="product-image cd-image">
                        <span class="cd-label">CD</span>
                    </div>
                    <div class="product-info">
                        <h3>Back in Black</h3>
                        <p class="artist">AC/DC</p>
                        <p class="genre">Hard Rock • 1980</p>
                        <div class="rating">
                            ★★★★☆ <span class="rating-count">(142)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$18.99</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- CD 6 -->
                <div class="product-card cd-card">
                    <div class="product-image cd-image">
                        <span class="cd-label">CD</span>
                    </div>
                    <div class="product-info">
                        <h3>The Joshua Tree</h3>
                        <p class="artist">U2</p>
                        <p class="genre">Rock • 1987</p>
                        <div class="rating">
                            ★★★★☆ <span class="rating-count">(113)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$20.99</span>
                            <span class="original-price">$24.99</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>
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

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Ritmo Retro</h3>
                <p>Tu tienda de confianza para vinilos y CDs.</p>
            </div>
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <a href="/">Inicio</a>
                <a href="/CDS">CDs</a>
                <a href="/vinilos">Vinilos</a>
            </div>
            <div class="footer-section">
                <h3>Contacto</h3>
                <a href="/contacto">Formulario de contacto</a>
                <p>email@ritmoretro.com</p>
                <p>+34 123 456 789</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Ritmo Retro. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>