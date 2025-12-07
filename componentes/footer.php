<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Ritmo Retro</h3>
            <p>Tu tienda de confianza para vinilos y CDs.</p>
        </div>
        <div class="footer-section">
            <h3>Enlaces Rápidos</h3>
            <a href="<?php echo isset($basePath)
                            ? $basePath . "index.php"
                            : "./index.php"; ?>">Inicio</a>
            <a href="<?php echo isset($basePath)
                            ? $basePath . "views/cds.php"
                            : "./views/cds.php"; ?>">CDs</a>
            <a href="<?php echo isset($basePath)
                            ? $basePath . "views/vinilos.php"
                            : "./views/vinilos.php"; ?>">Vinilos</a>
        </div>
        <div class="footer-section">
            <h3>Contacto</h3>
            <a href="<?php echo isset($basePath)
                            ? $basePath . "views/contacto.php"
                            : "./views/contacto.php"; ?>">Contacto</a>
            <p>email@ritmoretro.com</p>
            <p>+593 98 765 4321</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 Ritmo Retro. Todos los derechos reservados.</p>
    </div>
</footer>
</body>

</html>