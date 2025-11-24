<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vinilos - Ritmo Retro</title>
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
        <a href="/CDS">CDs</a>
        <a href="/vinilos" class="active">Vinilos</a>
        <a href="/busqueda">Búsqueda</a>
        <a href="/login">Iniciar sesión</a>
        <a href="/contacto">Contacto</a>
    </nav>

    <main class="main-content">
        <div class="page-header">
            <h1>Colección de Vinilos</h1>
            <p>Experimenta la magia del sonido analógico con nuestros vinilos de edición especial</p>
        </div>

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
                <!-- Vinilo 1 -->
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

                <!-- Vinilo 2 -->
                <div class="product-card vinyl-card">
                    <div class="product-image vinyl-image">
                        <span class="vinyl-label">Vinilo</span>
                        <span class="condition-badge nuevo">Nuevo</span>
                    </div>
                    <div class="product-info">
                        <h3>Led Zeppelin IV</h3>
                        <p class="artist">Led Zeppelin</p>
                        <p class="details">1971 • Reedición 2023 • 180g</p>
                        <div class="rating">
                            ★★★★☆ <span class="rating-count">(167)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$42.99</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- Vinilo 3 -->
                <div class="product-card vinyl-card">
                    <div class="product-image vinyl-image">
                        <span class="vinyl-label">Vinilo</span>
                        <span class="condition-badge like-new">Como Nuevo</span>
                    </div>
                    <div class="product-info">
                        <h3>What's Going On</h3>
                        <p class="artist">Marvin Gaye</p>
                        <p class="details">1971 • Edición Original</p>
                        <div class="rating">
                            ★★★★★ <span class="rating-count">(98)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$38.50</span>
                            <span class="original-price">$45.00</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- Vinilo 4 -->
                <div class="product-card vinyl-card">
                    <div class="product-image vinyl-image">
                        <span class="vinyl-label">Vinilo</span>
                        <span class="condition-badge nuevo">Nuevo</span>
                    </div>
                    <div class="product-info">
                        <h3>The Rise and Fall of Ziggy Stardust</h3>
                        <p class="artist">David Bowie</p>
                        <p class="details">1972 • Edición 50 Aniversario</p>
                        <div class="rating">
                            ★★★★☆ <span class="rating-count">(134)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$45.99</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- Vinilo 5 -->
                <div class="product-card vinyl-card">
                    <div class="product-image vinyl-image">
                        <span class="vinyl-label">Vinilo</span>
                        <span class="condition-badge muy-bueno">Muy Bueno</span>
                    </div>
                    <div class="product-info">
                        <h3>Blue</h3>
                        <p class="artist">Joni Mitchell</p>
                        <p class="details">1971 • Edición Original</p>
                        <div class="rating">
                            ★★★★☆ <span class="rating-count">(87)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$32.75</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- Vinilo 6 -->
                <div class="product-card vinyl-card">
                    <div class="product-image vinyl-image">
                        <span class="vinyl-label">Vinilo</span>
                        <span class="condition-badge nuevo">Nuevo</span>
                    </div>
                    <div class="product-info">
                        <h3>Hotel California</h3>
                        <p class="artist">Eagles</p>
                        <p class="details">1976 • Reedición 180g</p>
                        <div class="rating">
                            ★★★★☆ <span class="rating-count">(156)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$39.99</span>
                            <span class="original-price">$44.99</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- Vinilo 7 -->
                <div class="product-card vinyl-card">
                    <div class="product-image vinyl-image">
                        <span class="vinyl-label">Vinilo</span>
                        <span class="condition-badge excelente">Excelente</span>
                    </div>
                    <div class="product-info">
                        <h3>Revolver</h3>
                        <p class="artist">The Beatles</p>
                        <p class="details">1966 • Edición Original UK</p>
                        <div class="rating">
                            ★★★★★ <span class="rating-count">(189)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$52.99</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>

                <!-- Vinilo 8 -->
                <div class="product-card vinyl-card">
                    <div class="product-image vinyl-image">
                        <span class="vinyl-label">Vinilo</span>
                        <span class="condition-badge nuevo">Nuevo</span>
                    </div>
                    <div class="product-info">
                        <h3>The Dark Side of the Moon</h3>
                        <p class="artist">Pink Floyd</p>
                        <p class="details">1973 • Edición 180g</p>
                        <div class="rating">
                            ★★★★★ <span class="rating-count">(223)</span>
                        </div>
                        <div class="price-section">
                            <span class="price">$47.50</span>
                        </div>
                        <button class="add-to-cart">Añadir al Carrito</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="vinyls-info">
            <div class="info-content">
                <h2>La Experiencia del Vinilo</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <h3>Sonido Analógico Auténtico</h3>
                        <p>Disfruta del sonido cálido y rico que solo el vinilo puede ofrecer, con toda la profundidad y matices de la grabación original.</p>
                    </div>
                    <div class="info-item">
                        <h3>Arte y Presentación</h3>
                        <p>Portadas de gran tamaño, libretos con letras y arte interior que convierten cada álbum en una pieza de coleccionista.</p>
                    </div>
                    <div class="info-item">
                        <h3>Experiencia Táctil</h3>
                        <p>Desde colocar la aguja hasta cambiar de cara, el vinilo transforma la escucha en un ritual consciente y especial.</p>
                    </div>
                    <div class="info-item">
                        <h3>Valor de Colección</h3>
                        <p>Los vinilos no solo suenan mejor, sino que también conservan y aumentan su valor con el tiempo.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="care-guide">
            <div class="care-content">
                <h2>Cuidado de tus Vinilos</h2>
                <div class="care-tips">
                    <div class="care-tip">
                        <h3>💿 Limpieza Regular</h3>
                        <p>Usa un cepillo antiestático antes de cada reproducción para eliminar el polvo.</p>
                    </div>
                    <div class="care-tip">
                        <h3>📦 Almacenamiento Correcto</h3>
                        <p>Guarda los vinilos verticalmente y alejados de fuentes de calor y humedad.</p>
                    </div>
                    <div class="care-tip">
                        <h3>👐 Manipulación Adecuada</h3>
                        <p>Sostén los discos por los bordes y la etiqueta central para evitar marcas.</p>
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